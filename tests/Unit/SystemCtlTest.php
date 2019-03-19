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
use SystemCtl\Template\UnitTemplateInterface;
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

        return $command;
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function itShouldChangeStaticPropertiesIfSet(): void
    {
        SystemCtl::setBinary('testBinary');
        SystemCtl::setTimeout(5);
        SystemCtl::setAssetPath('testAssetPath');

        $reflection = new \ReflectionClass(SystemCtl::class);
        $binaryProperty = $reflection->getProperty('binary');
        $binaryProperty->setAccessible(true);
        $timeoutProperty = $reflection->getProperty('timeout');
        $timeoutProperty->setAccessible(true);
        $assetPathProperty = $reflection->getProperty('assetPath');
        $assetPathProperty->setAccessible(true);

        self::assertEquals('testBinary', $binaryProperty->getValue(new SystemCtl));
        self::assertEquals(5, $timeoutProperty->getValue(new SystemCtl));
        self::assertEquals('testAssetPath', $assetPathProperty->getValue(new SystemCtl));
    }

    /**
     * @test
     */
    public function itShouldInstantiateDefaultCommandDispatcherIfReceived(): void
    {
        $systemCtl = new SystemCtl;
        self::assertInstanceOf(SymfonyCommandDispatcher::class, $systemCtl->getCommandDispatcher());
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
            ->dispatch('--all', 'list-units', $unitName)
            ->willReturn($this->buildCommandStub($output));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $service = $systemctl->getService($unitName);

        self::assertInstanceOf(Service::class, $service);
        self::assertEquals('testService', $service->getName());
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
            ->dispatch('--all', 'list-units', $unitName)
            ->willReturn($this->buildCommandStub($output));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $service = $systemctl->getService($unitName);
        self::assertInstanceOf(Service::class, $service);
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
            ->dispatch('--all', 'list-units', $unitName)
            ->willReturn($this->buildCommandStub($output));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $service = $systemctl->getService($unitName);
        self::assertEquals('testService', $service->getName());
    }

    /**
     * @test
     */
    public function itShouldThrowAnExceptionIfNoServiceCouldBeFound(): void
    {
        $unitName = 'testService';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch('--all', 'list-units', $unitName)
            ->willReturn($this->buildCommandStub(''));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $this->expectException(UnitNotFoundException::class);
        $systemctl->getService($unitName);
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
            ->dispatch('--all', 'list-units', $unitName)
            ->willReturn($this->buildCommandStub($output));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $timer = $systemctl->getTimer($unitName);
        self::assertInstanceOf(Timer::class, $timer);
        self::assertEquals($unitName, $timer->getName());
    }

    /**
     * @test
     */
    public function itShouldThrowAnExeceptionIfNotTimerCouldBeFound(): void
    {
        $unitName = 'testTimer';
        $commandDispatcherStub = $this->buildCommandDispatcherStub();
        $commandDispatcherStub
            ->dispatch('--all', 'list-units', $unitName)
            ->willReturn($this->buildCommandStub(''));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $this->expectException(UnitNotFoundException::class);
        $systemctl->getTimer($unitName);
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
            ->dispatch('--all', 'list-units', $unitName)
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
            ->dispatch('--all', 'list-units', $unitName)
            ->willReturn($this->buildCommandStub($output));

        $systemctl = (new SystemCtl())->setCommandDispatcher($commandDispatcherStub->reveal());

        $service = $systemctl->getService($unitName);
        self::assertEquals('testService', $service->getName());
    }

    /**
     * @test
     */
    public function itShouldRaiseAnExceptionWhenAttemptingToInstallUnsupportedUnit(): void
    {
        $template = $this->prophesize(UnitTemplateInterface::class);
        $template->getUnitName()->willReturn('test');
        $template->getUnitSuffix()->willReturn('fubar');

        $systemCtl = new SystemCtl;

        $this->expectException(UnitTypeNotSupportedException::class);
        $systemCtl->install($template->reveal());
    }
}
