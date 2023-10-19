<?php

/*
Plugin Name: PDF Report
Plugin URI: http://github.com/oorkan/pdf-report/
Description: An awesome plugin to send personalized astrology PDF reports
Author: oorkan
Version: 1.2.2
Author URI: https://oorkan.dev/
*/

// ini_set("xdebug.var_display_max_children", '-1');
// ini_set("xdebug.var_display_max_data", '-1');
// ini_set("xdebug.var_display_max_depth", '-1');

require_once "inc/countrylist.php";
require_once "inc/pages/admin/settings.php";
require_once "vendor/autoload.php";
require_once "inc/custom-validators/NonceVerified.php";
require_once "inc/custom-validators/NonceVerifiedValidator.php";
require_once "inc/custom-validators/Year.php";
require_once "inc/custom-validators/YearValidator.php";
require_once "inc/custom-validators/Month.php";
require_once "inc/custom-validators/MonthValidator.php";
require_once "inc/custom-validators/Day.php";
require_once "inc/custom-validators/DayValidator.php";
require_once "inc/custom-validators/Hour.php";
require_once "inc/custom-validators/HourValidator.php";
require_once "inc/custom-validators/Minute.php";
require_once "inc/custom-validators/MinuteValidator.php";
require_once "inc/custom-validators/Meridiem.php";
require_once "inc/custom-validators/MeridiemValidator.php";

use GuzzleHttp\Client;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

$env = get_option("pdfr", []) + get_option("pdfr-settings", []) + get_option("pdfr-extra", []);

function wooThankyouRedirect($orderId)
{
    global $env;
    $order = wc_get_order($orderId);

    if (!$order->has_status("failed")) {
        $orderBilling = $order->data["billing"];
        $orderKey = $order->order_key;
        $items = $order->get_items();

        if (count($items) === 1) {
            $item = array_values($items)[0];
            $product = wc_get_product($item->get_product_id());
            $productType = strtolower(trim($product->get_attribute("type")));

            if ($productType) {
                $state = WC()->countries->get_states($orderBilling["country"])[$orderBilling["state"]];
                if (!$state) {
                    $state = $orderBilling["state"];
                }

                $orderKeyNormalized = str_replace("wc_order_", "", $orderKey);
                $urlParams = http_build_query([
                    "order" => str_replace("wc_order_", "", $orderKeyNormalized),
                    "firstname" => $orderBilling["first_name"],
                    "lastname" => $orderBilling["last_name"],
                    "city" => $orderBilling["city"],
                    "state" => $state,
                    "country" => $orderBilling["country"],
                    "email" => $orderBilling["email"]
                ], "", "&", PHP_QUERY_RFC3986);


                $transientName = "pdfr_o_{$orderKeyNormalized}";
                $transient = get_transient($transientName);

                if ($transient === false) {
                    $linkExpiryTime = 24 * 60 * 60;
                    if (set_transient("pdfr_o_{$orderKeyNormalized}", $orderKeyNormalized, $linkExpiryTime)) {
                        $formPage = $env["form-page-{$productType}"];
                        wp_redirect("{$formPage}?{$urlParams}", 301);
                        exit;
                    }
                }
            }
        }
    }
}
add_action("woocommerce_thankyou", "wooThankyouRedirect");

function wooProductPageRedirect()
{
    if (is_singular("product")) {
        global $post;
        $product = wc_get_product($post->ID);

        if (is_a($product, "WC_Product")) {
            $lpSlug = $product->get_attribute("lp-slug");

            if (is_string($lpSlug) && !empty($lpSlug)) {
                $lpSlug = trim($lpSlug);

                wp_redirect("/{$lpSlug}");
                exit;
            }
        }
    }
}
add_action("template_redirect", "wooProductPageRedirect");

// Function to run process in background
function run($type, $params, $contactEmail, $host, $outputFile = "/dev/null")
{
    global $env;

    $command = "php " . __DIR__ . "/pdf-report-script.php -t {$type} ";
    $command .= "-d '" . json_encode($params) . "' ";
    $command .= "-e '" . json_encode($env) . "' ";
    $command .= "-c '{$contactEmail}' ";
    $command .= "-h '{$host}'";

    $processId = shell_exec(sprintf(
        "%s > %s 2>&1 & echo $!",
        $command,
        $outputFile
    ));

    return $processId;
}

add_action("template_redirect", "pdfrFormRedirects");
add_shortcode("pdfr-form", "pdfrForm");
add_action("wp_enqueue_scripts", "pdfrStyles", 9);

function defaultFields(): array
{
    $fields = get_option("pdfr-settings");
    static $fieldsArr;

    $fieldsArr = [
        "language"         => "en",
        "footer_link"      => $fields["footer-link"],
        "logo_url"         => $fields["logo-url"],
        "company_name"     => $fields["company-name"],
        "company_info"     => $fields["company-info"],
        "domain_url"       => $fields["domain-url"],
        "company_email"    => $fields["company-email"],
        "company_landline" => $fields["company-landline"],
        "company_mobile"   => $fields["company-mobile"]
    ];

    return $fieldsArr;
}

function printCountries($inputFor)
{
    global $countrylist;

    $countriesHTML = "";
    foreach ($countrylist as $countryCode => $countryName) {
        $isSelectedCountry = (isset($_POST[$inputFor]) && $_POST[$inputFor] === $countryCode) ? "selected" : "";
        if (isset($_GET["country"]) && $_GET["country"] === $countryCode) {
            $isSelectedCountry = "selected";
        }

        $countriesHTML .= <<<HTML
            <option value="{$countryCode}"
                    $isSelectedCountry
            >{$countryName}</option>
        HTML;
    }

    return $countriesHTML;
}

function printViolation($inputFor, $violations)
{
    $violationsArr = [];
    foreach ($violations as $violation) {
        $violationsArr[$violation->getPropertyPath()] = $violation->getMessage();
    }

    if (isset($violationsArr["[$inputFor]"])) {
        return <<<HTML
            <div class="error text-danger" role="alert">
                {$violationsArr["[$inputFor]"]}
            </div>
        HTML;
    }

    return "";
}

function getLocation(string $address): array
{
    global $env;

    $params = [
        "address" => $address,
        "key" => $env["maps-api-key"]
    ];

    $response = (new Client())->request(
        "GET",
        "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($params["address"])
                                                                     . "&key={$params['key']}"
    )->getBody()->getContents();

    $responseObj = json_decode($response);
    $location = $responseObj->results[0]->geometry->location;

    return [
        "lat" => $location->lat,
        "lng" => $location->lng
    ];
}

function getTimezoneOffset(array $location): float
{
    global $env;

    $params = [
        "location" => $location,
        "key" => $env["maps-api-key"]
    ];

    $response = (new Client())->request(
        "GET",
        "https://maps.googleapis.com/maps/api/timezone/json?location="
            . urlencode("{$params['location']['lat']},{$params['location']['lng']}")
            . "&timestamp="
            . time()
            . "&key={$params['key']}"
    )->getBody()->getContents();

    $responseObj = json_decode($response);
    $tzOffset = ($responseObj->rawOffset + $responseObj->dstOffset);

    return $tzOffset;
}


function pdfrFormRedirects()
{
    if (!is_singular()) return;

    global $post;

    $isOxygenInstalled = is_plugin_active("oxygen/functions.php");

    if (empty($post->post_content) && !$isOxygenInstalled) return;

    $shortcodeSlug = "pdfr-form";
    $shortcodeRegex = get_shortcode_regex([$shortcodeSlug]);
    $matches = []; $matchesOxy = [];
    $isOnShortcodePage = false;

    preg_match_all("/{$shortcodeRegex}/", $post->post_content, $matches);

    if ($isOxygenInstalled) {
        $oxyPageData = get_post_meta($post->ID, "ct_builder_json", true);
        preg_match_all("/{$shortcodeRegex}/", $oxyPageData, $matchesOxy);
    }

    if (!empty($matches[2]) && in_array($shortcodeSlug, $matches[2])) $isOnShortcodePage = true;
    if (!empty($matchesOxy[2]) && in_array($shortcodeSlug, $matchesOxy[2])) $isOnShortcodePage = true;

    if ($isOnShortcodePage) {
        if (!isset($_GET["order"]) && !is_admin() && !isset($_GET["ct_builder"]) && !isset($_GET["action"])) {
            wp_redirect("/contact-us");
            exit;
        }

        if (isset($_GET["order"])) {
            $orderExists = wc_get_order_id_by_order_key("wc_order_{$_GET['order']}");
            if (!$orderExists) {
                wp_redirect("/contact-us");
                exit;
            }

            $transientName = "pdfr_o_{$_GET['order']}";
            $transient = get_transient($transientName);

            if ($transient === false) {
                wp_redirect("/contact-us");
                exit;
            }
        }
    }
}

function pdfrForm($atts)
{
    global $env;
    $nonceField = wp_nonce_field("{$atts["type"]}-form-submit", "{$atts["type"]}-form-nonce", true, false);

    if (isset($_POST["pdfr-form-submit"])) {
        // validation
        $validator = Validation::createValidator();

        $groups = new Assert\GroupSequence(["Default", "custom"]);

        $constraints = new Assert\Collection([
            "pdfr-form-submit" => new Assert\Blank(),
            "pdfr-form-input-name" => [
                new Assert\Length(["min" => 2]),
                new Assert\NotBlank()
            ],
            "pdfr-form-input-year" => [
                new Assert\Year(),
                new Assert\NotBlank()
            ],
            "pdfr-form-input-month" => [
                new Assert\Month(),
                new Assert\NotBlank()
            ],
            "pdfr-form-input-day" => [
                new Assert\Day(),
                new Assert\NotBlank()
            ],
            "pdfr-form-input-hour" => [
                new Assert\Hour(),
                new Assert\NotBlank()
            ],
            "pdfr-form-input-minute" => [
                new Assert\Minute(),
                new Assert\NotBlank()
            ],
            "pdfr-form-input-meridiem" => [
                new Assert\Meridiem(),
                new Assert\NotBlank()
            ],
            "pdfr-form-input-country" => new Assert\Country(),
            "pdfr-form-input-city" => new Assert\NotBlank(),
            "pdfr-form-input-state" => new Assert\NotNull(),
            "_wp_http_referer" => new Assert\NotBlank(),
            "{$atts["type"]}-form-nonce" => new Assert\NonceVerified("{$atts["type"]}-form-submit")
        ]);

        if ($atts["type"] === "synastry") {
            $constraints = new Assert\Collection([
                "pdfr-form-submit" => new Assert\Blank(),
                "pdfr-form-input-firstname" => [
                    new Assert\Length(["min" => 2]),
                    new Assert\NotBlank()
                ],
                "pdfr-form-input-lastname" => [
                    new Assert\Length(["min" => 2]),
                    new Assert\NotBlank()
                ],
                "pdfr-form-input-year" => [
                    new Assert\Year(),
                    new Assert\NotBlank()
                ],
                "pdfr-form-input-month" => [
                    new Assert\Month(),
                    new Assert\NotBlank()
                ],
                "pdfr-form-input-day" => [
                    new Assert\Day(),
                    new Assert\NotBlank()
                ],
                "pdfr-form-input-hour" => [
                    new Assert\Hour(),
                    new Assert\NotBlank()
                ],
                "pdfr-form-input-minute" => [
                    new Assert\Minute(),
                    new Assert\NotBlank()
                ],
                "pdfr-form-input-meridiem" => [
                    new Assert\Meridiem(),
                    new Assert\NotBlank()
                ],
                "pdfr-form-input-country" => new Assert\Country(),
                "pdfr-form-input-city" => new Assert\NotBlank(),
                "pdfr-form-input-state" => new Assert\NotNull(),
                "pdfr-form-input-sfirstname" => [
                    new Assert\Length(["min" => 2]),
                    new Assert\NotBlank()
                ],
                "pdfr-form-input-slastname" => [
                    new Assert\Length(["min" => 2]),
                    new Assert\NotBlank()
                ],
                "pdfr-form-input-syear" => [
                    new Assert\Year(),
                    new Assert\NotBlank()
                ],
                "pdfr-form-input-smonth" => [
                    new Assert\Month(),
                    new Assert\NotBlank()
                ],
                "pdfr-form-input-sday" => [
                    new Assert\Day(),
                    new Assert\NotBlank()
                ],
                "pdfr-form-input-shour" => [
                    new Assert\Hour(),
                    new Assert\NotBlank()
                ],
                "pdfr-form-input-sminute" => [
                    new Assert\Minute(),
                    new Assert\NotBlank()
                ],
                "pdfr-form-input-smeridiem" => [
                    new Assert\Meridiem(),
                    new Assert\NotBlank()
                ],
                "pdfr-form-input-scountry" => new Assert\Country(),
                "pdfr-form-input-scity" => new Assert\NotBlank(),
                "pdfr-form-input-sstate" => new Assert\NotNull(),
                "_wp_http_referer" => new Assert\NotBlank(),
                "{$atts["type"]}-form-nonce" => new Assert\NonceVerified("{$atts["type"]}-form-submit")
            ]);
        }

        $violations = $validator->validate($_POST, $constraints, $groups);

        if (count($violations)) {
            require_once "views/form-{$atts["type"]}.php";
        } else {
            $cmdArr = [] + defaultFields();
            if ($atts["type"] === "solar") {
                $cmdArr = $cmdArr + ["solar_year" => (int) date("Y")];
            }

            $birthdate = date_parse(DateTime::createFromFormat(
                "Y-m-d H:i A",
                sprintf(
                    "%04d-%02d-%02d %02d:%02d %02s",
                    $_POST["pdfr-form-input-year"],
                    $_POST["pdfr-form-input-month"],
                    $_POST["pdfr-form-input-day"],
                    $_POST["pdfr-form-input-hour"],
                    $_POST["pdfr-form-input-minute"],
                    $_POST["pdfr-form-input-meridiem"]
                )
            )->format("Y-m-d\TH:i"));

            $city = $_POST["pdfr-form-input-city"];
            $state = $_POST["pdfr-form-input-state"];
            $country = $_POST["pdfr-form-input-country"];

            $birthPlace = "{$city}, " . (!empty($state) ? "{$state}, " : "") . "{$country}";

            // location request
            $birthLocation = getLocation($birthPlace);

            // timezone request
            $birthTzOffset = getTimezoneOffset($birthLocation);

            if ($atts["type"] === "synastry") {
                $firstname = $_POST["pdfr-form-input-firstname"];
                $lastname = $_POST["pdfr-form-input-lastname"];

                $sFirstname = $_POST["pdfr-form-input-sfirstname"];
                $sLastname = $_POST["pdfr-form-input-slastname"];

                $sBirthdate = date_parse(DateTime::createFromFormat(
                    "Y-m-d H:i A",
                    sprintf(
                        "%04d-%02d-%02d %02d:%02d %02s",
                        $_POST["pdfr-form-input-syear"],
                        $_POST["pdfr-form-input-smonth"],
                        $_POST["pdfr-form-input-sday"],
                        $_POST["pdfr-form-input-shour"],
                        $_POST["pdfr-form-input-sminute"],
                        $_POST["pdfr-form-input-smeridiem"]
                    )
                )->format("Y-m-d\TH:i"));

                $sCity = $_POST["pdfr-form-input-scity"];
                $sState = $_POST["pdfr-form-input-sstate"];
                $sCountry = $_POST["pdfr-form-input-scountry"];
                $sBirthPlace = "{$sCity}, " . (!empty($sState) ? "{$sState}, " : "") . "{$sCountry}";

                // partner's location request
                $sBirthLocation = getLocation($sBirthPlace);

                // partner's timezone request
                $sBirthTzOffset = getTimezoneOffset($sBirthLocation);

                $cmdArr = $cmdArr + [
                    "p_first_name"  => $firstname,
                    "p_last_name"   => $lastname,
                    "p_day"         => $birthdate["day"],
                    "p_month"       => $birthdate["month"],
                    "p_year"        => $birthdate["year"],
                    "p_hour"        => $birthdate["hour"],
                    "p_minute"      => $birthdate["minute"],
                    "p_latitude"    => $birthLocation["lat"],
                    "p_longitude"   => $birthLocation["lng"],
                    "p_timezone"    => (float) ($birthTzOffset / 3600),
                    "p_place"       => $birthPlace,
                    "s_first_name"  => $sFirstname,
                    "s_last_name"   => $sLastname,
                    "s_day"         => $sBirthdate["day"],
                    "s_month"       => $sBirthdate["month"],
                    "s_year"        => $sBirthdate["year"],
                    "s_hour"        => $sBirthdate["hour"],
                    "s_minute"      => $sBirthdate["minute"],
                    "s_latitude"    => $sBirthLocation["lat"],
                    "s_longitude"   => $sBirthLocation["lng"],
                    "s_timezone"    => (float) ($sBirthTzOffset / 3600),
                    "s_place"       => $sBirthPlace
                ];
            } else {
                $name = $_POST["pdfr-form-input-name"];

                $cmdArr = $cmdArr + [
                    "name"      => $name,
                    "day"       => $birthdate["day"],
                    "month"     => $birthdate["month"],
                    "year"      => $birthdate["year"],
                    "hour"      => $birthdate["hour"],
                    "minute"    => $birthdate["minute"],
                    "latitude"  => $birthLocation["lat"],
                    "longitude" => $birthLocation["lng"],
                    "timezone"  => (float) ($birthTzOffset / 3600),
                    "place"     => $birthPlace
                ];
            }

            $orderId = wc_get_order_id_by_order_key("wc_order_{$_GET['order']}");
            $order = wc_get_order($orderId);
            $orderBilling = $order->data["billing"];

            run($atts["type"], $cmdArr, $orderBilling["email"], wp_parse_url(get_site_url())["host"]);
            echo <<<HTML
                <section class="ct-section">
                    <div class="ct-section-inner-wrap">
                       <div class="ct-div-block">
                            {$env["thank-you-message"]}
                        </div>
                    </div>
                </section>
            HTML;
        }
    } else {
        require_once "views/form-{$atts["type"]}.php";
    }
}

function pdfrStyles()
{
    wp_register_style(
        "pdfr-form-styles",
        plugins_url("pdf-report/assets/css/form/form.css"),
        [],
        false,
        "all"
    );

    wp_enqueue_style("pdfr-form-styles");
}
