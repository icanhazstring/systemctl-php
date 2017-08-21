<?php

namespace SystemCtl\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use SystemCtl\Command\CommandDispatcherInterface;
use SystemCtl\Command\CommandInterface;
use SystemCtl\Exception\UnitNotFoundException;
use SystemCtl\SystemCtl;
use SystemCtl\Unit\Service;
use SystemCtl\Unit\Timer;

/**
 * Class SystemCtlTest
 *
 * @package SystemCtl\Test\Unit
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
    public function itShouldCallCommandDispatcherWithListUnitsAndUnitPrefixOnServiceGetting()
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
    public function itShouldReturnAServiceOnServiceGetting()
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
    public function itShouldReturnAServiceWithTheCorrectNameOnServiceGetting()
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
    public function itShouldThrowAnExceptionIfNotServicesCouldBeFound()
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
    public function itShouldCallCommandDispatcherWithListUnitsAndUnitPrefixOnTimerGetting()
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
    public function itShouldThrowAnExeceptionIfNotTimerCouldBeFound()
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
    public function itShouldReturnATimerOnTimerGetting()
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
    public function itShouldReturnATimerWithTheCorrectNameOnTimerGetting()
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
