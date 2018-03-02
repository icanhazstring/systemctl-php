<?php
declare(strict_types=1);

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
 * UnitTest
 *
 * @package SystemCtl\Tests\Integration\Unit
 * @author  icanhazstring <blubb0r05+github@gmail.com>
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

        self::assertTrue($service->start());
        self::assertTrue($service->stop());
        self::assertTrue($service->enable());
        self::assertTrue($service->disable());
        self::assertTrue($service->reload());
        self::assertTrue($service->restart());
    }

    public function createCommandDispatcherStub(): ObjectProphecy
    {
        $commandDispatcher = $this->prophesize(CommandDispatcherInterface::class);
        $commandDispatcher->setTimeout(Argument::type('int'))->willReturn($commandDispatcher);
        $commandDispatcher->setBinary(Argument::type('string'))->willReturn($commandDispatcher);

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

        self::assertTrue($timer->start());
        self::assertTrue($timer->stop());
        self::assertTrue($timer->enable());
        self::assertTrue($timer->disable());
        self::assertTrue($timer->reload());
        self::assertTrue($timer->restart());
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
