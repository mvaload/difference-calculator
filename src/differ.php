<?php

namespace DiffCalc\differ;


function run()	
{
	$doc = <<<DOC
	Generate diff

	Usage:
	  gendiff (-h|--help)
	  gendiff [--format <fmt>] <firstFile> <secondFile>

	Options:
	  -h --help           Show this screen
	  --format <fmt>      Report format [default: pretty]
DOC;

	$args = \Docopt::handle($doc);
	foreach ($args as $key => $value) {
	    echo $key.': '.json_encode($value).PHP_EOL;
	}

	function getDiff($firstFile, $secondFile)
	{
		$file1 = file_get_contents($firstFile);
		$file2 = file_get_contents($secondFile);

		echo $file1 . "\n" . $file2;
	}

	getDiff($args['firstFile'], $args['secondFile']);
}