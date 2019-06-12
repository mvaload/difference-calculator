<?php

namespace DiffCalc\differ;

use function DiffCalc\renderer\render;
use function DiffCalc\parser\parsedData;

function genDiff($filePath1, $filePath2, $format = 'pretty')
{
    $fileData1 = parsedData($filePath1);
    $fileData2 = parsedData($filePath2);
    $parsData = buildDiff($fileData1, $fileData2);

    return render($parsData, $format);
}

function buildDiff($arr1, $arr2)
{
    $keys = array_merge(array_keys($arr1), array_keys($arr2));
    $uniqueKeys = array_values(array_unique($keys));

    $tree = array_map(function ($key) use (&$arr1, &$arr2) {
        if (!isset($arr1[$key])) {
            return [
                'key' => $key,
                'type' => 'added',
                'valueAfter' => $arr2[$key],
            ];
        }
        if (!isset($arr2[$key])) {
            return [
                'key' => $key,
                'type' => 'deleted',
                'valueBefore' => $arr1[$key],
            ];
        }
        if (is_array($arr1[$key]) && is_array($arr2[$key])) {
            return [
                'key' => $key,
                'type' => 'nested',
                'children' => buildDiff($arr1[$key], $arr2[$key]),
            ];
        }
        if ($arr1[$key] !== $arr2[$key]) {
            return [
                'key' => $key,
                'type' => 'updated',
                'valueBefore' => $arr1[$key],
                'valueAfter' => $arr2[$key],
            ];
        }
        return [
            'key' => $key,
            'type' => 'fixed',
            'valueBefore' => $arr1[$key],
        ];
    }, $uniqueKeys);

    return $tree;
}
