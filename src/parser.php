<?php

namespace DiffCalc\parser;

use Symfony\Component\Yaml\Yaml;

function getParser($content, $type)
{
    switch ($type) {
        case 'json':
            return json_decode($content, true);
        case 'yaml':
        case 'yml':
            return Yaml::parse($content);
        default:
            throw new \Exception("Unsupported data type '{$type}'");
    }
}

function parsedData($filePath)
{
    $type = pathinfo($filePath, PATHINFO_EXTENSION);
    $content = file_get_contents($filePath);
    $parse = getParser($content, $type);
    return $parse;
}
