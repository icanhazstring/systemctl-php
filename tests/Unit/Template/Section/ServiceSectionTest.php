<?php
declare(strict_types=1);

namespace SystemCtl\Tests\Unit\Template\Section;

use PHPUnit\Framework\TestCase;
use SystemCtl\Template\Section\ServiceSection;

/**
 * ServiceSectionTest
 *
 * @package SystemCtl\Tests\Unit\Template\Section
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class ServiceSectionTest extends TestCase
{

    /**
     * @test
     */
    public function itShouldCreateProperInstance()
    {
        $serviceSection = new ServiceSection;
        self::assertInstanceOf(ServiceSection::class, $serviceSection);
    }

    /**
     * @test
     */
    public function itShouldSetPropertiesAndReturnThem()
    {
        $serviceSection = (new ServiceSection)
            ->setType(ServiceSection::TYPE_FORKING)
            ->setEnvironment(['env' => 'test'])
            ->setEnvironmentFile('/etc/environment')
            ->setExecStart('/test/command/start')
            ->setExecStop('/test/command/stop')
            ->setExecReload('/test/command/reload')
            ->setRestart('always')
            ->setRemainsAfterExit(true)
            ->setPIDFile('pid');

        self::assertEquals(ServiceSection::TYPE_FORKING, $serviceSection->getType());
        self::assertEquals(['env' => 'test'], $serviceSection->getEnvironment());
        self::assertEquals('/etc/environment', $serviceSection->getEnvironmentFile());
        self::assertEquals('/test/command/start', $serviceSection->getExecStart());
        self::assertEquals('/test/command/stop', $serviceSection->getExecStop());
        self::assertEquals('/test/command/reload', $serviceSection->getExecReload());
        self::assertEquals('always', $serviceSection->getRestart());
        self::assertTrue($serviceSection->getRemainsAfterExit());
        self::assertEquals('pid', $serviceSection->getPIDFile());
    }

    /**
     * @test
     */
    public function itShouldReturnOnlyThosePropertiesPreviouslySet()
    {
        $serviceSection = new ServiceSection;

        $serviceSection->setType(ServiceSection::TYPE_SIMPLE);
        $serviceSection->setExecStart('/test/command/start');

        self::assertCount(2, $serviceSection->getProperties());
        self::assertArrayHasKey('Type', $serviceSection->getProperties());
        self::assertArrayHasKey('ExecStart', $serviceSection->getProperties());
    }
}
