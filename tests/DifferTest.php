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

    public function testGenDiffNestedPretty()
    {
        $expected = file_get_contents($this->getPath('correct_diff_nested'));

        $actual = genDiff(
            $this->getPath('before_nested.json'),
            $this->getPath('after_nested.json'),
            'pretty'
        );
        $this->assertEquals($expected, $actual);

        $actual = genDiff(
            $this->getPath('before_nested.yaml'),
            $this->getPath('after_nested.yaml'),
            'pretty'
        );
        $this->assertEquals($expected, $actual);
    }
}
