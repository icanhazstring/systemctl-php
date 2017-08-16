<?php

namespace SystemCtl\Test\Unit\Utils;

use PHPUnit\Framework\TestCase;
use SystemCtl\Utils\DefinitionConverter;

class DefinitionRendererTest extends TestCase
{
    public function testRendererShouldReturnString()
    {
        $this->assertTrue(\is_string(DefinitionConverter::convert(['test'])));
        $this->assertTrue(\is_string(DefinitionConverter::convert('test')));
    }

    public function testConverterShouldReturnStringIfGiven()
    {
        $this->assertEquals('test', DefinitionConverter::convert('test'));
    }

    public function testConvertShouldConvertArrayToSpaceSeperatedString()
    {
        $this->assertEquals('test1 test2', DefinitionConverter::convert(['test1', 'test2']));
    }
}
