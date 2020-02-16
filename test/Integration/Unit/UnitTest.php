<?php

namespace icanhazstring\SystemCtl\Test\Integration\Unit;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use icanhazstring\SystemCtl\Command\CommandDispatcherInterface;
use icanhazstring\SystemCtl\Command\CommandInterface;
use icanhazstring\SystemCtl\Exception\CommandFailedException;
use icanhazstring\SystemCtl\Unit\Service;
use icanhazstring\SystemCtl\Unit\Timer;
use icanhazstring\SystemCtl\Unit\Socket;

/**
 * Class UnitTest
 *
 * @package icanhazstring\SystemCtl\Test\Integration\Unit
 */
class UnitTest extends TestCase
{
    public function testServiceCommandsIfProcessIsSuccessfulShouldReturnTrue(): void
    {
        $command = $this->prophesize(CommandInterface::class);
        $command->isSuccessful()->willReturn(true);

        $commandDispatcher = $this->createCommandDispatcherStub();
        $commandDispatcher->dispatch(Argument::cetera())->willReturn($command);

        $service = new Service('AwesomeService', $commandDispatcher->reveal());

        $this->assertTrue($service->start());
        $this->assertTrue($service->stop());
        $this->assertTrue($service->enable());
        $this->assertTrue($service->disable());
        $this->assertTrue($service->reload());
        $this->assertTrue($service->restart());
    }

    public function createCommandDispatcherStub(): ObjectProphecy
    {
        $commandDispatcher = $this->prophesize(CommandDispatcherInterface::class);
        $commandDispatcher->setTimeout(Argument::any())->willReturn($commandDispatcher);
        $commandDispatcher->setBinary(Argument::any())->willReturn($commandDispatcher);

        return $commandDispatcher;
    }

    public function testServiceCommandsIfProcessIsUnsuccessFulShouldRaiseException(): void
    {
        $commandDispatcher = $this->createCommandDispatcherStub();
        $commandDispatcher->dispatch(Argument::cetera())->willThrow(CommandFailedException::class);

        $service = new Service('AwesomeService', $commandDispatcher->reveal());

        $this->expectException(CommandFailedException::class);
        $service->start();
    }

    public function testTimerCommandsIfProcessIsSuccessfulShouldReturnTrue(): void
    {
        $command = $this->prophesize(CommandInterface::class);
        $command->isSuccessful()->willReturn(true);

        $commandDispatcher = $this->createCommandDispatcherStub();
        $commandDispatcher->dispatch(Argument::cetera())->willReturn($command);

        $timer = new Timer('AwesomeService', $commandDispatcher->reveal());

        $this->assertTrue($timer->start());
        $this->assertTrue($timer->stop());
        $this->assertTrue($timer->enable());
        $this->assertTrue($timer->disable());
        $this->assertTrue($timer->reload());
        $this->assertTrue($timer->restart());
    }

    public function testTimerCommandsIfProcessIsUnsuccessFulShouldRaiseException(): void
    {
        $commandDispatcher = $this->createCommandDispatcherStub();
        $commandDispatcher->dispatch(Argument::cetera())->willThrow(CommandFailedException::class);

        $timer = new Timer('AwesomeTimer', $commandDispatcher->reveal());

        $this->expectException(CommandFailedException::class);
        $timer->start();
    }

    public function testSocketCommandsIfProcessIsSuccessfulShouldReturnTrue(): void
    {
        $command = $this->prophesize(CommandInterface::class);
        $command->isSuccessful()->willReturn(true);

        $commandDispatcher = $this->createCommandDispatcherStub();
        $commandDispatcher->dispatch(Argument::cetera())->willReturn($command);

        $socket = new Socket('AwesomeSocket', $commandDispatcher->reveal());

        $this->assertTrue($socket->start());
        $this->assertTrue($socket->stop());
        $this->assertTrue($socket->enable());
        $this->assertTrue($socket->disable());
        $this->assertTrue($socket->reload());
        $this->assertTrue($socket->restart());
    }

    public function testSocketCommandsIfProcessIsUnsuccessFulShouldRaiseException(): void
    {
        $commandDispatcher = $this->createCommandDispatcherStub();
        $commandDispatcher->dispatch(Argument::cetera())->willThrow(CommandFailedException::class);

        $socket = new Socket('AwesomeSocket', $commandDispatcher->reveal());

        $this->expectException(CommandFailedException::class);
        $socket->start();
    }
}
