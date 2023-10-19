<?php

namespace CMF\PDFReport;

use setasign\Fpdi\Fpdi as FPDI;
use GravityMedia\Ghostscript\Ghostscript;
use Symfony\Component\Process\Process;
use GuzzleHttp\Client;
use CMF\PDFReport\CLIParser;

class PDF
{
    public function __construct()
    {
        $this->shortopts = (new CLIParser())->shortopts;
        $this->env = $this->shortopts["env"];

        $this->filenamePrefixes = [
            "natal"         => $this->env["prefix-natal"],
            "solar"         => $this->env["prefix-solar"],
            "life-forecast" => $this->env["prefix-life-forecast"],
            "synastry"      => $this->env["prefix-synastry"]
        ];

        $this->APIUrls = [
            "natal"         => $this->env["api-url-natal"],
            "solar"         => $this->env["api-url-solar"],
            "life-forecast" => $this->env["api-url-life-forecast"],
            "synastry"      => $this->env["api-url-synastry"]
        ];
    }

    public function generate(string $type): string
    {
        $response = (new Client())->request("POST", $this->APIUrls[$type], [
            "auth" => [$this->env["api-key"], $this->env["api-password"]],
            "json" => $this->shortopts["data"]
        ]);
        return json_decode($response->getBody()->getContents())->pdf_url;
    }

    public function download(string $src, string $dest, ?string $customFilename = null)
    {
        $fileName = basename($src);
        $contactName = isset($this->shortopts["data"]["p_first_name"]) ?
                       "{$this->shortopts['data']['p_first_name']} {$this->shortopts['data']['p_last_name']}" :
                       $this->shortopts["data"]["name"];

        if ($customFilename) {
            $fileName = $customFilename;
        } else {
            $fileName = $this->filenamePrefixes[$this->shortopts["type"]] . " - " . $contactName . ".pdf";
        }

        $subfolder = bin2hex(random_bytes(20));
        mkdir(
            "{$dest}/{$subfolder}",
            0755,
            true,
        );
        $file = file_get_contents($src);

        if ($file) {
            if (file_put_contents("{$dest}/{$subfolder}/{$fileName}", $file)) {
                return realpath("{$dest}/{$subfolder}/{$fileName}");
            }
        }

        return null;
    }

    public function compat(string $inputFile, string $outputFile, float $compatLevel = 1.4): void
    {
        // Create Ghostscript object
        $ghostscript = new Ghostscript([
            "quiet" => false,
            "batch" => true
        ]);

        // Create and configure the device
        $device = $ghostscript->createPdfDevice($outputFile);
        $device->setCompatibilityLevel($compatLevel);

        // Create process
        $process = $device->createProcess($inputFile);

        // Print the command line
        // print "$ " . $process->getCommandLine() . PHP_EOL;

        // Run process
        $process->run(function ($type, $buffer) {
            if ($type === Process::ERR) {
                throw new \RuntimeException($buffer);
            }
        });
    }

    public function modify(string $frontPage, string $inputFile, string $outputFile, array $pageSize = [11, 8.5]): void
    {
        $pdf = new FPDI("P", "in", $pageSize);

        $pdf->AddPage();
        $pdf->setSourceFile($frontPage);
        $tplIdx = $pdf->importPage(1);
        $pdf->useTemplate($tplIdx);

        $pageCount = $pdf->setSourceFile($inputFile);

        //  Array of pages to skip -- modify this to fit your needs
        $skipPages = [1];

        //  Add all pages of source to new document
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            //  Skip undesired pages
            if (in_array($pageNo, $skipPages)) {
                continue;
            }

            //  Add page to the document
            $templateID = $pdf->importPage($pageNo);
            $pdf->getTemplateSize($templateID);
            $pdf->addPage();
            $pdf->useTemplate($templateID);
        }

        mkdir(
            pathinfo($outputFile, PATHINFO_DIRNAME),
            0755,
            true,
        );

        $pdf->Output($outputFile, "F");
    }

    public function move(string $src, string $dest): ?string
    {
        $destDir = pathinfo($dest, PATHINFO_DIRNAME);
        if (!file_exists($destDir)) {
            mkdir(
                $destDir,
                0755,
                true,
            );
        }

        if (rename($src, $dest)) {
            return $dest;
        }
    }
}
