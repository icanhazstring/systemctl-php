<?php

namespace SystemCtl\Test\Unit\Template\Section;

use PHPUnit\Framework\TestCase;
use SystemCtl\Exception\PropertyNotSupportedException;
use SystemCtl\Template\Section\InstallSection;

/**
 * InstallSectionTest
 *
 * @package SystemCtl\Test\Unit\Template\Section
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class InstallSectionTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldCreateProperInstance()
    {
        $installSection = new InstallSection;
        $this->assertInstanceOf(InstallSection::class, $installSection);
    }

    /**
     * @test
     */
    public function itShouldSetPropertiesAndReturnThem()
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

    /**
     * @test
     */
    public function itShouldRaiseAnExceptionOnInvalidProperty()
    {
        $installSection = new InstallSection;

        $this->expectException(PropertyNotSupportedException::class);
        $installSection->setFubar('should fail');
    }

    /**
     * @test
     */
    public function itShouldReturnNullIfAPropertyIsNotSet()
    {
        $installSection = new InstallSection;
        $this->assertNull($installSection->getWantedBy());
    }

    /**
     * @test
     */
    public function itShouldReturnOnlyThosePropertiesPreviouslySet()
    {
        $installSection = new InstallSection;

        $installSection->setWantedBy(['wanted']);
        $installSection->setRequiredBy(['required']);

        $this->assertCount(2, $installSection->getProperties());
        $this->assertArrayHasKey('WantedBy', $installSection->getProperties());
        $this->assertArrayHasKey('RequiredBy', $installSection->getProperties());
    }
}
