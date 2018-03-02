<?php
declare(strict_types=1);

namespace SystemCtl\Tests\Unit\Template;

use PHPUnit\Framework\TestCase;
use SystemCtl\Template\Section\InstallSection;
use SystemCtl\Template\Section\TimerSection;
use SystemCtl\Template\Section\UnitSection;
use SystemCtl\Template\TimerUnitTemplate;
use SystemCtl\Unit\Timer;

/**
 * TimerUnitTemplateTest
 *
 * @package SystemCtl\Tests\Unit\Template
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class TimerUnitTemplateTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldCreateASimpleInstance()
    {
        $unitTemplate = new TimerUnitTemplate('TestTimer');

        self::assertInstanceOf(TimerUnitTemplate::class, $unitTemplate);
        self::assertEquals(Timer::UNIT, $unitTemplate->getUnitSuffix());
        self::assertEquals('TestTimer', $unitTemplate->getUnitName());
    }

    /**
     * @test
     */
    public function itShouldReturnEmptySectionsAfterInstantiation()
    {
        $unitTemplate = new TimerUnitTemplate('TestTimer');

        self::assertInstanceOf(UnitSection::class, $unitTemplate->getUnitSection());
        self::assertInstanceOf(InstallSection::class, $unitTemplate->getInstallSection());
        self::assertInstanceOf(TimerSection::class, $unitTemplate->getTimerSection());

        self::assertEmpty($unitTemplate->getSections());
        self::assertEmpty($unitTemplate->getUnitSection()->getProperties());
        self::assertEmpty($unitTemplate->getInstallSection()->getProperties());
        self::assertEmpty($unitTemplate->getTimerSection()->getProperties());
    }

    /**
     * @test
     */
    public function itShouldReturnProperSectionValuesIfSet()
    {
        $unitTemplate = new TimerUnitTemplate('TestTimer');

        $unitTemplate
            ->getUnitSection()
            ->setDescription('TestDescription');

        self::assertCount(1, $unitTemplate->getSections());
        self::assertArrayHasKey('Unit', $unitTemplate->getSections());

        $unitTemplate
            ->getTimerSection()
            ->setUnit('superservice');

        self::assertCount(2, $unitTemplate->getSections());
        self::assertArrayHasKey('Timer', $unitTemplate->getSections());

        $unitTemplate
            ->getInstallSection()
            ->setWantedBy(['multi-user.target']);

        self::assertCount(3, $unitTemplate->getSections());
        self::assertArrayHasKey('Install', $unitTemplate->getSections());

        self::assertNotEmpty($unitTemplate->getUnitSection()->getProperties());
        self::assertNotEmpty($unitTemplate->getInstallSection()->getProperties());
        self::assertNotEmpty($unitTemplate->getTimerSection()->getProperties());
    }
}
