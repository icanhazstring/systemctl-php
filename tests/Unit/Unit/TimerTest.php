<?php

namespace SystemCtl\Test\Unit\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use SystemCtl\Exception\CommandFailedException;
use SystemCtl\SystemCtl;

class TimerTest extends TestCase
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
        $timer->yell(true);

        $this->expectException(CommandFailedException::class);
        $timer->start();
    }
}
