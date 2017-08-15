<?php

namespace SystemCtl\Test\Unit\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\ProcessBuilder;
use SystemCtl\Unit\Service;

class UnitTest extends TestCase
{
    public function testMultiInstanceUnit()
    {
        $unit = new Service('service@1', new ProcessBuilder());
        $this->assertEquals('service@1', $unit->getName());
        $this->assertTrue($unit->isMultiInstance());
        $this->assertEquals('1', $unit->getInstanceName());
    }
}
