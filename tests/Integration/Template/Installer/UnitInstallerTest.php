<?php
declare(strict_types=1);

namespace SystemCtl\Tests\Integration\Template\Installer;

use PHPUnit\Framework\TestCase;
use SystemCtl\Exception\UnitFileExistsException;
use SystemCtl\Template\Installer\UnitInstaller;
use SystemCtl\Template\Renderer\PlatesRenderer;
use SystemCtl\Template\Section\ServiceSection;
use SystemCtl\Template\ServiceUnitTemplate;
use Vfs\FileSystem;
use Vfs\Node\Directory;
use Vfs\Node\File;

/**
 * UnitInstallerTest
 *
 * @package SystemCtl\Tests\Integration\Template\Installer
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class UnitInstallerTest extends TestCase
{
    /** @var FileSystem */
    private static $fileSystem;

    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass()
    {
        self::$fileSystem = FileSystem::factory('vfs://');
        self::$fileSystem->mount();

        self::$fileSystem->get('/')->add('units', new Directory);
        self::$fileSystem->get('/')->add('assets', new Directory);

        self::$fileSystem->get('/units')->add('testUnit.service', new File);
        self::$fileSystem->get('/assets/')->add('unit-template.tpl', new File(
            file_get_contents(__DIR__ . '/../../../../assets/unit-template.tpl')
        ));
    }

    /**
     * @inheritDoc
     */
    public static function tearDownAfterClass()
    {
        self::$fileSystem->unmount();
    }

    /**
     * @test
     */
    public function itShouldRaiseAnExceptionIfTargetFileExists()
    {
        $template = new ServiceUnitTemplate('testUnit');

        $installer = (new UnitInstaller)->setPath('vfs://units/');

        $this->expectException(UnitFileExistsException::class);
        $installer->install($template);
    }

    /**
     * @test
     */
    public function itShouldCreateTargetFile()
    {
        $template = new ServiceUnitTemplate('awesomeService');

        $template
            ->getServiceSection()
            ->setType(ServiceSection::TYPE_SIMPLE);

        $renderer = new PlatesRenderer('vfs://assets/');

        $installer = (new UnitInstaller)->setPath('vfs://units/')->setRenderer($renderer);

        self::assertTrue($installer->install($template));

        /** @var File $file */
        $file = self::$fileSystem->get('/units/awesomeService.service');

        $expected = <<<EOF
[Service]
Type=simple

EOF;


        self::assertEquals($expected, $file->getContent());
    }
}
