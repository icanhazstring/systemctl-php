<?php
declare(strict_types=1);

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

        self::assertInstanceOf(ServiceUnitTemplate::class, $unitTemplate);
        self::assertEquals(Service::UNIT, $unitTemplate->getUnitSuffix());
        self::assertEquals('TestService', $unitTemplate->getUnitName());
    }

    /**
     * @test
     */
    public function itShouldReturnEmptySectionsAfterInstantiation()
    {
        $unitTemplate = new ServiceUnitTemplate('TestService');

        self::assertInstanceOf(UnitSection::class, $unitTemplate->getUnitSection());
        self::assertInstanceOf(InstallSection::class, $unitTemplate->getInstallSection());
        self::assertInstanceOf(ServiceSection::class, $unitTemplate->getServiceSection());

        self::assertEmpty($unitTemplate->getSections());
        self::assertEmpty($unitTemplate->getUnitSection()->getProperties());
        self::assertEmpty($unitTemplate->getInstallSection()->getProperties());
        self::assertEmpty($unitTemplate->getServiceSection()->getProperties());
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

        self::assertCount(1, $unitTemplate->getSections());
        self::assertArrayHasKey('Unit', $unitTemplate->getSections());

        $unitTemplate
            ->getServiceSection()
            ->setType(ServiceSection::TYPE_FORKING);

        self::assertCount(2, $unitTemplate->getSections());
        self::assertArrayHasKey('Service', $unitTemplate->getSections());

        $unitTemplate
            ->getInstallSection()
            ->setWantedBy(['multi-user.target']);

        self::assertCount(3, $unitTemplate->getSections());
        self::assertArrayHasKey('Install', $unitTemplate->getSections());

        self::assertNotEmpty($unitTemplate->getUnitSection()->getProperties());
        self::assertNotEmpty($unitTemplate->getInstallSection()->getProperties());
        self::assertNotEmpty($unitTemplate->getServiceSection()->getProperties());
    }
}
