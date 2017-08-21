<?php

namespace SystemCtl\Template\Installer;

use SystemCtl\Exception\UnitFileExistsException;
use SystemCtl\Template\AbstractUnitTemplate;
use SystemCtl\Template\RendererInterface;

class UnitInstaller implements UnitInstallerInterface
{
    /** @var string */
    private $path;

    /** @var RendererInterface */
    private $renderer;

    /**
     * @inheritDoc
     */
    public function setPath(string $path): UnitInstallerInterface
    {
        $this->path = rtrim($path, '/');
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setRenderer(RendererInterface $renderer): UnitInstallerInterface
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getRenderer(): RendererInterface
    {
        return $this->renderer;
    }

    /**
     * @inheritDoc
     */
    public function install(AbstractUnitTemplate $unitTemplate): bool
    {
        $unitSuffix = $unitTemplate->getUnitSuffix();
        $unitName = $unitTemplate->getUnitName();

        $targetFile = $this->path . DIRECTORY_SEPARATOR . $unitName . '.' . $unitSuffix;

        if (\file_exists($targetFile)) {
            throw UnitFileExistsException::create($unitName, $unitSuffix);
        }

        $content = $this->getRenderer()->render('unit-template', $unitTemplate);

        return file_put_contents($targetFile, $content) !== false;
    }
}
