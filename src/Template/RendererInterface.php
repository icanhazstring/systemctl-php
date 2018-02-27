<?php
declare(strict_types=1);

namespace SystemCtl\Template;

/**
 * RendererInterface
 *
 * @package SystemCtl\Template
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
interface RendererInterface
{
    /**
     * Render a named template with given data
     *
     * @param string               $templateFile
     * @param AbstractUnitTemplate $unitTemplate
     *
     * @return string
     */
    public function render(string $templateFile, AbstractUnitTemplate $unitTemplate): string;
}
