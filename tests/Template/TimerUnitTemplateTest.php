<?php

namespace SystemCtl\Test\Template;

use PHPUnit\Framework\TestCase;
use SystemCtl\Template\Section\InstallSection;
use SystemCtl\Template\Section\TimerSection;
use SystemCtl\Template\Section\UnitSection;
use SystemCtl\Template\TimerUnitTemplate;
use SystemCtl\Unit\Timer;

class TimerUnitTemplateTest extends TestCase
{
    public function testSimpleUnitCreation()
    {
        $unitTemplate = new TimerUnitTemplate('TestTimer');

        $this->assertInstanceOf(TimerUnitTemplate::class, $unitTemplate);
        $this->assertEquals(Timer::UNIT, $unitTemplate->getUnitSuffix());
        $this->assertEquals('TestTimer', $unitTemplate->getName());
    }

    public function testEmptySectionAfterCreation()
    {
        $unitTemplate = new TimerUnitTemplate('TestTimer');

        $this->assertInstanceOf(UnitSection::class, $unitTemplate->getUnitSection());
        $this->assertInstanceOf(InstallSection::class, $unitTemplate->getInstallSection());
        $this->assertInstanceOf(TimerSection::class, $unitTemplate->getTimerSection());

        $this->assertEmpty($unitTemplate->getDefinitions());
        $this->assertEmpty($unitTemplate->getUnitSection()->getProperties());
        $this->assertEmpty($unitTemplate->getInstallSection()->getProperties());
        $this->assertEmpty($unitTemplate->getTimerSection()->getProperties());
    }

    public function testUnitCreationWithSections()
    {
        $unitTemplate = new TimerUnitTemplate('TestTimer');

        $unitTemplate
            ->getUnitSection()
            ->setDescription('TestDescription');

        $this->assertCount(1, $unitTemplate->getDefinitions());
        $this->assertArrayHasKey('Unit', $unitTemplate->getDefinitions());

        $unitTemplate
            ->getTimerSection()
            ->setUnit('superservice');

        $this->assertCount(2, $unitTemplate->getDefinitions());
        $this->assertArrayHasKey('Timer', $unitTemplate->getDefinitions());

        $unitTemplate
            ->getInstallSection()
            ->setWantedBy(['multi-user.target']);

        $this->assertCount(3, $unitTemplate->getDefinitions());
        $this->assertArrayHasKey('Install', $unitTemplate->getDefinitions());

        $this->assertNotEmpty($unitTemplate->getUnitSection()->getProperties());
        $this->assertNotEmpty($unitTemplate->getInstallSection()->getProperties());
        $this->assertNotEmpty($unitTemplate->getTimerSection()->getProperties());
    }
}
