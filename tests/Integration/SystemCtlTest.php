<?php

namespace SystemCtl\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use SystemCtl\Command\CommandDispatcherInterface;
use SystemCtl\Command\CommandInterface;
use SystemCtl\Exception\UnitTypeNotSupportedException;
use SystemCtl\SystemCtl;
use SystemCtl\Unit\Service;
use SystemCtl\Unit\UnitInterface;

/**
 * Class SystemCtlTest
 *
 * @package SystemCtl\Test\Integration
 */
class SystemCtlTest extends TestCase
{
    /**
     * @return ObjectProphecy
     */
    public function createCommandDispatcherStub(): ObjectProphecy
    {
        $commandDispatcher = $this->prophesize(CommandDispatcherInterface::class);
        $commandDispatcher->setTimeout(Argument::any())->willReturn($commandDispatcher);
        $commandDispatcher->setBinary(Argument::any())->willReturn($commandDispatcher);

        return $commandDispatcher;
    }

    public function testListUnitsWithAvailableUnits()
    {
        $output = <<<EOT
  proc-sys-fs-binfmt_misc.timer                      loaded active mounted
  run-rpc_pipefs.mount                               loaded active mounted
  sys-fs-fuse-connections.mount                      loaded active mounted
  sys-kernel-debug.mount                             loaded active mounted
  acpid.path                                         loaded active running
  systemd-ask-password-console.path                  loaded active waiting
  systemd-ask-password-wall.path                     loaded active waiting
  acpid.service                                      loaded active running
  beanstalkd.service                                 loaded active running
  console-setup.service                              loaded active exited
  cron.service                                       loaded active running
● failed-server@foo.service                       loaded failed failed
EOT;

        $command = $this->prophesize(CommandInterface::class);
        $command->getOutput()->willReturn($output);

        $dispatcherStub = $this->createCommandDispatcherStub();
        $dispatcherStub->dispatch(Argument::cetera())->willReturn($command);

        $systemctl = new SystemCtl();
        $systemctl->setCommandDispatcher($dispatcherStub->reveal());

        $units = $systemctl->listUnits(null, SystemCtl::AVAILABLE_UNITS);
        $this->assertCount(12, $units);
    }

    public function testListUnitsWithSupportedUnits()
    {
        $output = <<<EOT
  proc-sys-fs-binfmt_misc.timer                      loaded active mounted
  run-rpc_pipefs.mount                               loaded active mounted
  sys-fs-fuse-connections.mount                      loaded active mounted
  sys-kernel-debug.mount                             loaded active mounted
  acpid.path                                         loaded active running
  systemd-ask-password-console.path                  loaded active waiting
  systemd-ask-password-wall.path                     loaded active waiting
  acpid.service                                      loaded active running
  beanstalkd.service                                 loaded active running
  console-setup.service                              loaded active exited
  cron.service                                       loaded active running
EOT;

        $command = $this->prophesize(CommandInterface::class);
        $command->getOutput()->willReturn($output);

        $dispatcherStub = $this->createCommandDispatcherStub();
        $dispatcherStub->dispatch(Argument::cetera())->willReturn($command);

        $systemctl = new SystemCtl();
        $systemctl->setCommandDispatcher($dispatcherStub->reveal());

        $units = $systemctl->listUnits();
        $this->assertCount(5, $units);
    }

    public function testCreateUnitFromSupportedSuffixShouldWord()
    {
        $unit = SystemCtl::unitFromSuffix('service', 'SuccessService');
        $this->assertInstanceOf(UnitInterface::class, $unit);
        $this->assertInstanceOf(Service::class, $unit);
        $this->assertEquals('SuccessService', $unit->getName());
    }

    public function testCreateUnitFromUnsupportedSuffixShouldRaiseException()
    {
        $this->expectException(UnitTypeNotSupportedException::class);
        SystemCtl::unitFromSuffix('unsupported', 'FailUnit');
    }

    public function testGetServices()
    {
        $output = <<<EOT
PLACEHOLDER STUFF
  superservice.service      Active running
  awesomeservice.service    Active running
  nonservice.timer          Active running
PLACEHOLDER STUFF

EOT;

        $command = $this->prophesize(CommandInterface::class);
        $command->getOutput()->willReturn($output);

        $dispatcherStub = $this->createCommandDispatcherStub();
        $dispatcherStub->dispatch(Argument::cetera())->willReturn($command);

        $systemctl = new SystemCtl();
        $systemctl->setCommandDispatcher($dispatcherStub->reveal());

        $services = $systemctl->getServices();

        $this->assertCount(2, $services);
    }

    public function testGetTimers()
    {
        $output = <<<EOT
PLACEHOLDER STUFF
  superservice.service      Active running
  awesomeservice.timer      Active running
  nonservice.timer          Active running
PLACEHOLDER STUFF

EOT;

        $command = $this->prophesize(CommandInterface::class);
        $command->getOutput()->willReturn($output);

        $dispatcherStub = $this->createCommandDispatcherStub();
        $dispatcherStub->dispatch(Argument::cetera())->willReturn($command);

        $systemctl = new SystemCtl();
        $systemctl->setCommandDispatcher($dispatcherStub->reveal());
        $timers = $systemctl->getTimers();

        $this->assertCount(2, $timers);
    }

    /**
     * @test
     */
    public function itShouldReturnTrueOnSuccessfulDaemonReload()
    {
        $command = $this->prophesize(CommandInterface::class);
        $command->isSuccessful()->willReturn(true);

        $dispatcher = $this->createCommandDispatcherStub();
        $dispatcher->dispatch(Argument::exact('daemon-reload'))->willReturn($command);

        $systemCtl = new SystemCtl();
        $systemCtl->setCommandDispatcher($dispatcher->reveal());

        $this->assertTrue($systemCtl->daemonReload());
    }
}
