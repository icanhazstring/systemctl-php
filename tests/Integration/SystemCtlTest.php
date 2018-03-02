<?php
declare(strict_types=1);

namespace SystemCtl\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use SystemCtl\Command\CommandDispatcherInterface;
use SystemCtl\Command\CommandInterface;
use SystemCtl\SystemCtl;
use SystemCtl\Template\Installer\UnitInstaller;
use SystemCtl\Template\Renderer\PlatesRenderer;
use SystemCtl\Template\Section\ServiceSection;
use SystemCtl\Template\ServiceUnitTemplate;
use Vfs\FileSystem;
use Vfs\Node\Directory;
use Vfs\Node\File;

/**
 * SystemCtlTest
 *
 * @package SystemCtl\Tests\Integration
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class SystemCtlTest extends TestCase
{
    /** @var FileSystem */
    private static $fileSystem;

    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass()
    {
        self::$fileSystem = FileSystem::factory('vfs://');
        self::$fileSystem->mount();

        self::$fileSystem->get('/')->add('units', new Directory);
        self::$fileSystem->get('/')->add('assets', new Directory);

        self::$fileSystem->get('/units')->add('testUnit.service', new File);
        self::$fileSystem->get('/assets/')->add('unit-template.tpl', new File(
            file_get_contents(__DIR__ . '/../../assets/unit-template.tpl')
        ));
    }

    /**
     * @inheritDoc
     */
    public static function tearDownAfterClass()
    {
        self::$fileSystem->unmount();
    }

    /**
     * @return ObjectProphecy
     */
    private function buildCommandDispatcherStub(): ObjectProphecy
    {
        $commandDispatcherStub = $this->prophesize(CommandDispatcherInterface::class);
        $commandDispatcherStub->setTimeout(Argument::type('int'))->willReturn($commandDispatcherStub);
        $commandDispatcherStub->setBinary(Argument::type('string'))->willReturn($commandDispatcherStub);
        $commandDispatcherStub->setArguments(Argument::type('array'))->willReturn($commandDispatcherStub);

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
        $command->isSuccessful()->willReturn(true);

        return $command;
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
EOT;

        $command = $this->prophesize(CommandInterface::class);
        $command->getOutput()->willReturn($output);

        $dispatcherStub = $this->buildCommandDispatcherStub();
        $dispatcherStub->dispatch(Argument::cetera())->willReturn($command);

        $systemctl = new SystemCtl();
        $systemctl->setCommandDispatcher($dispatcherStub->reveal());

        $units = $systemctl->listUnits(null, SystemCtl::AVAILABLE_UNITS);
        self::assertCount(11, $units);
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

        $dispatcherStub = $this->buildCommandDispatcherStub();
        $dispatcherStub->dispatch(Argument::cetera())->willReturn($command);

        $systemctl = new SystemCtl();
        $systemctl->setCommandDispatcher($dispatcherStub->reveal());

        $units = $systemctl->listUnits();
        self::assertCount(5, $units);
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

        $dispatcherStub = $this->buildCommandDispatcherStub();
        $dispatcherStub->dispatch(Argument::cetera())->willReturn($command);

        $systemctl = new SystemCtl();
        $systemctl->setCommandDispatcher($dispatcherStub->reveal());

        $services = $systemctl->getServices();

        self::assertCount(2, $services);
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

        $dispatcherStub = $this->buildCommandDispatcherStub();
        $dispatcherStub->dispatch(Argument::cetera())->willReturn($command);

        $systemctl = new SystemCtl();
        $systemctl->setCommandDispatcher($dispatcherStub->reveal());
        $timers = $systemctl->getTimers();

        self::assertCount(2, $timers);
    }

    /**
     * @test
     */
    public function itShouldReturnTrueOnSuccessfulDaemonReload()
    {
        $command = $this->prophesize(CommandInterface::class);
        $command->isSuccessful()->willReturn(true);

        $dispatcher = $this->buildCommandDispatcherStub();
        $dispatcher->dispatch('daemon-reload')->willReturn($command);

        $systemCtl = new SystemCtl();
        $systemCtl->setCommandDispatcher($dispatcher->reveal());

        self::assertTrue($systemCtl->daemonReload());
    }

    /**
     * @test
     */
    public function itShouldReturnUnitAfterInstall()
    {
        $unitName = 'testService';

        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch('list-units', $unitName)
            ->willReturn($this->buildCommandStub('testService.service Active Running'))
            ->shouldBeCalled();

        $commandDispatcherStub
            ->dispatch('daemon-reload')
            ->willReturn($this->buildCommandStub(''))
            ->shouldBeCalled();

        $template = new ServiceUnitTemplate('awesomeService');

        $template
            ->getServiceSection()
            ->setType(ServiceSection::TYPE_SIMPLE);

        $renderer = new PlatesRenderer('vfs://assets/');

        $installer = (new UnitInstaller)->setPath('vfs://units/')->setRenderer($renderer);

        $systemctl = (new SystemCtl)->setCommandDispatcher($commandDispatcherStub->reveal());
        $systemctl->setUnitInstaller($installer);

        $unitTemplate = new ServiceUnitTemplate($unitName);
        $unitTemplate->getServiceSection()->setType(ServiceSection::TYPE_SIMPLE);

        $unit = $systemctl->install($unitTemplate);

        self::assertEquals($unitName, $unit->getName());
    }

    /**
     * @test
     */
    public function itShouldReturnDefaultInstallerIfReceived()
    {
        SystemCtl::setAssetPath('vfs://');

        $systemCtl = new SystemCtl;
        self::assertInstanceOf(UnitInstaller::class, $systemCtl->getUnitInstaller());
    }

    /**
     * @test
     */
    public function itShouldAddScopeArgumentToDispatcher()
    {
        $output = 'testService.service Active';

        $dispatcher = $this->buildCommandDispatcherStub();
        $dispatcher->setArguments(['--system'])->shouldBeCalled()->willReturn($dispatcher->reveal());
        $dispatcher->dispatch('list-units', 'testService')
            ->shouldBeCalled()
            ->willReturn($this->buildCommandStub($output));

        $systemCtl = new SystemCtl;
        $systemCtl->setCommandDispatcher($dispatcher->reveal());

        $systemCtl->getService('testService');

        $dispatcher->setArguments(['--user'])->shouldBeCalled()->willReturn($dispatcher->reveal());
        $systemCtl->user()->getService('testService');
    }
}
