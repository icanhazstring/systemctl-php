<?php

namespace SystemCtl\Test\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use SystemCtl\Exception\CommandFailedException;
use SystemCtl\SystemCtl;
use SystemCtl\Unit\Service;

class UnitTest extends TestCase
{
    protected function getSystemCtlMock(bool $processState = true): SystemCtl
    {
        $process = $this->getMockBuilder(Process::class)
            ->disableOriginalConstructor()
            ->setMethods(['isSuccessful'])
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

        return $systemctl;
    }

    public function testServiceCommandsIfProcessIsSuccessfulShouldReturnTrue()
    {
        $systemctl = $this->getSystemCtlMock();
        $service = $systemctl->getService('AwesomeService');

        $this->assertTrue($service->start());
        $this->assertTrue($service->stop());
        $this->assertTrue($service->enable());
        $this->assertTrue($service->disable());
        $this->assertTrue($service->reload());
        $this->assertTrue($service->restart());
    }

    public function testServiceCommandsIfProcessIsUnsuccessFulShouldRaiseException()
    {
        $systemctl = $this->getSystemCtlMock(false);

        $service = $systemctl->getService('AwesomeService');

        $this->expectException(CommandFailedException::class);
        $this->expectExceptionMessage('Failed to start service AwesomeService');

        $service->start();
    }

    public function testTimerCommandsIfProcessIsSuccessfulShouldReturnTrue()
    {
        $systemctl = $this->getSystemCtlMock();

        $timer = $systemctl->getTimer('AwesomeTimer');

        $this->assertTrue($timer->start());
        $this->assertTrue($timer->stop());
        $this->assertTrue($timer->enable());
        $this->assertTrue($timer->disable());
        $this->assertTrue($timer->reload());
        $this->assertTrue($timer->restart());
    }

    public function testTimerCommandsIfProcessIsUnsuccessFulShouldRaiseException()
    {
        $systemctl = $this->getSystemCtlMock(false);
        $timer = $systemctl->getTimer('AwesomeTimer');

        $this->expectException(CommandFailedException::class);
        $timer->start();
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

        $this->assertFalse($service->start(false));
        $this->assertFalse($service->stop(false));
        $this->assertFalse($service->disable(false));
        $this->assertFalse($service->enable(false));
        $this->assertFalse($service->restart(false));
        $this->assertFalse($service->reload(false));
    }
}
