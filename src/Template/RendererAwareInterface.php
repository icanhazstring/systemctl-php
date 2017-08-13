<?php

namespace SystemCtl\Template;

interface RendererAwareInterface
{
    /**
     * @param RendererInterface $renderer
     * @return RendererAwareInterface
     */
    public function setRenderer(RendererInterface $renderer): RendererAwareInterface;

    /**
     * @return RendererInterface
     */
    public function getRenderer(): RendererInterface;
}
