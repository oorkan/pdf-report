<?php

require_once("vendor/autoload.php");
require_once("inc/classes/CLIParser.php");
require_once("inc/classes/PDF.php");

use CMF\PDFReport\CLIParser;
use CMF\PDFReport\PDF;
use GuzzleHttp\Client;
use Symfony\Component\Filesystem\Filesystem;

$shortopts = (new CLIParser())->shortopts;
$env = $shortopts["env"];
$type = $shortopts["type"];
$contactEmail = $shortopts["contact"];
$host = $shortopts["host"];

$pdf = (new PDF());

$pdf->compat(
    __DIR__ . "/"
            . "pdf/front-pages/optimized/{$type}.pdf",
    __DIR__ . "/"
            . "pdf/front-pages/{$type}-compat.pdf"
);

$pdfFile = $pdf->download($pdf->generate($type), __DIR__ . "/" . "pdf/origin");

$pdf->compat(
    $pdfFile,
    pathinfo($pdfFile, PATHINFO_DIRNAME) . "/"
                                         . pathinfo($pdfFile, PATHINFO_FILENAME)
                                         . "-compat.pdf"
);

$pdf->modify(
    __DIR__ . "/"
            . "pdf/front-pages/{$type}-compat.pdf",
    pathinfo($pdfFile, PATHINFO_DIRNAME) . "/"
                                         . pathinfo($pdfFile, PATHINFO_FILENAME)
                                         . "-compat.pdf",
    __DIR__ . "/"
            . "pdf/mod/"
            . basename(pathinfo($pdfFile, PATHINFO_DIRNAME))
            . "/"
            . pathinfo($pdfFile, PATHINFO_FILENAME) . ".pdf"
);

$finalPdfFile = $pdf->move(
    __DIR__ . "/"
            . "pdf/mod/"
            . basename(pathinfo($pdfFile, PATHINFO_DIRNAME))
            . "/"
            . pathinfo($pdfFile, PATHINFO_FILENAME)
            . ".pdf",
    realpath(__DIR__ . "/../../..") . "/pdf/"
                                    . basename(pathinfo($pdfFile, PATHINFO_DIRNAME))
                                    . "/"
                                    . pathinfo($pdfFile, PATHINFO_FILENAME)
                                    . ".pdf"
);

$finalPdfFileRelative = basename(dirname($finalPdfFile)) . "/"
                                                         . rawurlencode(pathinfo($finalPdfFile, PATHINFO_FILENAME))
                                                         . ".pdf";

$response = (new Client())->request(
    "GET",
    "https://{$env['mautic-domain']}/api/contacts?search=" . urlencode($contactEmail) . "",
    [
        "headers" => [
            "Authorization" => "Basic " . base64_encode("{$env['mautic-login']}:{$env['mautic-password']}")
        ]
    ]
)->getBody()->getContents();

$responseDecoded = json_decode($response, true);
$contactId = array_values($responseDecoded["contacts"])[0]["id"];

$typeNormalized = str_replace("-", "_", $type);
if ($contactId) {
    $response = (new Client())->request(
        "PATCH",
        "https://{$env['mautic-domain']}/api/contacts/{$contactId}/edit",
        [
            "headers" => [
                "Authorization" => "Basic " . base64_encode("{$env['mautic-login']}:{$env['mautic-password']}"),
                "Content-Type" => "application/json"
            ],
            "json" => ["pdf_{$typeNormalized}" => "https://{$host}/pdf/{$finalPdfFileRelative}"]
        ]
    )->getBody()->getContents();

    // cleanup
    $fs = new Filesystem();
    $fs->remove(__DIR__ . "/pdf/mod/" . basename(pathinfo($pdfFile, PATHINFO_DIRNAME)));
    $fs->remove(__DIR__ . "/pdf/origin/" . basename(pathinfo($pdfFile, PATHINFO_DIRNAME)));
}
