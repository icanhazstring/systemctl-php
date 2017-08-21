<?php

namespace SystemCtl\Template\Renderer;

use League\Plates;
use SystemCtl\Template\RendererInterface;
use SystemCtl\Template\AbstractUnitTemplate;

/**
 * PlatesRenderer
 *
 * Wrapper for plates to be used with RendererInterface
 *
 * @package SystemCtl\Template
 * @author icanhazstring <blubb0r05+github@gmail.com>
 */
class PlatesRenderer implements RendererInterface
{
    /** @var Plates\Engine */
    private $engine;

    /**
     * TemplateRenderer constructor.
     * @param Plates\Engine $engine
     */
    public function __construct(Plates\Engine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * @inheritdoc
     */
    public function render(string $templateFile, AbstractUnitTemplate $unitTemplate): string
    {
        return $this->engine->render($templateFile, ['sections' => $unitTemplate->getSections()]);
    }
}
