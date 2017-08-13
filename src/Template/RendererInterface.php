<?php

namespace SystemCtl\Template;

interface RendererInterface
{
    /**
     * Render a named template with given data
     *
     * @param string $templateFile
     * @param UnitTemplate $unitTemplate
     * @return string
     */
    public function render(string $templateFile, UnitTemplate $unitTemplate): string;
}
