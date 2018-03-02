<?php
declare(strict_types=1);

namespace SystemCtl\Tests\Unit\Template\Section;

use PHPUnit\Framework\TestCase;
use SystemCtl\Template\Section\TimerSection;

/**
 * TimerSectionTest
 *
 * @package SystemCtl\Tests\Unit\Template\Section
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
        self::assertInstanceOf(TimerSection::class, $timerSection);
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

        self::assertEquals('Wed..Sat *-*-* 1:00', $timerSection->getOnCalendar());
        self::assertEquals('testUnit', $timerSection->getUnit());
        self::assertFalse($timerSection->getRemainAfterElapse());
    }

    /**
     * @test
     */
    public function itShouldReturnOnlyThosePropertiesPreviouslySet()
    {
        $timerSection = new TimerSection;

        $timerSection->setOnCalendar('Wed..Sat *-*-* 1:00');
        $timerSection->setUnit('testUnit');

        self::assertCount(2, $timerSection->getProperties());
        self::assertArrayHasKey('OnCalendar', $timerSection->getProperties());
        self::assertArrayHasKey('Unit', $timerSection->getProperties());
    }
}
