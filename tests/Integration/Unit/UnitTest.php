<?php

namespace SystemCtl\Test\Integration\Unit;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use SystemCtl\Command\CommandDispatcherInterface;
use SystemCtl\Exception\CommandFailedException;
use SystemCtl\SystemCtl;
use SystemCtl\Unit\Service;

class UnitTest extends TestCase
{
    public function createCommandDispatcherStub(): ObjectProphecy
    {
        $commandDispatcher = $this->prophesize(CommandDispatcherInterface::class);
        $commandDispatcher->setTimeout(Argument::any())->willReturn($commandDispatcher);
        $commandDispatcher->setBinary(Argument::any())->willReturn($commandDispatcher);

        return $commandDispatcher;
    }

    public function testServiceCommandsIfProcessIsSuccessfulShouldReturnTrue()
    {
        $commandDispatcher = $this->createCommandDispatcherStub();
        $commandDispatcher->dispatch(Argument::cetera())->willReturn(true);

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcher->reveal());
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
        $commandDispatcher = $this->createCommandDispatcherStub();
        $commandDispatcher->dispatch(Argument::cetera())->willThrow(CommandFailedException::class);

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcher->reveal());
        $service = $systemctl->getService('AwesomeService');

        $this->expectException(CommandFailedException::class);
        $service->start();
    }

    public function testTimerCommandsIfProcessIsSuccessfulShouldReturnTrue()
    {
        $commandDispatcher = $this->createCommandDispatcherStub();
        $commandDispatcher->dispatch(Argument::cetera())->willReturn(true);

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcher->reveal());
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
        $commandDispatcher = $this->createCommandDispatcherStub();
        $commandDispatcher->dispatch(Argument::cetera())->willThrow(CommandFailedException::class);

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcher->reveal());
        $timer = $systemctl->getTimer('AwesomeTimer');

        $this->expectException(CommandFailedException::class);
        $timer->start();
    }
}
