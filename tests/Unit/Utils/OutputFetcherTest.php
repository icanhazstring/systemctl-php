<?php

namespace SystemCtl\Tests\Unit\Utils;

use PHPUnit\Framework\TestCase;
use SystemCtl\Utils\OutputFetcher;

/**
 * Class OutputFetcherTest
 *
 * @package SystemCtl\Test\Unit\Utils
 */
class OutputFetcherTest extends TestCase
{
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
    ) {
        $units = OutputFetcher::fetchUnitNames($suffix, $output);
        $this->assertCount($expectedAmount, $units);
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
  superservice.mount           active running
  awesomeservice.mount         active running
  nonservice.timer             active running
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
    public function itOnlyExtractsTheUnitNames(string $output, string $suffix, array $expectedUnitNames)
    {
        $units = OutputFetcher::fetchUnitNames($suffix, $output);
        $this->assertEquals($expectedUnitNames, $units);
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
  super.mount        Active running
  awesome.mount      Active running
  nonservice.timer          Active running
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
                'suffix' => 'mount',
                'units'  => [
                    'super',
                    'awesome',
                ],
            ],
        ];
    }
}
