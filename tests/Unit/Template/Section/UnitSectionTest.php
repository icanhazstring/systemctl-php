<?php
declare(strict_types=1);

namespace SystemCtl\Tests\Unit\Template\Section;

use PHPUnit\Framework\TestCase;
use SystemCtl\Template\Section\UnitSection;

/**
 * UnitSectionTest
 *
 * @package SystemCtl\Tests\Unit\Template\Section
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class UnitSectionTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldCreateProperInstance()
    {
        $unitSection = new UnitSection;
        self::assertInstanceOf(UnitSection::class, $unitSection);
    }

    /**
     * @test
     */
    public function itShouldSetPropertiesAndReturnThem()
    {
        $unitSection = (new UnitSection)
            ->setDescription('TestDescription')
            ->setDocumentation('TestDocumentation')
            ->setAfter(['a', 'b'])
            ->setRequires(['c', 'd'])
            ->setWants(['e', 'f'])
            ->setConflicts(['g', 'h']);

        self::assertEquals('TestDescription', $unitSection->getDescription());
        self::assertEquals('TestDocumentation', $unitSection->getDocumentation());
        self::assertEquals(['a', 'b'], $unitSection->getAfter());
        self::assertEquals(['c', 'd'], $unitSection->getRequires());
        self::assertEquals(['e', 'f'], $unitSection->getWants());
        self::assertEquals(['g', 'h'], $unitSection->getConflicts());
    }

    /**
     * @test
     */
    public function itShouldReturnOnlyThosePropertiesPreviouslySet()
    {
        $unitSection = new UnitSection;

        $unitSection->setDescription('TestDescription');
        $unitSection->setDocumentation('TestDocumentation');

        self::assertCount(2, $unitSection->getProperties());
        self::assertArrayHasKey('Description', $unitSection->getProperties());
        self::assertArrayHasKey('Documentation', $unitSection->getProperties());
    }
}
