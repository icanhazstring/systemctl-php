<?php

namespace SystemCtl;

use League\Plates;
use SystemCtl\Template\PlatesRenderer;
use SystemCtl\Template\RendererInterface;
use SystemCtl\Template\UnitTemplate;

/**
 * Class UnitFactory
 *
 * @package SystemCtl
 * @author icanhazstring <blubb0r05+github@gmail.com>
 */
class UnitFactory
{
    protected static $installPath = '/etc/systemd/system';
    protected static $templatePath = __DIR__ . '/../assets/';

    /** @var RendererInterface */
    protected static $renderer;

    /**
     * @param RendererInterface $renderer
     */
    public static function setDefaultRenderer(RendererInterface $renderer)
    {
        self::$renderer = $renderer;
    }

    /**
     * Set path to look for templates
     *
     * @param string $path
     */
    public static function setTemplatePath(string $path)
    {
        self::$templatePath = $path;
    }

    /**
     * @param string $path
     */
    public static function setInstallPath(string $path)
    {
        self::$installPath = $path;
    }

    /**
     * Create a new unit template
     *
     * @param string $unitName
     * @return UnitTemplate
     */
    public static function create(string $unitName): UnitTemplate
    {
        if (self::$renderer === null) {
            // Use default renderer if nothing was defined before
            $engine = new Plates\Engine(self::$templatePath, 'tpl');
            self::$renderer = new PlatesRenderer($engine);
        }

        $unitTemplate = new UnitTemplate($unitName, self::$installPath);
        $unitTemplate->setRenderer(self::$renderer);

        return $unitTemplate;
    }
}
