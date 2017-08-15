<?php

namespace SystemCtl\Test\Template\Section;

use PHPUnit\Framework\TestCase;
use SystemCtl\Exception\PropertyNotSupportedException;
use SystemCtl\Template\Section\InstallSection;

class InstallSectionTest extends TestCase
{
    public function testCreation()
    {
        $installSection = new InstallSection;
        $this->assertInstanceOf(InstallSection::class, $installSection);
    }

    public function testValidProperties()
    {
        $installSection = (new InstallSection)
            ->setAlias(['alias'])
            ->setRequiredBy(['required'])
            ->setWantedBy(['wanted'])
            ->setAlso(['also']);

        $this->assertEquals(['alias'], $installSection->getAlias());
        $this->assertEquals(['required'], $installSection->getRequiredBy());
        $this->assertEquals(['wanted'], $installSection->getWantedBy());
        $this->assertEquals(['also'], $installSection->getAlso());
    }

    public function testInvalidPropertyShouldRaiseException()
    {
        $installSection = new InstallSection;

        $this->expectException(PropertyNotSupportedException::class);
        $installSection->setFubar('should fail');
    }

    public function testNonSetPropertyShouldReturnNull()
    {
        $installSection = new InstallSection;
        $this->assertNull($installSection->getWantedBy());
    }

    public function testGetPropetiesShouldReturnOnlySetProperties()
    {
        $installSection = new InstallSection;

        $installSection->setWantedBy(['wanted']);
        $installSection->setRequiredBy(['required']);

        $this->assertCount(2, $installSection->getProperties());
        $this->assertArrayHasKey('WantedBy', $installSection->getProperties());
        $this->assertArrayHasKey('RequiredBy', $installSection->getProperties());
    }
}
