<?php

namespace DiffCalc\renderer;

use function DiffCalc\format\pretty\rendPretty;
use function DiffCalc\format\plain\rendPlain;

function render($data, $format)
{
    switch ($format) {
        case 'json':
            return \json_encode($data);
        case 'pretty':
            return rendPretty($data);
        case 'plain':
            return rendPlain($data);
        default:
            throw new \Exception("Unsupported format '{$format}'");
    }
}
