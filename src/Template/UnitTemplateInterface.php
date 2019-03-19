<?php
declare(strict_types=1);

namespace SystemCtl\Template;

/**
 * UnitTemplateInterface
 *
 * @author icanhazstring <blubb0r05+github@gmail.com>
 */
interface UnitTemplateInterface
{
    /**
     * @return string
     */
    public function getUnitName(): string;

    /**
     * @return string
     */
    public function getUnitSuffix(): string;

    /**
     * Get all definitions for this template as array
     *
     * @return array
     */
    public function getSections(): array;
}
