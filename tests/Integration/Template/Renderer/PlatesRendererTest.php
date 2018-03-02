<?php
declare(strict_types=1);

namespace SystemCtl\Tests\Integration\Template\Renderer;

use PHPUnit\Framework\TestCase;
use SystemCtl\Template\Renderer\PlatesRenderer;
use SystemCtl\Template\Section\ServiceSection;
use SystemCtl\Template\ServiceUnitTemplate;
use Vfs\FileSystem;
use Vfs\Node\Directory;
use Vfs\Node\File;

/**
 * PlatesRendererTest
 *
 * @package SystemCtl\Tests\Unit\Template\Renderer
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class PlatesRendererTest extends TestCase
{
    /** @var FileSystem */
    protected static $fileSystem;

    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass()
    {
        self::$fileSystem = FileSystem::factory('vfs://');
        self::$fileSystem->mount();

        self::$fileSystem->get('/')->add('assets', new Directory());
        self::$fileSystem->get('/assets/')->add('unit-template.tpl', new File(
            file_get_contents(__DIR__ . '/../../../../assets/unit-template.tpl')
        ));
    }

    public static function tearDownAfterClass()
    {
        self::$fileSystem->unmount();
    }

    public function testPlatesRenderer()
    {
        $renderer = new PlatesRenderer('vfs://assets/', 'tpl');

        $unitTemplate = new ServiceUnitTemplate('test');

        $unitTemplate->getUnitSection()->setDescription('TestDescription');
        $unitTemplate->getServiceSection()->setType(ServiceSection::TYPE_SIMPLE);
        $unitTemplate->getInstallSection()->setWantedBy(['multi-user.target']);

        $result = $renderer->render('unit-template', $unitTemplate);
        self::assertEquals(<<<EOT
[Unit]
Description=TestDescription
[Service]
Type=simple
[Install]
WantedBy=multi-user.target

EOT
            , $result);
    }
}
