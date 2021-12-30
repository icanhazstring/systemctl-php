<?php

namespace icanhazstring\SystemCtl\Test\Integration;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use icanhazstring\SystemCtl\Command\CommandDispatcherInterface;
use icanhazstring\SystemCtl\Command\CommandInterface;
use icanhazstring\SystemCtl\Exception\UnitTypeNotSupportedException;
use icanhazstring\SystemCtl\SystemCtl;
use icanhazstring\SystemCtl\Unit\Service;
use icanhazstring\SystemCtl\Unit\UnitInterface;

/**
 * Class SystemCtlTest
 *
 * @package icanhazstring\SystemCtl\Test\Integration
 */
class SystemCtlTest extends TestCase
{
    use ProphecyTrait;

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

    public function testListUnitsWithAvailableUnits(): void
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
        self::assertCount(12, $units);
    }

    public function testListUnitsWithSupportedUnits(): void
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
        self::assertCount(5, $units);
    }

    public function testCreateUnitFromSupportedSuffixShouldWord(): void
    {
        $unit = SystemCtl::unitFromSuffix('service', 'SuccessService');
        self::assertInstanceOf(UnitInterface::class, $unit);
        self::assertInstanceOf(Service::class, $unit);
        self::assertSame('SuccessService', $unit->getName());
    }

    public function testCreateUnitFromUnsupportedSuffixShouldRaiseException(): void
    {
        $this->expectException(UnitTypeNotSupportedException::class);
        SystemCtl::unitFromSuffix('unsupported', 'FailUnit');
    }

    public function testGetServices(): void
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

        self::assertCount(2, $services);
    }

    public function testGetTimers(): void
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

        self::assertCount(2, $timers);
    }

    /**
     * @test
     */
    public function itShouldReturnTrueOnSuccessfulDaemonReload(): void
    {
        $command = $this->prophesize(CommandInterface::class);
        $command->isSuccessful()->willReturn(true);

        $dispatcher = $this->createCommandDispatcherStub();
        $dispatcher->dispatch(Argument::exact('daemon-reload'))->willReturn($command);

        $systemCtl = new SystemCtl();
        $systemCtl->setCommandDispatcher($dispatcher->reveal());

        self::assertTrue($systemCtl->daemonReload());
    }
}
