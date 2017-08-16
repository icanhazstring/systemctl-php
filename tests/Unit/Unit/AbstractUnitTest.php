<?php

namespace SystemCtl\Tests\Unit\Unit;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

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
    private const SERVICE_NAME = 'testService';

    /**
     * @test
     * @dataProvider itShouldReturnCorrectNameDataProvider
     */
    public function itShouldReturnCorrectName(string $name)
    {
        $unit = new UnitStub($name, new ProcessBuilder());

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
        $unit = new UnitStub($name, new ProcessBuilder());
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
        $unit = new UnitStub($name, new ProcessBuilder());
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
}
