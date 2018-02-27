<?php
declare(strict_types=1);

namespace SystemCtl\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use SystemCtl\Command\CommandDispatcherInterface;
use SystemCtl\Command\CommandInterface;
use SystemCtl\Command\SymfonyCommandDispatcher;
use SystemCtl\Exception\UnitNotFoundException;
use SystemCtl\Exception\UnitTypeNotSupportedException;
use SystemCtl\SystemCtl;
use SystemCtl\Template\AbstractUnitTemplate;
use SystemCtl\Unit\Service;
use SystemCtl\Unit\Timer;

/**
 * SystemCtlTest
 *
 * @package SystemCtl\Tests\Unit
 * @author  icanhazstring <blubb0r05+github@gmail.com>
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
    public function itShouldChangeStaticPropertiesIfSet()
    {
        SystemCtl::setBinary('testBinary');
        SystemCtl::setTimeout(5);
        SystemCtl::setInstallPath('testInstallPath');
        SystemCtl::setAssetPath('testAssetPath');

        $reflection = new \ReflectionClass(SystemCtl::class);
        $binaryProperty = $reflection->getProperty('binary');
        $binaryProperty->setAccessible(true);
        $timeoutProperty = $reflection->getProperty('timeout');
        $timeoutProperty->setAccessible(true);
        $installPathProperty = $reflection->getProperty('installPath');
        $installPathProperty->setAccessible(true);
        $assetPathProperty = $reflection->getProperty('assetPath');
        $assetPathProperty->setAccessible(true);

        $this->assertEquals('testBinary', $binaryProperty->getValue(new SystemCtl));
        $this->assertEquals(5, $timeoutProperty->getValue(new SystemCtl));
        $this->assertEquals('testInstallPath', $installPathProperty->getValue(new SystemCtl));
        $this->assertEquals('testAssetPath', $assetPathProperty->getValue(new SystemCtl));
    }

    /**
     * @test
     */
    public function itShouldInstantiateDefaultCommandDispatcherIfReceived()
    {
        $systemCtl = new SystemCtl;
        $this->assertInstanceOf(SymfonyCommandDispatcher::class, $systemCtl->getCommandDispatcher());
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
            ->dispatch('list-units', $unitName)
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
            ->dispatch('list-units', $unitName)
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
            ->dispatch('list-units', $unitName)
            ->willReturn($this->buildCommandStub($output));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $service = $systemctl->getService($unitName);
        $this->assertEquals('testService', $service->getName());
    }

    /**
     * @test
     */
    public function itShouldThrowAnExceptionIfNoServiceCouldBeFound()
    {
        $unitName = 'testService';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch('list-units', $unitName)
            ->willReturn($this->buildCommandStub(''));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $this->expectException(UnitNotFoundException::class);
        $systemctl->getService($unitName);
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
            ->dispatch('list-units', $unitName)
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
            ->dispatch('list-units', $unitName)
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
            ->dispatch('list-units', $unitName)
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
            ->dispatch('list-units', $unitName)
            ->willReturn($this->buildCommandStub($output));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $service = $systemctl->getService($unitName);
        $this->assertEquals('testService', $service->getName());
    }

    /**
     * @test
     */
    public function itShouldRaiseAnExceptionWhenAttemptingToInstallUnsupportedUnit()
    {
        $template = $this->prophesize(AbstractUnitTemplate::class);
        $template->getUnitName()->willReturn('test');
        $template->getUnitSuffix()->willReturn('fubar');

        $systemCtl = new SystemCtl;

        $this->expectException(UnitTypeNotSupportedException::class);
        $systemCtl->install($template->reveal());
    }
}
