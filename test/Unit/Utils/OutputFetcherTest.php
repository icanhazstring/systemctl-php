<?php

namespace icanhazstring\SystemCtl\Test\Unit\Utils;

use PHPUnit\Framework\TestCase;
use icanhazstring\SystemCtl\Utils\OutputFetcher;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * Class OutputFetcherTest
 *
 * @package icanhazstring\SystemCtl\Test\Unit\Utils
 */
class OutputFetcherTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @param string $output
     * @param string $suffix
     * @param int    $expectedAmount
     *
     * @test
     * @dataProvider itDeterminesTheCorrectAmountOfUnitsDataProvider
     */
    public function itDeterminesTheCorrectAmountOfUnits(
        string $output,
        string $suffix,
        int $expectedAmount
    ): void {
        $units = OutputFetcher::fetchUnitNames($suffix, $output);
        self::assertCount($expectedAmount, $units);
    }

    /**
     * @return array
     */
    public function itDeterminesTheCorrectAmountOfUnitsDataProvider(): array
    {
        $output = <<<OUTPUT
  superservice.service         active running
  awesomeservice.service       active running
  nonservice.timer             active running
  nonservice.socket            active running
  nonservice.device            active running
  nonservice.scope             active running
  nonservice.slice             active running
  superservice.mount           active running
  awesomeservice.mount         active running
  nonservice.timer             active running
  nonservice.socket            active running
  nonservice.device            active running
  nonservice.scope             active running
  nonservice.slice             active running
  superservice.service         active running
  awesomeservice.service       active running
● failed-service@foo.service   loaded failed failed
OUTPUT;

        return [
            [
                'output' => $output,
                'suffix' => 'service',
                'amount' => 5,
            ],
            [
                'output' => $output,
                'suffix' => 'timer',
                'amount' => 2,
            ],
            [
                'output' => $output,
                'suffix' => 'socket',
                'amount' => 2,
            ],
            [
                'output' => $output,
                'suffix' => 'device',
                'amount' => 2,
            ],
            [
                'output' => $output,
                'suffix' => 'scope',
                'amount' => 2,
            ],
            [
                'output' => $output,
                'suffix' => 'slice',
                'amount' => 2,
            ],
            [
                'output' => $output,
                'suffix' => 'mount',
                'amount' => 2,
            ],
            [
                'output' => $output,
                'suffix' => 'notThere',
                'amount' => 0,
            ],
        ];
    }

    /**
     * @param string $output
     * @param string $suffix
     * @param array $expectedUnitNames
     *
     * @test
     * @dataProvider itOnlyExtractsTheUnitNamesDataProvider
     */
    public function itOnlyExtractsTheUnitNames(string $output, string $suffix, array $expectedUnitNames): void
    {
        $units = OutputFetcher::fetchUnitNames($suffix, $output);
        self::assertEquals($expectedUnitNames, $units);
    }

    /**
     * @return array
     */
    public function itOnlyExtractsTheUnitNamesDataProvider(): array
    {
        $output = <<<OUTPUT
  foo.service      Active running
  foo-bar.service    Active running
  a-timer.timer          Active running
  a-socket.socket          Active running
  a-device.device          Active running
  a-scope.scope          Active running
  a-slice.slice          Active running
  super.mount        Active running
  awesome.mount      Active running
  nonservice.timer          Active running
  nonservice.socket          Active running
  nonservice.device          Active running
  nonservice.scope          Active running
  nonservice.slice          Active running
  instance-service@1.service      Active running
  instance-service@foo.service    Active running
● failed-service@foo.service loaded failed failed
OUTPUT;

        return [
            [
                'output' => $output,
                'suffix' => 'service',
                'units'  => [
                    'foo',
                    'foo-bar',
                    'instance-service@1',
                    'instance-service@foo',
                    'failed-service@foo'
                ],
            ],
            [
                'output' => $output,
                'suffix' => 'timer',
                'units'  => [
                    'a-timer',
                    'nonservice',
                ],
            ],
            [
                'output' => $output,
                'suffix' => 'device',
                'units'  => [
                    'a-device',
                    'nonservice',
                ],
            ],
            [
                'output' => $output,
                'suffix' => 'socket',
                'units'  => [
                    'a-socket',
                    'nonservice',
                ],
            ],
            [
                'output' => $output,
                'suffix' => 'scope',
                'units'  => [
                    'a-scope',
                    'nonservice',
                ],
            ],
            [
                'output' => $output,
                'suffix' => 'slice',
                'units'  => [
                    'a-slice',
                    'nonservice',
                ],
            ],
            [
                'output' => $output,
                'suffix' => 'mount',
                'units'  => [
                    'super',
                    'awesome',
                ],
            ],
        ];
    }
}
