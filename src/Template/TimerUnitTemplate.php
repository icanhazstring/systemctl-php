<?php

namespace SystemCtl\Template;

use SystemCtl\Template\Section\TimerSection;
use SystemCtl\Unit\Timer;

class TimerUnitTemplate extends AbstractUnitTemplate
{
    /** @var TimerSection */
    protected $timerSection;

    /**
     * ServiceUnitTemplate constructor.
     *
     * @param string $unitName
     */
    public function __construct($unitName)
    {
        parent::__construct($unitName, Timer::UNIT);

        $this->timerSection = new TimerSection;
    }

    /**
     * @return TimerSection
     */
    public function getTimerSection(): TimerSection
    {
        return $this->timerSection;
    }

    /**
     * @inheritDoc
     */
    public function getDefinitions(): array
    {
        $defintiions = parent::getDefinitions();
        $timerProperties = $this->getTimerSection()->getProperties();

        if (!empty($timerProperties)) {
            $defintiions[ucfirst(Timer::UNIT)] = $this->convertProperties($timerProperties);
        }

        return $defintiions;
    }
}
