<?php
declare(strict_types=1);

namespace SystemCtl\Template\Installer;

use SystemCtl\Template\AbstractUnitTemplate;
use SystemCtl\Template\RendererInterface;
use SystemCtl\Template\UnitTemplateInterface;

/**
 * UnitInstallerInterface
 *
 * @package SystemCtl\Template\Installer
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
interface UnitInstallerInterface
{
    /**
     * Change install path
     *
     * @param string $path
     *
     * @return UnitInstallerInterface
     */
    public function setPath(string $path): UnitInstallerInterface;

    /**
     * @param RendererInterface $renderer
     *
     * @return UnitInstallerInterface
     */
    public function setRenderer(RendererInterface $renderer): UnitInstallerInterface;

    /**
     * @return RendererInterface
     */
    public function getRenderer(): RendererInterface;

    /**
     * Install a unit by template
     *
     * @param UnitTemplateInterface $unitTemplate
     * @param bool                  $overwrite
     * @return bool
     * @throw UnitNotInstalledException
     */
    public function install(UnitTemplateInterface $unitTemplate, bool $overwrite = false): bool;
}
