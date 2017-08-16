<?php

namespace SystemCtl\Test\Unit\Template\Section;

use PHPUnit\Framework\TestCase;
use SystemCtl\Exception\PropertyNotSupportedException;
use SystemCtl\Template\Section\TimerSection;

class TimerSectionTest extends TestCase
{
    public function testCreation()
    {
        $timerSection = new TimerSection;
        $this->assertInstanceOf(TimerSection::class, $timerSection);
    }

    public function testValidProperties()
    {
        $timerSection = (new TimerSection)
            ->setOnCalendar('Wed..Sat *-*-* 1:00')
            ->setUnit('testUnit')
            ->setRemainAfterElapse(false);

        $this->assertEquals('Wed..Sat *-*-* 1:00', $timerSection->getOnCalendar());
        $this->assertEquals('testUnit', $timerSection->getUnit());
        $this->assertFalse($timerSection->shouldRemainAfterElapse());
    }

    public function testInvalidPropertyShouldRaiseException()
    {
        $timerSection = new TimerSection;

        $this->expectException(PropertyNotSupportedException::class);
        $timerSection->setFubar('should fail');
    }

    public function testNonSetPropertyShouldReturnNull()
    {
        $timerSection = new TimerSection;
        $this->assertNull($timerSection->getOnCalendar());
    }

    public function testGetPropetiesShouldReturnOnlySetProperties()
    {
        $timerSection = new TimerSection;

        $timerSection->setOnCalendar('Wed..Sat *-*-* 1:00');
        $timerSection->setUnit('testUnit');

        $this->assertCount(2, $timerSection->getProperties());
        $this->assertArrayHasKey('OnCalendar', $timerSection->getProperties());
        $this->assertArrayHasKey('Unit', $timerSection->getProperties());
    }
}
