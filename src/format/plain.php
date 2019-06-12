<?php

namespace DiffCalc\format\plain;

function rendPlain($data, $root = '')
{
    $diff = array_map(function ($node) use ($root) {
        ['key' => $key, 'type' => $type] = $node;
        switch ($type) {
            case 'added':
                $value = plainString($node['valueAfter']);
                return "Property '{$root}{$key}' was added with value: '{$value}'";
            case 'deleted':
                $value = plainString($node['valueBefore']);
                return "Property '{$root}{$key}' was removed";
            case 'updated':
                $after = plainString($node['valueAfter']);
                $before = plainString($node['valueBefore']);
                return "Property '{$root}{$key}' was changed. From '{$before}' to '{$after}'";
            case 'nested':
                return rendPlain($node['children'], "{$root}{$key}.");
            case 'fixed':
                return '';
        }
    }, $data);

    $diffString = implode("\n", array_filter($diff, function ($item) {
        return !empty($item);
    }));

    return $diffString;
}

function plainString($value)
{
    if (is_bool($value)) {
        $strValue = json_encode($value);
    }
    if (is_array($value)) {
        $strValue = 'complex value';
    }
    return isset($strValue) ? $strValue : $value;
}
