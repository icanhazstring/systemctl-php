<?php

namespace SystemCtl\Test\Utils;

use PHPUnit\Framework\TestCase;
use SystemCtl\Utils\OutputFetcher;

class OutputFetcherTest extends TestCase
{
    public function testFetchUnitNames()
    {
        $output = <<<EOT
PLACEHOLDER STUFF
  superservice.service      Active running
  awesomeservice.service    Active running
  nonservice.timer          Active running
PLACEHOLDER STUFF
  
EOT;

        $units = OutputFetcher::fetchUnitNames('service', $output);
        $this->assertCount(2, $units);
    }
}
