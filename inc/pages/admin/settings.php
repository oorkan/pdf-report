<?php

defined("ABSPATH") || exit;

function pdfrSettingsInit()
{
    register_setting("pdfr", "pdfr");
    register_setting("pdfr-settings", "pdfr-settings");
    register_setting("pdfr-extra", "pdfr-extra");

    add_settings_section("astrology-api", "Astrology API", "", "pdfr");
    add_settings_section("mautic-api", "Mautic API", "", "pdfr");
    add_settings_section("maps-api", "Google Maps API", "", "pdfr");
    add_settings_section("form-pages", "User Form Pages", "", "pdfr");
    add_settings_section("default", "", "", "pdfr-settings");
    add_settings_section("default", "", "", "pdfr-extra");

    // Home
    add_settings_field(
        "api-key",
        "API Key",
        "pdfrFieldTextCb",
        "pdfr",
        "astrology-api",
        array(
            "option"   => "pdfr",
            "field-id" => "api-key"
        )
    );

    add_settings_field(
        "api-password",
        "API Password",
        "pdfrFieldTextCb",
        "pdfr",
        "astrology-api",
        array(
            "option"   => "pdfr",
            "field-id" => "api-password"
        )
    );

    add_settings_field(
        "api-url-natal",
        "API URL - Natal Horoscope",
        "pdfrFieldUrlCb",
        "pdfr",
        "astrology-api",
        array(
            "option"   => "pdfr",
            "field-id" => "api-url-natal"
        )
    );

    add_settings_field(
        "api-url-solar",
        "API URL - Solar Return",
        "pdfrFieldUrlCb",
        "pdfr",
        "astrology-api",
        array(
            "option"   => "pdfr",
            "field-id" => "api-url-solar"
        )
    );

    add_settings_field(
        "api-url-life-forecast",
        "API URL - Life Forecast",
        "pdfrFieldUrlCb",
        "pdfr",
        "astrology-api",
        array(
            "option"   => "pdfr",
            "field-id" => "api-url-life-forecast"
        )
    );

    add_settings_field(
        "api-url-synastry",
        "API URL - Synastry Report",
        "pdfrFieldUrlCb",
        "pdfr",
        "astrology-api",
        array(
            "option"   => "pdfr",
            "field-id" => "api-url-synastry"
        )
    );

    add_settings_field(
        "mautic-domain",
        "Domain",
        "pdfrFieldTextCb",
        "pdfr",
        "mautic-api",
        array(
            "option"   => "pdfr",
            "field-id" => "mautic-domain"
        )
    );

    add_settings_field(
        "mautic-login",
        "Login",
        "pdfrFieldTextCb",
        "pdfr",
        "mautic-api",
        array(
            "option"   => "pdfr",
            "field-id" => "mautic-login"
        )
    );

    add_settings_field(
        "mautic-password",
        "Password",
        "pdfrFieldTextCb",
        "pdfr",
        "mautic-api",
        array(
            "option"   => "pdfr",
            "field-id" => "mautic-password"
        )
    );

    add_settings_field(
        "maps-api-key",
        "API Key",
        "pdfrFieldTextCb",
        "pdfr",
        "maps-api",
        array(
            "option"   => "pdfr",
            "field-id" => "maps-api-key"
        )
    );

    add_settings_field(
        "form-page-natal",
        "Form Page URL - Natal Horoscope",
        "pdfrFieldUrlCb",
        "pdfr",
        "form-pages",
        array(
            "option"   => "pdfr",
            "field-id" => "form-page-natal"
        )
    );

    add_settings_field(
        "form-page-solar",
        "Form Page URL - Solar Return",
        "pdfrFieldUrlCb",
        "pdfr",
        "form-pages",
        array(
            "option"   => "pdfr",
            "field-id" => "form-page-solar"
        )
    );

    add_settings_field(
        "form-page-life-forecast",
        "Form Page URL - Life Forecast",
        "pdfrFieldUrlCb",
        "pdfr",
        "form-pages",
        array(
            "option"   => "pdfr",
            "field-id" => "form-page-life-forecast"
        )
    );

    add_settings_field(
        "form-page-synastry",
        "Form Page URL - Synastry Report",
        "pdfrFieldUrlCb",
        "pdfr",
        "form-pages",
        array(
            "option"   => "pdfr",
            "field-id" => "form-page-synastry"
        )
    );

    // Settings
    add_settings_field(
        "footer-link",
        "Footer Link",
        "pdfrFieldTextCb",
        "pdfr-settings",
        "default",
        array(
            "option"   => "pdfr-settings",
            "field-id" => "footer-link"
        )
    );

    add_settings_field(
        "logo-url",
        "Logo URL",
        "pdfrFieldUrlCb",
        "pdfr-settings",
        "default",
        array(
            "option"   => "pdfr-settings",
            "field-id" => "logo-url"
        )
    );

    add_settings_field(
        "company-name",
        "Company Name",
        "pdfrFieldTextCb",
        "pdfr-settings",
        "default",
        array(
            "option"   => "pdfr-settings",
            "field-id" => "company-name"
        )
    );

    add_settings_field(
        "company-info",
        "Company Info",
        "pdfrFieldTextboxCb",
        "pdfr-settings",
        "default",
        array(
            "option"   => "pdfr-settings",
            "field-id" => "company-info"
        )
    );

    add_settings_field(
        "domain-url",
        "Domain URL",
        "pdfrFieldUrlCb",
        "pdfr-settings",
        "default",
        array(
            "option"   => "pdfr-settings",
            "field-id" => "domain-url"
        )
    );

    add_settings_field(
        "company-email",
        "Company Email",
        "pdfrFieldEmailCb",
        "pdfr-settings",
        "default",
        array(
            "option"   => "pdfr-settings",
            "field-id" => "company-email"
        )
    );

    add_settings_field(
        "company-landline",
        "Company Landline",
        "pdfrFieldTelCb",
        "pdfr-settings",
        "default",
        array(
            "option"   => "pdfr-settings",
            "field-id" => "company-landline"
        )
    );

    add_settings_field(
        "company-mobile",
        "Company Mobile",
        "pdfrFieldTelCb",
        "pdfr-settings",
        "default",
        array(
            "option"   => "pdfr-settings",
            "field-id" => "company-mobile"
        )
    );

    // Extra
    add_settings_field(
        "prefix-natal",
        "Prefix - Natal Horoscope",
        "pdfrFieldTextCb",
        "pdfr-extra",
        "default",
        array(
            "option"   => "pdfr-extra",
            "field-id" => "prefix-natal"
        )
    );

    add_settings_field(
        "prefix-solar",
        "Prefix - Solar Return",
        "pdfrFieldTextCb",
        "pdfr-extra",
        "default",
        array(
            "option"   => "pdfr-extra",
            "field-id" => "prefix-solar"
        )
    );

    add_settings_field(
        "prefix-life-forecast",
        "Prefix - Life Forecast",
        "pdfrFieldTextCb",
        "pdfr-extra",
        "default",
        array(
            "option"   => "pdfr-extra",
            "field-id" => "prefix-life-forecast"
        )
    );

    add_settings_field(
        "prefix-synastry",
        "Prefix - Synastry Report",
        "pdfrFieldTextCb",
        "pdfr-extra",
        "default",
        array(
            "option"   => "pdfr-extra",
            "field-id" => "prefix-synastry"
        )
    );

    add_settings_field(
        "thank-you-message",
        "Thank You Message",
        "pdfrFieldTextboxCb",
        "pdfr-extra",
        "default",
        array(
            "option"   => "pdfr-extra",
            "field-id" => "thank-you-message"
        )
    );
}
add_action("admin_init", "pdfrSettingsInit");

function pdfrFieldUrlCb($args)
{
    $option = get_option($args["option"]);
    $fieldValue = isset($option[$args["field-id"]]) ? "value='" . esc_attr($option[$args["field-id"]]) . "'" : "";
    $fieldId = "{$args['option']}[{$args['field-id']}]";

    echo <<<HTML
        <div>
            <input type="url" name="{$fieldId}" $fieldValue>
        </div>
    HTML;
}

function pdfrFieldTextCb($args)
{
    $option = get_option($args["option"]);
    $fieldValue = isset($option[$args["field-id"]]) ? "value='" . esc_html($option[$args["field-id"]]) . "'" : "";
    $fieldId = "{$args['option']}[{$args['field-id']}]";

    echo <<<HTML
        <style>input:not([type="submit"]), textarea {min-width: 50%;}</style>
        <div>
            <input type="text" name="{$fieldId}" $fieldValue>
        </div>
    HTML;
}

function pdfrFieldTextboxCb($args)
{
    $option = get_option($args["option"]);
    $fieldValue = isset($option[$args["field-id"]]) ? esc_html($option[$args["field-id"]]) : "";
    $fieldId = "{$args['option']}[{$args['field-id']}]";

    echo <<<HTML
        <div>
            <textarea name="{$fieldId}" rows="5">$fieldValue</textarea>
        </div>
    HTML;
}

function pdfrFieldEmailCb($args)
{
    $option = get_option($args["option"]);
    $fieldValue = isset($option[$args["field-id"]]) ? "value='" . esc_attr($option[$args["field-id"]]) . "'" : "";
    $fieldId = "{$args['option']}[{$args['field-id']}]";

    echo <<<HTML
        <div>
            <input type="email" name="{$fieldId}" $fieldValue>
        </div>
    HTML;
}

function pdfrFieldTelCb($args)
{
    $option = get_option($args["option"]);
    $fieldValue = isset($option[$args["field-id"]]) ? "value='" . esc_attr($option[$args["field-id"]]) . "'" : "";
    $fieldId = "{$args['option']}[{$args['field-id']}]";

    echo <<<HTML
        <div>
            <input type="tel" name="{$fieldId}" $fieldValue>
        </div>
    HTML;
}

function pdfrAdminPages()
{
    add_menu_page(
        "PDF Report",
        "PDF Report",
        "manage_options",
        "pdfr",
        "",
        "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIj8+PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1MTIgNTEyIj48cGF0aCBkPSJtNDUxLjcgOTkuNzItNzEuNC03MS40NGMtMTUuNi0xNS41NS00Ni4zLTI4LjI4LTY4LjMtMjguMjhoLTI0MGMtMjIgMC00MCAxOC00MCA0MHY0MzJjMCAyMiAxOCA0MCA0MCA0MGgzNjhjMjIgMCA0MC0xOCA0MC00MHYtMzA0YzAtMjItMTIuNy01Mi43LTI4LjMtNjguMjh6Ii8+PHBhdGggZmlsbD0iI2ZmZiIgZD0ibTQ0OCA0NzJjMCA0LjMtMy43IDgtOCA4aC0zNjhjLTQuMzQgMC04LTMuNy04LTh2LTQzMmMwLTQuMzQgMy42Ni04IDgtOGgyNDBjMi40IDAgNS4xLjMwIDggLjg1djEyNy4yaDEyNy4xYy42IDIuOS45IDUuNi45IDh2MzA0eiIvPjxwYXRoIGQ9Im00MTQuNSAzMTYuOGMtMi4xIDEuMy04LjEgMi4xLTExLjkgMi4xLTEyLjQgMC0yNy42LTUuNy00OS4xLTE0LjkgOC4zLS42IDE1LjgtLjkgMjIuNi0uOSAxMi40IDAgMTYgMCAyOC4yIDMuMSAxMi4xIDMgMTIuMiA5LjMgMTAuMiAxMC42em0tMjE1LjEgMS45YzQuOC04LjQgOS43LTE3LjMgMTQuNy0yNi44IDEyLjItMjMuMSAyMC00MS4zIDI1LjctNTYuMiAxMS41IDIwLjkgMjUuOCAzOC42IDQyLjUgNTIuOCAyLjEgMS44IDQuMyAzLjUgNi43IDUuMy0zNC4xIDYuOC02My42IDE1LTg5LjYgMjQuOXptMzkuOC0yMTguOWM2LjggMCAxMC43IDE3LjA2IDExIDMzLjE2LjMgMTYtMy40IDI3LjItOC4xIDM1LjYtMy45LTEyLjQtNS43LTMxLjgtNS43LTQ0LjUgMCAwLS4zLTI0LjI2IDIuOC0yNC4yNnptLTEzMy40IDMwNy4yYzMuOS0xMC41IDE5LjEtMzEuMyA0MS42LTQ5LjggMS40LTEuMSA0LjktNC40IDguMS03LjQtMjMuNSAzNy42LTM5LjMgNTIuNS00OS43IDU3LjJ6bTMxNS4yLTExMi4zYy02LjgtNi43LTIyLTEwLjItNDUtMTAuNS0xNS42LS4yLTM0LjMgMS4yLTU0LjEgMy45LTguOC01LjEtMTcuOS0xMC42LTI1LjEtMTcuMy0xOS4yLTE4LTM1LjItNDIuOS00NS4yLTcwLjMuNi0yLjYgMS4yLTQuOCAxLjctNy4xIDAgMCAxMC44LTYxLjUgNy45LTgyLjMtLjQtMi45LS42LTMuNy0xLjQtNS45bC0uOS0yLjVjLTIuOS02Ljc2LTguNy0xMy45Ni0xNy44LTEzLjU3bC01LjMtLjE3aC0uMWMtMTAuMSAwLTE4LjQgNS4xNy0yMC41IDEyLjg0LTYuNiAyNC4zLjIgNjAuNSAxMi41IDEwNy40bC0zLjIgNy43Yy04LjggMjEuNC0xOS44IDQzLTI5LjUgNjJsLTEuMyAyLjVjLTEwLjIgMjAtMTkuNSAzNy0yNy45IDUxLjRsLTguNyA0LjZjLS42LjQtMTUuNSA4LjItMTkgMTAuMy0yOS42IDE3LjctNDkuMjggMzcuOC01Mi41NCA1My44LTEuMDQgNS0uMjYgMTEuNSA1LjAxIDE0LjZsOC40IDQuMmMzLjYzIDEuOCA3LjUzIDIuNyAxMS40MyAyLjcgMjEuMSAwIDQ1LjYtMjYuMiA3OS4zLTg1LjEgMzktMTIuNyA4My40LTIzLjMgMTIyLjMtMjkuMSAyOS42IDE2LjcgNjYgMjguMyA4OSAyOC4zIDQuMSAwIDcuNi0uNCAxMC41LTEuMiA0LjQtMS4xIDguMS0zLjYgMTAuNC03LjEgNC40LTYuNyA1LjQtMTUuOSA0LjEtMjUuNC0uMy0yLjgtMi42LTYuMy01LTguN3oiLz48cGF0aCBmaWxsPSIjZmZmIiBkPSJtNDI5LjEgMTIyLjNjMS42IDEuNiAzLjEgMy41IDQuNiA1LjdoLTgxLjd2LTgxLjczYzIuMiAxLjUyIDQuMSAzLjA4IDUuNyA0LjY0bDcxLjQgNzEuMzl6Ii8+PC9zdmc+"
    );

    add_submenu_page(
        "pdfr",
        "Home",
        "Home",
        "manage_options",
        "pdfr",
        function () {
            pdfrSettingsPageHtml("pdfr");
        }
    );

    add_submenu_page(
        "pdfr",
        "Settings",
        "Settings",
        "manage_options",
        "pdfr-settings",
        function () {
            pdfrSettingsPageHtml("pdfr-settings");
        }
    );

    add_submenu_page(
        "pdfr",
        "Extra",
        "Extra",
        "manage_options",
        "pdfr-extra",
        function () {
            pdfrSettingsPageHtml("pdfr-extra");
        }
    );
}
add_action("admin_menu", "pdfrAdminPages");


function pdfrSettingsPageHtml(string $page): void
{
    $optionGroup = $page;
    ?>
    <div class="wrap">
        <h1><?= esc_html(get_admin_page_title()) ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields($optionGroup);
            do_settings_sections($page);
            submit_button("Save Settings");
            ?>
        </form>
    </div>
    <?php
}
