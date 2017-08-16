<?php

namespace SystemCtl\Test\Unit\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use SystemCtl\Exception\CommandFailedException;
use SystemCtl\SystemCtl;

class ServiceTest extends TestCase
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
        $service->yell(true);

        $this->expectException(CommandFailedException::class);
        $this->expectExceptionMessage('Failed to start service AwesomeService');

        $service->start();
    }
}
