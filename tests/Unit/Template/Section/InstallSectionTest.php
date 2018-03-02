<?php
declare(strict_types=1);

namespace SystemCtl\Tests\Unit\Template\Section;

use PHPUnit\Framework\TestCase;
use SystemCtl\Exception\PropertyNotSupportedException;
use SystemCtl\Template\Section\InstallSection;

/**
 * InstallSectionTest
 *
 * @package SystemCtl\Tests\Unit\Template\Section
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
        self::assertInstanceOf(InstallSection::class, $installSection);
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

        self::assertEquals(['alias'], $installSection->getAlias());
        self::assertEquals(['required'], $installSection->getRequiredBy());
        self::assertEquals(['wanted'], $installSection->getWantedBy());
        self::assertEquals(['also'], $installSection->getAlso());
    }

    /**
     * @test
     */
    public function itShouldRaiseAnExceptionOnInvalidProperty()
    {
        $installSection = new InstallSection;

        $this->expectException(PropertyNotSupportedException::class);
        $installSection->setFubar(['should fail']);
    }

    /**
     * @test
     */
    public function itShouldReturnNullIfAPropertyIsNotSet()
    {
        $installSection = new InstallSection;
        self::assertNull($installSection->getWantedBy());
    }

    /**
     * @test
     */
    public function itShouldReturnOnlyThosePropertiesPreviouslySet()
    {
        $installSection = new InstallSection;

        $installSection->setWantedBy(['wanted']);
        $installSection->setRequiredBy(['required']);

        self::assertCount(2, $installSection->getProperties());
        self::assertArrayHasKey('WantedBy', $installSection->getProperties());
        self::assertArrayHasKey('RequiredBy', $installSection->getProperties());
    }
}
