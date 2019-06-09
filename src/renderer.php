<?php

namespace DiffCalc\renderer;

use Funct\Collection;

const NUM_OF_SPACES = 4;

function render($data, $format)
{
    switch ($format) {
        case 'json':
            return \json_encode($data);
        case 'pretty':
            return rendPretty($data);
    }
}

function rendPretty($data, $level = 0)
{
    $spaces = str_repeat(" ", $level * NUM_OF_SPACES);
    $result = Collection\flattenAll(array_map(function ($node) use ($level, $spaces) {
        switch ($node['type']) {
            case 'nested':
                ['key' => $key, 'children' => $children] = $node;
                $tree = rendPretty($children, $level + 1);
                return "  {$spaces}  {$key}: {$tree}";
            case 'deleted':
                ['key' => $key, 'valueBefore' => $valueBefore] = $node;
                $oldVal = transferToStr($valueBefore, $level + 1);
                return "  {$spaces}- {$key}: {$oldVal}";
            case 'added':
                ['key' => $key, 'valueAfter' => $valueAfter] = $node;
                $newVal = transferToStr($valueAfter, $level + 1);
                return "  {$spaces}+ {$key}: {$newVal}";
            case 'updated':
                ['key' => $key, 'valueBefore' => $valueBefore, 'valueAfter' => $valueAfter] = $node;
                $oldVal = transferToStr($valueBefore, $level + 1);
                $newVal = transferToStr($valueAfter, $level + 1);
                return ["  {$spaces}+ {$key}: {$newVal}",
                        "  {$spaces}- {$key}: {$oldVal}"];
            case 'fixed':
                ['key' => $key, 'value' => $value] = $node;
                $oldVal = transferToStr($value, $level + 1);
                return "  {$spaces}  {$key}: {$oldVal}";
        }
    }, $data));
    return implode("\n", array_merge(['{'], $result, ["{$spaces}}"]));
}

function transferToStr($obj, $level)
{
    if (!is_object($obj)) {
        return encode($obj);
    }

    $iter = function ($obj, $level) use (&$iter) {
        $arr = get_object_vars($obj);
        $keys = array_keys($arr);
        $spaces = str_repeat(" ", $level * NUM_OF_SPACES);

        $result = array_map(function ($item) use ($spaces, $level, $arr) {
            if (is_object($arr[$item])) {
                $tree = $iter($arr[$item], $level + 1);
                return "{$spaces}    {$key}: {$tree}";
            }
            $value = encode($arr[$item]);
            $key = encode($item);
            return "{$spaces}    {$key}: {$value}";
        }, $keys);
        return implode("\n", array_merge(['{'], $result, ["{$spaces}}"]));
    };
    return $iter($obj, $level);
}

function encode($data)
{
    return trim(json_encode($data), '" ');
}
