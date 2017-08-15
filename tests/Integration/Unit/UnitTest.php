<?php

namespace SystemCtl\Test\Integration\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use SystemCtl\Exception\CommandFailedException;
use SystemCtl\SystemCtl;
use SystemCtl\Unit\Service;

class UnitTest extends TestCase
{
    public function testServiceCommandsIfProcessIsSuccessfulShouldReturnTrue()
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

        $service = $systemctl->getService('AwesomeService');

        $process->method('isSuccessful')->willReturn(true);

        $this->assertTrue($service->start());
        $this->assertTrue($service->stop());
        $this->assertTrue($service->enable());
        $this->assertTrue($service->disable());
        $this->assertTrue($service->reload());
        $this->assertTrue($service->restart());
    }

    public function testServiceCommandsIfProcessIsUnsuccessFulShouldRaiseException()
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

        $service = $systemctl->getService('AwesomeService');

        $process->method('isSuccessful')->willReturn(false);
        $this->expectException(CommandFailedException::class);
        $service->start();
    }

    public function testTimerCommandsIfProcessIsSuccessfulShouldReturnTrue()
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

        $timer = $systemctl->getTimer('AwesomeTimer');

        $process->method('isSuccessful')->willReturn(true);

        $this->assertTrue($timer->start());
        $this->assertTrue($timer->stop());
        $this->assertTrue($timer->enable());
        $this->assertTrue($timer->disable());
        $this->assertTrue($timer->reload());
        $this->assertTrue($timer->restart());
    }

    public function testTimerCommandsIfProcessIsUnsuccessFulShouldRaiseException()
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

        $timer = $systemctl->getTimer('AwesomeTimer');

        $this->expectException(CommandFailedException::class);
        $timer->start();
    }
}
