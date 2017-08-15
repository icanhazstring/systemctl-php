<?php

namespace SystemCtl\Template\Section;

/**
 * TimerSection
 *
 * @method TimerSection setOnCalendar(string $value)    Defines timers with calendar event expressions (i.e. wallclock)
 * @method TimerSection setUnit(string $unit)           The unit to activate when this timer elapses
 * @method TimerSection setRemainAfterElapse(bool $rae) Elapsed timer will stay loaded and its state remains queriable
 *
 * @method string getOnCalendar()
 * @method string getUnit()
 * @method bool shouldRemainAfterElapse()
 *
 * @package SystemCtl\Template\Section
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class TimerSection extends AbstractSection
{
    protected const PROPERTIES = [
        'OnCalendar',
        'Unit',
        'RemainAfterElapse'
    ];
}
