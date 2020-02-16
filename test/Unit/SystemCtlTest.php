<?php

namespace icanhazstring\SystemCtl\Test\Unit;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use icanhazstring\SystemCtl\Command\CommandDispatcherInterface;
use icanhazstring\SystemCtl\Command\CommandInterface;
use icanhazstring\SystemCtl\Exception\UnitNotFoundException;
use icanhazstring\SystemCtl\SystemCtl;
use icanhazstring\SystemCtl\Unit\Service;
use icanhazstring\SystemCtl\Unit\Timer;
use icanhazstring\SystemCtl\Unit\Socket;

/**
 * Class SystemCtlTest
 *
 * @package icanhazstring\SystemCtl\Test\Unit
 */
class SystemCtlTest extends TestCase
{
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
        $this->assertInstanceOf(Service::class, $service);
        $this->assertEquals('testService', $service->getName());
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
        $this->assertInstanceOf(Service::class, $service);
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
        $this->assertEquals('testService', $service->getName());
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
        $this->assertInstanceOf(Timer::class, $timer);
        $this->assertEquals($unitName, $timer->getName());
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
        $this->assertInstanceOf(Socket::class, $socket);
        $this->assertEquals($unitName, $socket->getName());
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
        $this->assertInstanceOf(Service::class, $service);
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
        $this->assertEquals('testService', $service->getName());
    }
}
