<?php

namespace SystemCtl\Test\Unit;

use PHPUnit\Framework\TestCase;
use SystemCtl\SystemCtl;
use SystemCtl\Unit\Service;
use SystemCtl\Unit\Timer;

class SystemCtlTest extends TestCase
{
    public function testGetServiceWithName()
    {
        $systemctl = new SystemCtl();

        $service = $systemctl->getService('testService');
        $this->assertInstanceOf(Service::class, $service);
        $this->assertEquals('testService', $service->getName());
    }

    public function testGetTimerWithName()
    {
        $systemctl = new SystemCtl();

        $timer = $systemctl->getTimer('testTimer');
        $this->assertInstanceOf(Timer::class, $timer);
        $this->assertEquals('testTimer', $timer->getName());
    }
}
