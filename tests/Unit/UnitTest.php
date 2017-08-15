<?php

namespace SystemCtl\Test\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use SystemCtl\SystemCtl;
use SystemCtl\Unit\Service;

class UnitTest extends TestCase
{
    protected function getSystemCtlMock(bool $processState = true, string $processOutput = ''): SystemCtl
    {
        $process = $this->getMockBuilder(Process::class)
            ->disableOriginalConstructor()
            ->setMethods(['isSuccessful', 'getOutput'])
            ->getMock();

        $processBuilder = $this->getMockBuilder(ProcessBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(['getProcess'])
            ->getMock();

        $processBuilder->method('getProcess')->willReturn($process);

        /** @var \PHPUnit_Framework_MockObject_MockObject|SystemCtl $systemctl */
        $systemctl = $this->getMockBuilder(SystemCtl::class)
            ->setMethods(['getProcessBuilder'])
            ->getMock();

        $systemctl->method('getProcessBuilder')->willReturn($processBuilder);
        $process->method('isSuccessful')->willReturn($processState);
        $process->method('getOutput')->willReturn($processOutput);

        return $systemctl;
    }

    public function testMultiInstanceUnit()
    {
        $process = $this->getMockBuilder(Process::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var \PHPUnit_Framework_MockObject_MockObject|ProcessBuilder $processBuilder */
        $processBuilder = $this->getMockBuilder(ProcessBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(['getProcess'])
            ->getMock();

        $processBuilder->method('getProcess')->willReturn($process);

        $unit = new Service('service@1', $processBuilder);
        $this->assertEquals('service@1', $unit->getName());
        $this->assertTrue($unit->isMultiInstance());
        $this->assertEquals('1', $unit->getInstanceName());
    }

    public function testProcessShouldReturnExitCode()
    {
        $systemctl = $this->getSystemCtlMock(false);
        $service = $systemctl->getService('AwesomeService');

        $this->assertFalse($service->start());
        $this->assertFalse($service->stop());
        $this->assertFalse($service->disable());
        $this->assertFalse($service->enable());
        $this->assertFalse($service->restart());
        $this->assertFalse($service->reload());
    }

    public function testIsEnabled()
    {
        $systemctl = $this->getSystemCtlMock(true, 'enabled');
        $service = $systemctl->getService('TestService');

        $this->assertTrue($service->isEnabled());

        $systemctl = $this->getSystemCtlMock(true, 'disabled');
        $service = $systemctl->getService('TestService');

        $this->assertFalse($service->isEnabled());
    }

    public function testIsActive()
    {
        $systemctl = $this->getSystemCtlMock(true, 'active');
        $service = $systemctl->getService('TestService');

        $this->assertTrue($service->isActive());

        $systemctl = $this->getSystemCtlMock(true, 'inactive');
        $service = $systemctl->getService('TestService');

        $this->assertFalse($service->isActive());
    }
}
