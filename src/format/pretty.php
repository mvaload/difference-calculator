<?php

namespace DiffCalc\format\pretty;

function rendPretty(array $data, $level = 0)
{
    $spaces = '    ';
    $offset = str_repeat($spaces, $level);

    $diff = array_map(function ($node) use ($spaces, $offset, $level) {
        ['key' => $key, 'type' => $type] = $node;
        switch ($type) {
            case 'added':
                $value = prettyString($node['valueAfter'], $offset);
                return "{$offset}  + {$key}: {$value}";
            case 'deleted':
                $value = prettyString($node['valueBefore'], $offset);
                return "{$offset}  - {$key}: {$value}";
            case 'nested':
                $childrenDiff = rendPretty($node['children'], $level + 1);
                return "{$offset}{$spaces}{$key}: {$childrenDiff}";
            case 'updated':
                $after = prettyString($node['valueAfter'], $offset);
                $before = prettyString($node['valueBefore'], $offset);
                return "{$offset}  + {$key}: {$after}\n{$offset}  - {$key}: {$before}";
            case 'fixed':
                $value = prettyString($node['valueBefore'], $offset);
                return "{$offset}{$spaces}{$key}: {$value}";
        }
    }, $data);

    $leftBrace = "{\n";
    $diffString = implode("\n", $diff);
    $rightBrace = "\n{$offset}}";

    return "{$leftBrace}{$diffString}{$rightBrace}";
}

function prettyString($value, $offset)
{
    $spaces = '    ';
    if (is_bool($value)) {
        $strValue = json_encode($value);
    }
    if (is_array($value)) {
        $prettified = htmlspecialchars(json_encode($value, JSON_PRETTY_PRINT), ENT_QUOTES, 'UTF-8');
        $unquoted = str_replace('&quot;', '', $prettified);
        $indented = str_replace($spaces, "{$spaces}{$spaces}{$offset}", $unquoted);
        $strValue = str_replace('}', "{$spaces}{$offset}}", $indented);
    }
    return isset($strValue) ? $strValue : $value;
}
