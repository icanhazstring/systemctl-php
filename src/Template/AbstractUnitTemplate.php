<?php

namespace SystemCtl\Template;

use SebastianBergmann\CodeCoverage\Report\Xml\Unit;
use SystemCtl\Template\Section\AbstractSection;
use SystemCtl\Template\Section\InstallSection;
use SystemCtl\Template\Section\UnitSection;
use SystemCtl\Utils\DefinitionConverter;

/**
 * AbstractUnitTemplate
 *
 * Defines basic properties for a unit
 *
 * @package SystemCtl\Template
 * @author icanhazstring <blubb0r05+github@gmail.com>
 */
abstract class AbstractUnitTemplate
{
    /** @var string */
    protected $unitName;
    /** @var string */
    protected $unitSuffix;

    /** @var UnitSection */
    protected $unitSection;
    /** @var InstallSection */
    protected $installSection;

    /**
     * Create a new template for any unit
     *
     * @param string $unitName
     * @param string $unitSuffix
     */
    public function __construct(string $unitName, string $unitSuffix)
    {
        $this->unitName = $unitName;
        $this->unitSuffix = $unitSuffix;

        $this->unitSection = new UnitSection;
        $this->installSection = new InstallSection;
    }

    /**
     * @return AbstractSection
     */
    abstract public function getTypeSpecificSection();

    /**
     * Get all definitions for this template as array
     *
     * @return array
     */
    public function getSections(): array
    {
        $unitProperties = $this->getUnitSection()->getProperties();
        $typeSpecificProperties = $this->getTypeSpecificSection()->getProperties();
        $installProperties = $this->getInstallSection()->getProperties();

        $definitions = [];

        if (!empty($unitProperties)) {
            $definitions['Unit'] = $this->convertProperties($unitProperties);
        }

        if (!empty($typeSpecificProperties)) {
            $definitions[ucfirst($this->getUnitSuffix())] = $this->convertProperties($typeSpecificProperties);
        }

        if (!empty($installProperties)) {
            $definitions['Install'] = $this->convertProperties($installProperties);
        }

        return $definitions;
    }

    /**
     * Convert properties to proper definitions in templates
     *
     * @param $properties
     *
     * @return array
     */
    protected function convertProperties($properties)
    {
        return array_map([DefinitionConverter::class, 'convert'], $properties);
    }

    /**
     * @return string
     */
    public function getUnitName(): string
    {
        return $this->unitName;
    }

    /**
     * @return string
     */
    public function getUnitSuffix(): string
    {
        return $this->unitSuffix;
    }

    /**
     * @return UnitSection
     */
    public function getUnitSection(): UnitSection
    {
        return $this->unitSection;
    }

    /**
     * @return InstallSection
     */
    public function getInstallSection(): InstallSection
    {
        return $this->installSection;
    }
}
