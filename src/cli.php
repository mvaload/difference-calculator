<?php

namespace DiffCalc\cli;

use function DiffCalc\differ\genDiff;

const DOC = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help           Show this screen
  --format <fmt>      Report format [default: pretty]
DOC;

function run()
{
    $args = \Docopt::handle(DOC);
    $diff = genDiff($args['<firstFile>'], $args['<secondFile>'], $args['--format']);
    print_r($diff . PHP_EOL);
}
