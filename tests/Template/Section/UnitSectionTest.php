<?php

namespace SystemCtl\Test\Template\Section;

use PHPUnit\Framework\TestCase;
use SystemCtl\Exception\PropertyNotSupportedException;
use SystemCtl\Template\Section\UnitSection;

class UnitSectionTest extends TestCase
{
    public function testCreation()
    {
        $unitSection = new UnitSection;
        $this->assertInstanceOf(UnitSection::class, $unitSection);
    }

    public function testValidProperties()
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

    public function testInvalidPropertyShouldRaiseException()
    {
        $unitSection = new UnitSection;

        $this->expectException(PropertyNotSupportedException::class);
        $unitSection->setFubar('should fail');
    }

    public function testNonSetPropertyShouldReturnNull()
    {
        $unitSection = new UnitSection;
        $this->assertNull($unitSection->getDescription());
    }

    public function testGetPropetiesShouldReturnOnlySetProperties()
    {
        $unitSection = new UnitSection;

        $unitSection->setDescription('TestDescription');
        $unitSection->setDocumentation('TestDocumentation');

        $this->assertCount(2, $unitSection->getProperties());
        $this->assertArrayHasKey('Description', $unitSection->getProperties());
        $this->assertArrayHasKey('Documentation', $unitSection->getProperties());
    }
}
