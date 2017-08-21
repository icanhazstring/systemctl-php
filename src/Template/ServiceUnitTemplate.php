<?php

namespace SystemCtl\Template;

use SystemCtl\Template\Section\ServiceSection;
use SystemCtl\Unit\Service;

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
