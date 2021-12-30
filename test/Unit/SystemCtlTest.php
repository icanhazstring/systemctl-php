<?php

namespace icanhazstring\SystemCtl\Test\Unit;

use icanhazstring\SystemCtl\Unit\Device;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use icanhazstring\SystemCtl\Command\CommandDispatcherInterface;
use icanhazstring\SystemCtl\Command\CommandInterface;
use icanhazstring\SystemCtl\Exception\UnitNotFoundException;
use icanhazstring\SystemCtl\SystemCtl;
use icanhazstring\SystemCtl\Unit\Service;
use icanhazstring\SystemCtl\Unit\Timer;
use icanhazstring\SystemCtl\Unit\Socket;
use icanhazstring\SystemCtl\Unit\Scope;
use icanhazstring\SystemCtl\Unit\Slice;
use icanhazstring\SystemCtl\Unit\Swap;
use icanhazstring\SystemCtl\Unit\Target;
use icanhazstring\SystemCtl\Unit\Automount;
use icanhazstring\SystemCtl\Unit\Mount;

/**
 * Class SystemCtlTest
 *
 * @package icanhazstring\SystemCtl\Test\Unit
 */
class SystemCtlTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @return ObjectProphecy
     */
    private function buildCommandDispatcherStub(): ObjectProphecy
    {
        $commandDispatcherStub = $this->prophesize(CommandDispatcherInterface::class);
        $commandDispatcherStub->setTimeout(Argument::type('int'))->willReturn($commandDispatcherStub);
        $commandDispatcherStub->setBinary(Argument::type('string'))->willReturn($commandDispatcherStub);

        return $commandDispatcherStub;
    }

    /**
     * @param string $output
     *
     * @return ObjectProphecy
     */
    private function buildCommandStub(string $output): ObjectProphecy
    {
        $command = $this->prophesize(CommandInterface::class);
        $command->getOutput()->willReturn($output);

        return $command;
    }

    /**
     * @test
     */
    public function itShouldCallCommandDispatcherWithListUnitsAndUnitPrefixOnServiceGetting(): void
    {
        $unitName = 'testService';
        $output = ' testService.service     Active running';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub($output));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $service = $systemctl->getService($unitName);
        self::assertSame('testService', $service->getName());
    }

    /**
     * @test
     */
    public function itShouldReturnAServiceOnServiceGetting(): void
    {
        $unitName = 'testService';
        $output = ' testService.service     Active running';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub($output));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $service = $systemctl->getService($unitName);
        self::assertSame($unitName, $service->getName());
    }

    /**
     * @test
     */
    public function itShouldReturnAServiceWithTheCorrectNameOnServiceGetting(): void
    {
        $unitName = 'testService';
        $output = ' testService.service     Active running';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub($output));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $service = $systemctl->getService($unitName);
        self::assertSame('testService', $service->getName());
    }

    /**
     * @test
     */
    public function itShouldCallCommandDispatcherWithListUnitsAndUnitPrefixOnMountGetting()
    {
        $unitName = 'testMount';
        $output = ' testMount.mount     Active running';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub($output));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $mount = $systemctl->getMount($unitName);
        $this->assertInstanceOf(Mount::class, $mount);
        $this->assertSame($unitName, $mount->getName());
    }

    /**
     * @test
     */
    public function itShouldThrowAnExceptionIfNotServicesCouldBeFound(): void
    {
        $unitName = 'testService';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub(''));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $this->expectException(UnitNotFoundException::class);
        $systemctl->getTimer($unitName);
    }

    /**
     * @test
     */
    public function itShouldCallCommandDispatcherWithListUnitsAndUnitPrefixOnTimerGetting(): void
    {
        $unitName = 'testTimer';
        $output = ' testTimer.timer     Active running';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub($output));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $timer = $systemctl->getTimer($unitName);
        self::assertInstanceOf(Timer::class, $timer);
        self::assertSame($unitName, $timer->getName());
    }

    /**
     * @test
     */
    public function itShouldCallCommandDispatcherWithListUnitsAndUnitPrefixOnSocketGetting(): void
    {
        $unitName = 'testSocket';
        $output = ' testSocket.socket     Active running';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub($output));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $socket = $systemctl->getSocket($unitName);
        self::assertInstanceOf(Socket::class, $socket);
        self::assertSame($unitName, $socket->getName());
    }

    /**
     * @test
     */
    public function itShouldCallCommandDispatcherWithListUnitsAndUnitPrefixOnScopeGetting(): void
    {
        $unitName = 'testScope';
        $output = ' testScope.scope     Active running';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub($output));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $scope = $systemctl->getScope($unitName);
        self::assertInstanceOf(Scope::class, $scope);
        self::assertSame($unitName, $scope->getName());
    }

    /**
     * @test
     */
    public function itShouldCallCommandDispatcherWithListUnitsAndUnitPrefixOnSliceGetting()
    {
        $unitName = 'testSlice';
        $output = ' testSlice.slice     Active running';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub($output));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $slice = $systemctl->getSlice($unitName);
        $this->assertInstanceOf(Slice::class, $slice);
        $this->assertSame($unitName, $slice->getName());
    }

    /**
     * @test
     */
    public function itShouldCallCommandDispatcherWithListUnitsAndUnitPrefixOnTargetGetting()
    {
        $unitName = 'testTarget';
        $output = ' testTarget.target     Active running';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub($output));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $target = $systemctl->getTarget($unitName);
        $this->assertInstanceOf(Target::class, $target);
        $this->assertSame($unitName, $target->getName());
    }

    /**
     * @test
     */
    public function itShouldCallCommandDispatcherWithListUnitsAndUnitPrefixOnSwapGetting()
    {
        $unitName = 'testSwap';
        $output = ' testSwap.swap     Active running';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub($output));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $swap = $systemctl->getSwap($unitName);
        $this->assertInstanceOf(Swap::class, $swap);
        $this->assertSame($unitName, $swap->getName());
    }

    /**
     * @test
     */
    public function itShouldCallCommandDispatcherWithListUnitsAndUnitPrefixOnAutomountGetting()
    {
        $unitName = 'testAutomount';
        $output = ' testAutomount.automount     Active running';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub($output));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $automount = $systemctl->getAutomount($unitName);
        $this->assertInstanceOf(Automount::class, $automount);
        $this->assertSame($unitName, $automount->getName());
    }

    /**
     * @test
     */
    public function itShouldThrowAnExeceptionIfNotTimerCouldBeFound(): void
    {
        $unitName = 'testTimer';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub(''));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $this->expectException(UnitNotFoundException::class);
        $systemctl->getTimer($unitName);
    }

    /**
     * @test
     */
    public function itShouldThrowAnExceptionIfNoSocketCouldBeFound(): void
    {
        $unitName = 'testSocket';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub(''));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $this->expectException(UnitNotFoundException::class);
        $systemctl->getSocket($unitName);
    }

    /**
     * @test
     */
    public function itShouldThrowAnExceptionIfNoTargetCouldBeFound()
    {
        $unitName = 'testTarget';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub(''));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $this->expectException(UnitNotFoundException::class);
        $systemctl->getTarget($unitName);
    }

    /**
     * @test
     */
    public function itShouldThrowAnExceptionIfNoSwapCouldBeFound()
    {
        $unitName = 'testSwap';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub(''));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $this->expectException(UnitNotFoundException::class);
        $systemctl->getSwap($unitName);
    }

    /**
     * @test
     */
    public function itShouldThrowAnExceptionIfNoScopeCouldBeFound(): void
    {
        $unitName = 'testScope';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub(''));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $this->expectException(UnitNotFoundException::class);
        $systemctl->getScope($unitName);
    }

    /**
     * @test
     */
    public function itShouldThrowAnExceptionIfNoSliceCouldBeFound()
    {
        $unitName = 'testSlice';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub(''));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $this->expectException(UnitNotFoundException::class);
        $systemctl->getSlice($unitName);
    }

    /**
     * @test
     */
    public function itShouldReturnATimerOnTimerGetting(): void
    {
        $unitName = 'testService';
        $output = ' testService.service     Active running';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub($output));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $service = $systemctl->getService($unitName);
        self::assertInstanceOf(Service::class, $service);
    }

    /**
     * @test
     */
    public function itShouldReturnATimerWithTheCorrectNameOnTimerGetting(): void
    {
        $unitName = 'testService';
        $output = ' testService.service     Active running';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub($output));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $service = $systemctl->getService($unitName);
        self::assertSame('testService', $service->getName());
    }

    /**
     * @test
     */
    public function itShouldCallCommandDispatcherWithListUnitsAndUnitPrefixOnDeviceGetting()
    {
        $unitName = 'testDevice';
        $output = ' testDevice.device     Active running';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub($output));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $device = $systemctl->getDevice($unitName);
        self::assertInstanceOf(Device::class, $device);
        self::assertSame($unitName, $device->getName());
    }

    /**
     * @test
     */
    public function itShouldThrowAnExceptionIfNoDeviceCouldBeFound()
    {
        $unitName = 'testDevice';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub(''));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $this->expectException(UnitNotFoundException::class);
        $systemctl->getSocket($unitName);
    }

    /**
     * @test
     */
    public function itShouldThrowAnExceptionIfNoAutomountCouldBeFound()
    {
        $unitName = 'testAutomount';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub(''));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $this->expectException(UnitNotFoundException::class);
        $systemctl->getAutomount($unitName);
    }

    /**
     * @test
     */
    public function itShouldThrowAnExceptionIfNoMountCouldBeFound()
    {
        $unitName = 'testMount';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch(...['list-units', $unitName . '*'])
            ->willReturn($this->buildCommandStub(''));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $this->expectException(UnitNotFoundException::class);
        $systemctl->getMount($unitName);
    }
}
