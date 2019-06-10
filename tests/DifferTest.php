<?php
namespace DiffCalc\Tests;

use \PHPUnit\Framework\TestCase;

use function DiffCalc\differ\genDiff;

class DifferTest extends TestCase
{
    private function getPath($filename)
    {
        return 'tests' . DIRECTORY_SEPARATOR
            . 'files'  . DIRECTORY_SEPARATOR
            . $filename;
    }

    public function testGenDiffPretty()
    {
        $expected = file_get_contents($this->getPath('correct_diff'));

        $actual = genDiff(
            $this->getPath('before.json'),
            $this->getPath('after.json'),
            'pretty'
        );
        $this->assertEquals($expected, $actual);

        $actual = genDiff(
            $this->getPath('before.yaml'),
            $this->getPath('after.yaml'),
            'pretty'
        );
        $this->assertEquals($expected, $actual);
    }
}
