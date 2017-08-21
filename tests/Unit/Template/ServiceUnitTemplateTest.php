<?php

namespace SystemCtl\Tests\Unit\Template;

use PHPUnit\Framework\TestCase;
use SystemCtl\Template\Section\InstallSection;
use SystemCtl\Template\Section\ServiceSection;
use SystemCtl\Template\Section\UnitSection;
use SystemCtl\Template\ServiceUnitTemplate;
use SystemCtl\Unit\Service;

/**
 * ServiceUnitTemplateTest
 *
 * @package SystemCtl\Tests\Unit\Template
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class ServiceUnitTemplateTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldCreateASimpleInstance()
    {
        $unitTemplate = new ServiceUnitTemplate('TestService');

        $this->assertInstanceOf(ServiceUnitTemplate::class, $unitTemplate);
        $this->assertEquals(Service::UNIT, $unitTemplate->getUnitSuffix());
        $this->assertEquals('TestService', $unitTemplate->getUnitName());
    }

    /**
     * @test
     */
    public function itShouldReturnEmptySectionsAfterInstantiation()
    {
        $unitTemplate = new ServiceUnitTemplate('TestService');

        $this->assertInstanceOf(UnitSection::class, $unitTemplate->getUnitSection());
        $this->assertInstanceOf(InstallSection::class, $unitTemplate->getInstallSection());
        $this->assertInstanceOf(ServiceSection::class, $unitTemplate->getServiceSection());

        $this->assertEmpty($unitTemplate->getSections());
        $this->assertEmpty($unitTemplate->getUnitSection()->getProperties());
        $this->assertEmpty($unitTemplate->getInstallSection()->getProperties());
        $this->assertEmpty($unitTemplate->getServiceSection()->getProperties());
    }

    /**
     * @test
     */
    public function itShouldReturnProperSectionValuesIfSet()
    {
        $unitTemplate = new ServiceUnitTemplate('TestService');

        $unitTemplate
            ->getUnitSection()
            ->setDescription('TestDescription');

        $this->assertCount(1, $unitTemplate->getSections());
        $this->assertArrayHasKey('Unit', $unitTemplate->getSections());

        $unitTemplate
            ->getServiceSection()
            ->setType(ServiceSection::TYPE_FORKING);

        $this->assertCount(2, $unitTemplate->getSections());
        $this->assertArrayHasKey('Service', $unitTemplate->getSections());

        $unitTemplate
            ->getInstallSection()
            ->setWantedBy(['multi-user.target']);

        $this->assertCount(3, $unitTemplate->getSections());
        $this->assertArrayHasKey('Install', $unitTemplate->getSections());

        $this->assertNotEmpty($unitTemplate->getUnitSection()->getProperties());
        $this->assertNotEmpty($unitTemplate->getInstallSection()->getProperties());
        $this->assertNotEmpty($unitTemplate->getServiceSection()->getProperties());
    }
}
