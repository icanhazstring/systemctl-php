<?php

namespace SystemCtl\Test\Unit\Template\Section;

use PHPUnit\Framework\TestCase;
use SystemCtl\Template\Section\TimerSection;

/**
 * TimerSectionTest
 *
 * @package SystemCtl\Test\Unit\Template\Section
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class TimerSectionTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldCreateProperInstance()
    {
        $timerSection = new TimerSection;
        $this->assertInstanceOf(TimerSection::class, $timerSection);
    }

    /**
     * @test
     */
    public function itShouldSetPropertiesAndReturnThem()
    {
        $timerSection = (new TimerSection)
            ->setOnCalendar('Wed..Sat *-*-* 1:00')
            ->setUnit('testUnit')
            ->setRemainAfterElapse(false);

        $this->assertEquals('Wed..Sat *-*-* 1:00', $timerSection->getOnCalendar());
        $this->assertEquals('testUnit', $timerSection->getUnit());
        $this->assertFalse($timerSection->shouldRemainAfterElapse());
    }

    /**
     * @test
     */
    public function itShouldReturnOnlyThosePropertiesPreviouslySet()
    {
        $timerSection = new TimerSection;

        $timerSection->setOnCalendar('Wed..Sat *-*-* 1:00');
        $timerSection->setUnit('testUnit');

        $this->assertCount(2, $timerSection->getProperties());
        $this->assertArrayHasKey('OnCalendar', $timerSection->getProperties());
        $this->assertArrayHasKey('Unit', $timerSection->getProperties());
    }
}
