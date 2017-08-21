<?php

namespace SystemCtl\Tests\Integration\Unit;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use SystemCtl\Command\CommandDispatcherInterface;
use SystemCtl\Command\CommandInterface;
use SystemCtl\Exception\CommandFailedException;
use SystemCtl\Unit\Service;
use SystemCtl\Unit\Timer;

/**
 * Class UnitTest
 *
 * @package SystemCtl\Test\Integration\Unit
 */
class UnitTest extends TestCase
{
    public function testServiceCommandsIfProcessIsSuccessfulShouldReturnTrue()
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

    public function testServiceCommandsIfProcessIsUnsuccessFulShouldRaiseException()
    {
        $commandDispatcher = $this->createCommandDispatcherStub();
        $commandDispatcher->dispatch(Argument::cetera())->willThrow(CommandFailedException::class);

        $service = new Service('AwesomeService', $commandDispatcher->reveal());

        $this->expectException(CommandFailedException::class);
        $service->start();
    }

    public function testTimerCommandsIfProcessIsSuccessfulShouldReturnTrue()
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

    public function testTimerCommandsIfProcessIsUnsuccessFulShouldRaiseException()
    {
        $commandDispatcher = $this->createCommandDispatcherStub();
        $commandDispatcher->dispatch(Argument::cetera())->willThrow(CommandFailedException::class);

        $timer = new Timer('AwesomeTimer', $commandDispatcher->reveal());

        $this->expectException(CommandFailedException::class);
        $timer->start();
    }
}
