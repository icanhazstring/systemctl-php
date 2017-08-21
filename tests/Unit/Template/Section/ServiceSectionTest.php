<?php

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
        $this->assertInstanceOf(ServiceSection::class, $serviceSection);
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

        $this->assertEquals(ServiceSection::TYPE_FORKING, $serviceSection->getType());
        $this->assertEquals(['env' => 'test'], $serviceSection->getEnvironment());
        $this->assertEquals('/etc/environment', $serviceSection->getEnvironmentFile());
        $this->assertEquals('/test/command/start', $serviceSection->getExecStart());
        $this->assertEquals('/test/command/stop', $serviceSection->getExecStop());
        $this->assertEquals('/test/command/reload', $serviceSection->getExecReload());
        $this->assertEquals('always', $serviceSection->getRestart());
        $this->assertTrue($serviceSection->getRemainsAfterExit());
        $this->assertEquals('pid', $serviceSection->getPIDFile());
    }

    /**
     * @test
     */
    public function itShouldReturnOnlyThosePropertiesPreviouslySet()
    {
        $serviceSection = new ServiceSection;

        $serviceSection->setType(ServiceSection::TYPE_SIMPLE);
        $serviceSection->setExecStart('/test/command/start');

        $this->assertCount(2, $serviceSection->getProperties());
        $this->assertArrayHasKey('Type', $serviceSection->getProperties());
        $this->assertArrayHasKey('ExecStart', $serviceSection->getProperties());
    }
}
