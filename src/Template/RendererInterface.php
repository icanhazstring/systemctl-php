<?php

namespace SystemCtl\Template;

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
