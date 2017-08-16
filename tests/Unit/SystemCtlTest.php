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

    public function testSetTimeoutShouldChangeCommandTimeout()
    {
        $systemCtl = new SystemCtl();
        $processBuilder = $systemCtl->getProcessBuilder();
        $this->assertEquals(3, $processBuilder->getProcess()->getTimeout());

        SystemCtl::setTimeout(5);
        $processBuilder = $systemCtl->getProcessBuilder();
        $this->assertEquals(5, $processBuilder->getProcess()->getTimeout());
    }

    public function testSetBinaryShouldChangeCommand()
    {
        $systemCtl = new SystemCtl();

        $processBuilder = $systemCtl->getProcessBuilder();
        $this->assertEquals("'/bin/systemctl'", $processBuilder->getProcess()->getCommandLine());

        SystemCtl::setBinary('/usr/sbin/systemctl');
        $processBuilder = $systemCtl->getProcessBuilder();
        $this->assertEquals("'/usr/sbin/systemctl'", $processBuilder->getProcess()->getCommandLine());
    }
}
