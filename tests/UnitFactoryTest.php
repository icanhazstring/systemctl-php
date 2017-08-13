<?php

namespace SystemCtl\Test;

use League\Plates\Engine;
use PHPUnit\Framework\TestCase;
use SystemCtl\Exception\ConfigurationNotSupported;
use SystemCtl\Template\PlatesRenderer;
use SystemCtl\Template\RendererInterface;
use SystemCtl\Template\UnitTemplate;
use SystemCtl\Template\UnitType;
use SystemCtl\Test\Template\PlatesRendererTest;
use SystemCtl\UnitFactory;
use Vfs\FileSystem;

class UnitFactoryTest extends TestCase
{
    /** @var FileSystem */
    protected static $fileSystem;

    public static function setUpBeforeClass()
    {
        self::$fileSystem = FileSystem::factory('vfs://');
        self::$fileSystem->mount();
    }

    public static function tearDownAfterClass()
    {
        self::$fileSystem->unmount();
    }

    public function testSimpleUnitCreation()
    {
        $unitTemplate = UnitFactory::create('TestService');

        $this->assertInstanceOf(UnitTemplate::class, $unitTemplate);
        $this->assertInstanceOf(RendererInterface::class, $unitTemplate->getRenderer());
        $this->assertEquals('TestService', $unitTemplate->getName());
        $this->assertEquals('multi-user.target', $unitTemplate->getWantedBy());
    }

    public function testFullUnitCreation()
    {
        $unitTemplate = UnitFactory::create('TestService')
            ->setDescription('TestDescription')
            ->setAfter(['test.service'])
            ->setRequired(['required.service'])
            ->setWants(['wanted.service'])
            ->setConflicts(['testMail.service'])
            ->setType(UnitType::SIMPLE)
            ->setExecStart('/test/command/start')
            ->setExecStop('/test/command/stop')
            ->setExecReload('/test/command/reload')
            ->setEnvironmentFile('/etc/environment')
            ->setPIDFile('/etc/service.pid')
            ->setWantedBy('test.target');

        $this->assertEquals('TestService', $unitTemplate->getName());
        $this->assertEquals('TestDescription', $unitTemplate->getDescription());
        $this->assertEquals(['test.service'], $unitTemplate->getAfter());
        $this->assertEquals(['required.service'], $unitTemplate->getRequired());
        $this->assertEquals(['wanted.service'], $unitTemplate->getWants());
        $this->assertEquals(['testMail.service'], $unitTemplate->getConflicts());
        $this->assertEquals(UnitType::SIMPLE, $unitTemplate->getType());
        $this->assertEquals('/test/command/start', $unitTemplate->getExecStart());
        $this->assertEquals('/test/command/stop', $unitTemplate->getExecStop());
        $this->assertEquals('/test/command/reload', $unitTemplate->getExecReload());
        $this->assertEquals('/etc/environment', $unitTemplate->getEnvironmentFile());
        $this->assertEquals('/etc/service.pid', $unitTemplate->getPIDFile());
        $this->assertEquals('test.target', $unitTemplate->getWantedBy());
    }

    public function testInvalidUnitTypeSetter()
    {
        $unitTemplate = UnitFactory::create('TestService');
        $this->expectException(ConfigurationNotSupported::class);
        $unitTemplate->setType('notsupported');
    }

    public function testLateStaticBindings()
    {
        $engine = new Engine('vfs://');
        $renderer = new PlatesRenderer($engine);

        UnitFactory::setInstallPath('/test');
        UnitFactory::setTemplatePath('vfs://');
        UnitFactory::setDefaultRenderer($renderer);

        $unitFactory = new UnitFactory();
        $refObject = new \ReflectionObject($unitFactory);

        $installProperty = $refObject->getProperty('installPath');
        $installProperty->setAccessible(true);

        $templateProperty = $refObject->getProperty('templatePath');
        $templateProperty->setAccessible(true);

        $rendererProperty = $refObject->getProperty('renderer');
        $rendererProperty->setAccessible(true);

        $this->assertEquals('/test', $installProperty->getValue());
        $this->assertEquals('vfs://', $templateProperty->getValue());
        $this->assertInstanceOf(PlatesRenderer::class, $rendererProperty->getValue());
    }
}
