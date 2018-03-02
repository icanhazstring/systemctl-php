<?php
declare(strict_types=1);

namespace SystemCtl\Tests\Unit\Utils;

use PHPUnit\Framework\TestCase;
use SystemCtl\Utils\DefinitionConverter;

/**
 * DefinitionRendererTest
 *
 * @package SystemCtl\Tests\Unit\Utils
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class DefinitionRendererTest extends TestCase
{
    public function testRendererShouldReturnString()
    {
        self::assertTrue(\is_string(DefinitionConverter::convert(['test'])));
        self::assertTrue(\is_string(DefinitionConverter::convert('test')));
    }

    public function testConverterShouldReturnStringIfGiven()
    {
        self::assertEquals('test', DefinitionConverter::convert('test'));
    }

    public function testConvertShouldConvertArrayToSpaceSeperatedString()
    {
        self::assertEquals('test1 test2', DefinitionConverter::convert(['test1', 'test2']));
    }
}
