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
        $this->assertInstanceOf(UnitSection::class, $unitSection);
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

        $this->assertEquals('TestDescription', $unitSection->getDescription());
        $this->assertEquals('TestDocumentation', $unitSection->getDocumentation());
        $this->assertEquals(['a', 'b'], $unitSection->getAfter());
        $this->assertEquals(['c', 'd'], $unitSection->getRequires());
        $this->assertEquals(['e', 'f'], $unitSection->getWants());
        $this->assertEquals(['g', 'h'], $unitSection->getConflicts());
    }

    /**
     * @test
     */
    public function itShouldReturnOnlyThosePropertiesPreviouslySet()
    {
        $unitSection = new UnitSection;

        $unitSection->setDescription('TestDescription');
        $unitSection->setDocumentation('TestDocumentation');

        $this->assertCount(2, $unitSection->getProperties());
        $this->assertArrayHasKey('Description', $unitSection->getProperties());
        $this->assertArrayHasKey('Documentation', $unitSection->getProperties());
    }
}
