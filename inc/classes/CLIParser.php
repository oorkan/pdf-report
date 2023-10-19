<?php

namespace CMF\PDFReport;

class CLIParser
{
    public function __construct()
    {
        global $argv;
        $shortopts = getopt("t:d:e:c:h:", [], $argv);
        $shortoptsNormalized = [
            "type"     => $shortopts["t"],
            "data"     => json_decode($shortopts["d"], true),
            "env"      => json_decode($shortopts["e"], true),
            "contact"  => $shortopts["c"],
            "host"     => $shortopts["h"]
        ];

        $this->shortopts = $shortoptsNormalized;
    }
}
