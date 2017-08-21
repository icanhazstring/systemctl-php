<?php

namespace SystemCtl\Test\Unit\Template;

use PHPUnit\Framework\TestCase;
use SystemCtl\Template\Section\InstallSection;
use SystemCtl\Template\Section\ServiceSection;
use SystemCtl\Template\Section\UnitSection;
use SystemCtl\Template\ServiceUnitTemplate;
use SystemCtl\Unit\Service;

/**
 * ServiceUnitTemplateTest
 *
 * @package SystemCtl\Test\Unit\Template
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
        $this->assertEquals('TestService', $unitTemplate->getName());
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

        $this->assertEmpty($unitTemplate->getDefinitions());
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

        $this->assertCount(1, $unitTemplate->getDefinitions());
        $this->assertArrayHasKey('Unit', $unitTemplate->getDefinitions());

        $unitTemplate
            ->getServiceSection()
            ->setType(ServiceSection::TYPE_FORKING);

        $this->assertCount(2, $unitTemplate->getDefinitions());
        $this->assertArrayHasKey('Service', $unitTemplate->getDefinitions());

        $unitTemplate
            ->getInstallSection()
            ->setWantedBy(['multi-user.target']);

        $this->assertCount(3, $unitTemplate->getDefinitions());
        $this->assertArrayHasKey('Install', $unitTemplate->getDefinitions());

        $this->assertNotEmpty($unitTemplate->getUnitSection()->getProperties());
        $this->assertNotEmpty($unitTemplate->getInstallSection()->getProperties());
        $this->assertNotEmpty($unitTemplate->getServiceSection()->getProperties());
    }
}
