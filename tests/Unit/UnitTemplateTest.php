<?php

namespace SystemCtl\Test\Unit;

use PHPUnit\Framework\TestCase;
use SystemCtl\UnitFactory;
use Vfs\FileSystem;

class UnitTemplateTest extends TestCase
{
    /** @var FileSystem */
    protected static $fileSystem;

    public static function setUpBeforeClass()
    {
        self::$fileSystem = FileSystem::factory('vfs://');
        self::$fileSystem->mount();

        UnitFactory::setInstallPath('vfs:///');
    }

    public static function tearDownAfterClass()
    {
        self::$fileSystem->unmount();
    }

    public function testUnitInstall()
    {
        $unitTemplate = UnitFactory::create('TestService');
        $this->assertTrue(!$unitTemplate->install());
    }
}
