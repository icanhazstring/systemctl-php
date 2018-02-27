<?php
declare(strict_types=1);

namespace SystemCtl\Template;

use SystemCtl\Template\Section\ServiceSection;
use SystemCtl\Unit\Service;

/**
 * ServiceUnitTemplate
 *
 * @package SystemCtl\Template
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class ServiceUnitTemplate extends AbstractUnitTemplate
{
    /** @var ServiceSection */
    protected $serviceSection;

    /**
     * ServiceUnitTemplate constructor.
     *
     * @param string $unitName
     */
    public function __construct($unitName)
    {
        parent::__construct($unitName, Service::UNIT);

        $this->serviceSection = new ServiceSection;
    }

    /**
     * @return ServiceSection
     */
    public function getServiceSection(): ServiceSection
    {
        return $this->serviceSection;
    }

    /**
     * @inheritDoc
     */
    public function getTypeSpecificSection()
    {
        return $this->serviceSection;
    }
}
