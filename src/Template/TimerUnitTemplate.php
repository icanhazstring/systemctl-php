<?php
declare(strict_types=1);

namespace SystemCtl\Template;

use SystemCtl\Template\Section\AbstractSection;
use SystemCtl\Template\Section\TimerSection;
use SystemCtl\Unit\Timer;

/**
 * TimerUnitTemplate
 *
 * @package SystemCtl\Template
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class TimerUnitTemplate extends AbstractUnitTemplate implements UnitTemplateInterface
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
    protected function getTypeSpecificSection(): AbstractSection
    {
        return $this->timerSection;
    }
}
