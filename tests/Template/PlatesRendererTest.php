<?php

namespace SystemCtl\Test\Template;

use League\Plates\Engine;
use PHPUnit\Framework\TestCase;
use SystemCtl\Template\PlatesRenderer;
use SystemCtl\Template\UnitTemplate;
use Vfs\FileSystem;
use Vfs\Node\Directory;
use Vfs\Node\File;

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
            file_get_contents(__DIR__ . '/../assets/test-minimal.tpl')
        ));
    }

    public static function tearDownAfterClass()
    {
        self::$fileSystem->unmount();
    }

    public function testPlatesRenderer()
    {
        $engine = new Engine('vfs://assets/', 'tpl');
        $renderer = new PlatesRenderer($engine);

        $unitTemplate = new UnitTemplate('test', 'vfs://');
        $unitTemplate->setExecStart('/test/command/start');

        $result = $renderer->render('unit-template', $unitTemplate);
        $this->assertEquals(<<<EOT
[Unit]

[Service]

[Install]
WantedBy=multi-user.target
EOT
        , $result);
    }
}
