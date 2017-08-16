<?php

namespace SystemCtl\Test\Unit\Template\Section;

use PHPUnit\Framework\TestCase;
use SystemCtl\Exception\PropertyNotSupportedException;
use SystemCtl\Template\Section\ServiceSection;

class ServiceSectionTest extends TestCase
{
    public function testCreation()
    {
        $serviceSection = new ServiceSection;
        $this->assertInstanceOf(ServiceSection::class, $serviceSection);
    }

    public function testValidProperties()
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
        $this->assertTrue($serviceSection->shouldRemainsAfterExit());
        $this->assertEquals('pid', $serviceSection->getPIDFile());
    }

    public function testInvalidPropertyShouldRaiseException()
    {
        $serviceSection = new ServiceSection;

        $this->expectException(PropertyNotSupportedException::class);
        $serviceSection->setFubar('should fail');
    }

    public function testNonSetPropertyShouldReturnNull()
    {
        $serviceSection = new ServiceSection;
        $this->assertNull($serviceSection->getEnvironment());
    }

    public function testGetPropetiesShouldReturnOnlySetProperties()
    {
        $serviceSection = new ServiceSection;

        $serviceSection->setType(ServiceSection::TYPE_SIMPLE);
        $serviceSection->setExecStart('/test/command/start');

        $this->assertCount(2, $serviceSection->getProperties());
        $this->assertArrayHasKey('Type', $serviceSection->getProperties());
        $this->assertArrayHasKey('ExecStart', $serviceSection->getProperties());
    }
}
