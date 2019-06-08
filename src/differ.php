<?php

namespace DiffCalc\differ;

use function DiffCalc\renderer\render;
use Funct\Collection;

function genDiff($filePath1, $filePath2, $format = 'pretty')
{
    $fileData1 = file_get_contents($filePath1);
    $fileData2 = file_get_contents($filePath2);

    $data1 = json_decode($fileData1, true);
    $data2 = json_decode($fileData2, true);

    $parsData = buildDiff($data1, $data2);

    return $parsData;
}


function buildDiff($arr1, $arr2)
{
    $keys = Collection\union(array_keys($arr1), array_keys($arr2));

    $diff = array_reduce($keys, function ($acc, $key) use ($arr1, $arr2) {
        if (array_key_exists($key, $arr1) && array_key_exists($key, $arr2)) {
            if (is_array($arr1[$key]) && is_array($arr2[$key])) {
                $acc[] = [
                    'type' => 'nested',
                    'key' => $key,
                    'children' => buildDiff($arr1[$key], $arr2[$key])
                ];
            } elseif ($arr2[$key] === $arr1[$key]) {
                $acc[] = [
                    'type' => 'fixed',
                    'key' => $key,
                    'value' => $arr2[$key]
                ];
            } else {
                $acc[] = [
                    'type' => 'updated',
                    'key' => $key,
                    'valueBefore' => $arr1[$key],
                    'valueAfter' => $arr2[$key]
                ];
            }
        } elseif (array_key_exists($key, $arr2)) {
            $acc[] = [
                'type' => 'added',
                'key' => $key,
                'valueAfter' => $arr2[$key]
            ];
        } else {
            $acc[] = [
                'type' => 'deleted',
                'key' => $key,
                'valueBefore' => $arr1[$key]
            ];
        }
        return $acc;
	}, []);

    return $diff;
}
