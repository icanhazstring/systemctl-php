<?php

namespace SystemCtl\Tests\Unit\Unit;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use SystemCtl\Command\CommandDispatcherInterface;
use SystemCtl\Command\CommandInterface;
use SystemCtl\Exception\CommandFailedException;

/**
 * Class AbstractUnitTest
 *
 * @package SystemCtl\Tests\Unit\Unit
 */
class AbstractUnitTest extends TestCase
{
    /**
     * @var string
     */
    private const UNIT_NAME = 'testUnit';

    /**
     * @test
     * @dataProvider itShouldReturnCorrectNameDataProvider
     *
     * @param string $name
     */
    public function itShouldReturnCorrectName(string $name)
    {
        $commandDispatcher = $this->prophesize(CommandDispatcherInterface::class);
        $unit = new UnitStub($name, $commandDispatcher->reveal());

        $this->assertEquals($name, $unit->getName());
    }

    /**
     * @return array
     */
    public function itShouldReturnCorrectNameDataProvider(): array
    {
        return [
            [
                'name' => 'test1',
            ],
            [
                'name' => 'test1.service',
            ],
            [
                'name' => 'test1.timer',
            ],
            [
                'name' => 'test1.mount',
            ],
            [
                'name' => 'test1@2.service',
            ],
        ];
    }

    /**
     * @param string $name
     * @param bool   $isMultiInstance
     *
     * @test
     * @dataProvider itDetectsMultiInstanceUnitsCorrectlyDataProvider
     */
    public function itDetectsMultiInstanceUnitsCorrectly(string $name, bool $isMultiInstance)
    {
        $commandDispatcher = $this->prophesize(CommandDispatcherInterface::class);
        $unit = new UnitStub($name, $commandDispatcher->reveal());

        $this->assertEquals($isMultiInstance, $unit->isMultiInstance());
    }

    /**
     * @return array
     */
    public function itDetectsMultiInstanceUnitsCorrectlyDataProvider()
    {
        return [
            [
                'name' => 'test1@1',
                'isMultiInstance' => true,
            ],
            [
                'name' => 'test1@123.service',
                'isMultiInstance' => true,
            ],
            [
                'name' => 'test1@foo',
                'isMultiInstance' => true,
            ],
            [
                'name' => 'test1@foo.mount',
                'isMultiInstance' => true,
            ],
            [
                'name' => 'test1.service',
                'isMultiInstance' => false,
            ],
            [
                'name' => 'test1.timer',
                'isMultiInstance' => false,
            ],
        ];
    }

    /**
     * @param string $name
     * @param string $instanceName
     *
     * @test
     * @dataProvider itDetectsMultiInstanceInstanceNamesCorrectlyDataProvider
     */
    public function itDetectsMultiInstanceInstanceNamesCorrectly(string $name, ?string $instanceName)
    {
        $commandDispatcher = $this->prophesize(CommandDispatcherInterface::class);
        $unit = new UnitStub($name, $commandDispatcher->reveal());

        $this->assertEquals($instanceName, $unit->getInstanceName());
    }

    /**
     * @return array
     */
    public function itDetectsMultiInstanceInstanceNamesCorrectlyDataProvider()
    {
        return [
            [
                'name' => 'test1@1',
                'instanceName' => '1',
            ],
            [
                'name' => 'test1@123.service',
                'instanceName' => '123',
            ],
            [
                'name' => 'test1@foo',
                'instanceName' => 'foo',
            ],
            [
                'name' => 'test1@foo.mount',
                'instanceName' => 'foo',
            ],
            [
                'name' => 'test1.service',
                'instanceName' => null,
            ],
            [
                'name' => 'test1.timer',
                'instanceName' => null,
            ],
        ];
    }

    /**
     * @test
     */
    public function itShouldReturnTrueIfServiceEnabledCommandRanSuccessfully()
    {
        $command = $this->prophesize(CommandInterface::class);
        $command->getOutput()->willReturn('enabled');
        $commandDispatcher = $this->prophesize(CommandDispatcherInterface::class);
        $commandDispatcher->dispatch(Argument::cetera())->willReturn($command);

        $unit = new UnitStub(static::UNIT_NAME, $commandDispatcher->reveal());

        $this->assertTrue($unit->isEnabled());
    }

    /**
     * @test
     */
    public function itShouldRaiseAnExceptionIfServiceEnabledCommandFailed()
    {
        $commandDispatcher = $this->prophesize(CommandDispatcherInterface::class);
        $commandDispatcher->dispatch(Argument::cetera())->willThrow(CommandFailedException::class);

        $unit = new UnitStub(static::UNIT_NAME, $commandDispatcher->reveal());
        $this->expectException(CommandFailedException::class);

        $unit->isEnabled();
    }

    /**
     * @param bool $commandSuccessful
     * @param string $commandOutput
     *
     * @test
     * @dataProvider itShouldReturnFalseIfServiceEnabledCommandOutputDoesNotEqualEnabledDataProvider
     */
    public function itShouldReturnFalseIfServiceEnabledCommandOutputDoesNotEqualEnabled(
        $commandSuccessful,
        $commandOutput
    ) {
        $command = $this->prophesize(CommandInterface::class);
        $command->isSuccessful()->willReturn($commandSuccessful);
        $command->getOutput()->willReturn($commandOutput);

        $commandDispatcher = $this->prophesize(CommandDispatcherInterface::class);
        $commandDispatcher->dispatch(Argument::cetera())->willReturn($command);

        $unit = new UnitStub(static::UNIT_NAME, $commandDispatcher->reveal());

        $this->assertFalse($unit->isEnabled());
    }

    /**
     * @return array
     */
    public function itShouldReturnFalseIfServiceEnabledCommandOutputDoesNotEqualEnabledDataProvider(): array
    {
        return [
            [
                'commandSuccessful' => true,
                'commandOutput' => 'static',
            ],
            [
                'commandSuccessful' => false,
                'commandOutput' => 'static',
            ],
            [
                'commandSuccessful' => true,
                'commandOutput' => 'enable',
            ],
        ];
    }

    /**
     * @test
     */
    public function itShouldReturnTrueIfServiceActiveCommandRanSuccessfully()
    {
        $command = $this->prophesize(CommandInterface::class);
        $command->getOutput()->willReturn('active');

        $commandDispatcher = $this->prophesize(CommandDispatcherInterface::class);
        $commandDispatcher->dispatch(Argument::cetera())->willReturn($command);

        $unit = new UnitStub(static::UNIT_NAME, $commandDispatcher->reveal());

        $this->assertTrue($unit->isRunning());
    }

    /**
     * @test
     */
    public function itShouldRaiseExceptionIfServiceActiveCommandFailed()
    {
        $commandDispatcher = $this->prophesize(CommandDispatcherInterface::class);
        $commandDispatcher->dispatch(Argument::cetera())->willThrow(CommandFailedException::class);

        $unit = new UnitStub(static::UNIT_NAME, $commandDispatcher->reveal());

        $this->expectException(CommandFailedException::class);
        $unit->isRunning();
    }

    /**
     * @param bool $commandSuccessful
     * @param string $commandOutput
     *
     * @test
     * @dataProvider itShouldReturnFalseIfServiceActiveCommandOutputDoesNotEqualActiveDataProvider
     */
    public function itShouldReturnFalseIfServiceActiveCommandOutputDoesNotEqualActive(
        $commandSuccessful,
        $commandOutput
    ) {
        $command = $this->prophesize(CommandInterface::class);
        $command->isSuccessful()->willReturn($commandSuccessful);
        $command->getOutput()->willReturn($commandOutput);

        $commandDispatcher = $this->prophesize(CommandDispatcherInterface::class);
        $commandDispatcher->dispatch(Argument::cetera())->willReturn($command);

        $unit = new UnitStub(static::UNIT_NAME, $commandDispatcher->reveal());

        $this->assertFalse($unit->isRunning());
    }

    /**
     * @return array
     */
    public function itShouldReturnFalseIfServiceActiveCommandOutputDoesNotEqualActiveDataProvider(): array
    {
        return [
            [
                'commandSuccessful' => true,
                'commandOutput' => 'static',
            ],
            [
                'commandSuccessful' => false,
                'commandOutput' => 'static',
            ],
            [
                'commandSuccessful' => true,
                'commandOutput' => 'enable',
            ],
        ];
    }

    /**
     * @test
     */
    public function testIfExecuteAppendsTheUnitNameAndSuffix()
    {
        $commandStub = $this->prophesize(CommandInterface::class);
        $commandStub->isSuccessful()->willReturn(true);

        $commandDispatcherStub = $this->prophesize(CommandDispatcherInterface::class);
        $commandDispatcherStub
            ->dispatch(...['start', self::UNIT_NAME . '.' . 'stub'])
            ->willReturn($commandStub)
            ->shouldBeCalled();

        $unitStub = new UnitStub(self::UNIT_NAME, $commandDispatcherStub->reveal());
        $unitStub->start();
    }
}
