<?php

namespace icanhazstring\SystemCtl\Test\Integration\Unit;

use icanhazstring\SystemCtl\Unit\Device;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use icanhazstring\SystemCtl\Command\CommandDispatcherInterface;
use icanhazstring\SystemCtl\Command\CommandInterface;
use icanhazstring\SystemCtl\Exception\CommandFailedException;
use icanhazstring\SystemCtl\Unit\Service;
use icanhazstring\SystemCtl\Unit\Timer;
use icanhazstring\SystemCtl\Unit\Socket;
use icanhazstring\SystemCtl\Unit\Scope;
use icanhazstring\SystemCtl\Unit\Slice;
use icanhazstring\SystemCtl\Unit\Target;

/**
 * Class UnitTest
 *
 * @package icanhazstring\SystemCtl\Test\Integration\Unit
 */
class UnitTest extends TestCase
{
    use ProphecyTrait;

    public function testServiceCommandsIfProcessIsSuccessfulShouldReturnTrue(): void
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

        self::assertTrue($timer->start());
        self::assertTrue($timer->stop());
        self::assertTrue($timer->enable());
        self::assertTrue($timer->disable());
        self::assertTrue($timer->reload());
        self::assertTrue($timer->restart());
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

        self::assertTrue($socket->start());
        self::assertTrue($socket->stop());
        self::assertTrue($socket->enable());
        self::assertTrue($socket->disable());
        self::assertTrue($socket->reload());
        self::assertTrue($socket->restart());
    }

    public function testSocketCommandsIfProcessIsUnsuccessFulShouldRaiseException(): void
    {
        $commandDispatcher = $this->createCommandDispatcherStub();
        $commandDispatcher->dispatch(Argument::cetera())->willThrow(CommandFailedException::class);

        $socket = new Socket('AwesomeSocket', $commandDispatcher->reveal());

        $this->expectException(CommandFailedException::class);
        $socket->start();
    }

    public function testScopeCommandsIfProcessIsSuccessfulShouldReturnTrue(): void
    {
        $command = $this->prophesize(CommandInterface::class);
        $command->isSuccessful()->willReturn(true);

        $commandDispatcher = $this->createCommandDispatcherStub();
        $commandDispatcher->dispatch(Argument::cetera())->willReturn($command);

        $scope = new Scope('AwesomeScope', $commandDispatcher->reveal());

        self::assertTrue($scope->start());
        self::assertTrue($scope->stop());
        self::assertTrue($scope->enable());
        self::assertTrue($scope->disable());
        self::assertTrue($scope->reload());
        self::assertTrue($scope->restart());
    }

    public function testScopeCommandsIfProcessIsUnsuccessFulShouldRaiseException(): void
    {
        $commandDispatcher = $this->createCommandDispatcherStub();
        $commandDispatcher->dispatch(Argument::cetera())->willThrow(CommandFailedException::class);

        $scope = new Scope('AwesomeScope', $commandDispatcher->reveal());

        $this->expectException(CommandFailedException::class);
        $scope->start();
    }

    public function testSliceCommandsIfProcessIsSuccessfulShouldReturnTrue()
    {
        $command = $this->prophesize(CommandInterface::class);
        $command->isSuccessful()->willReturn(true);

        $commandDispatcher = $this->createCommandDispatcherStub();
        $commandDispatcher->dispatch(Argument::cetera())->willReturn($command);

        $slice = new Slice('AwesomeSlice', $commandDispatcher->reveal());

        $this->assertTrue($slice->start());
        $this->assertTrue($slice->stop());
        $this->assertTrue($slice->enable());
        $this->assertTrue($slice->disable());
        $this->assertTrue($slice->reload());
        $this->assertTrue($slice->restart());
    }

    public function testSliceCommandsIfProcessIsUnsuccessFulShouldRaiseException()
    {
        $commandDispatcher = $this->createCommandDispatcherStub();
        $commandDispatcher->dispatch(Argument::cetera())->willThrow(CommandFailedException::class);

        $slice = new Slice('AwesomeSlice', $commandDispatcher->reveal());

        $this->expectException(CommandFailedException::class);
        $slice->start();
    }

    public function testTargetCommandsIfProcessIsSuccessfulShouldReturnTrue()
    {
        $command = $this->prophesize(CommandInterface::class);
        $command->isSuccessful()->willReturn(true);

        $commandDispatcher = $this->createCommandDispatcherStub();
        $commandDispatcher->dispatch(Argument::cetera())->willReturn($command);

        $target = new Target('AwesomeTarget', $commandDispatcher->reveal());

        $this->assertTrue($target->start());
        $this->assertTrue($target->stop());
        $this->assertTrue($target->enable());
        $this->assertTrue($target->disable());
        $this->assertTrue($target->reload());
        $this->assertTrue($target->restart());
    }

    public function testTargetCommandsIfProcessIsUnsuccessFulShouldRaiseException()
    {
        $commandDispatcher = $this->createCommandDispatcherStub();
        $commandDispatcher->dispatch(Argument::cetera())->willThrow(CommandFailedException::class);

        $target = new Target('AwesomeTarget', $commandDispatcher->reveal());

        $this->expectException(CommandFailedException::class);
        $target->start();
    }

    public function testDeviceCommandsIfProcessIsSuccessfulShouldReturnTrue()
    {
        $command = $this->prophesize(CommandInterface::class);
        $command->isSuccessful()->willReturn(true);

        $commandDispatcher = $this->createCommandDispatcherStub();
        $commandDispatcher->dispatch(Argument::cetera())->willReturn($command);

        $device = new Device('AwesomeDevice', $commandDispatcher->reveal());

        self::assertTrue($device->start());
        self::assertTrue($device->stop());
        self::assertTrue($device->enable());
        self::assertTrue($device->disable());
        self::assertTrue($device->reload());
        self::assertTrue($device->restart());
    }

    public function testDeviceCommandsIfProcessIsUnsuccessFulShouldRaiseException()
    {
        $commandDispatcher = $this->createCommandDispatcherStub();
        $commandDispatcher->dispatch(Argument::cetera())->willThrow(CommandFailedException::class);

        $device = new Device('AwesomeDevice', $commandDispatcher->reveal());

        $this->expectException(CommandFailedException::class);
        $device->start();
    }
}
