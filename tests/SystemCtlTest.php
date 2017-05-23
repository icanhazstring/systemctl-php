<?php

namespace SystemCtl\Test;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use SystemCtl\Exception\UnitTypeNotSupportedException;
use SystemCtl\Service;
use SystemCtl\SystemCtl;
use SystemCtl\UnitInterface;

class SystemCtlTest extends TestCase
{
    public function testListUnits()
    {
        $systemctl = new SystemCtl();
        $units = $systemctl->listUnits();
        $this->assertTrue(count($units) > 0);
    }

    public function testCreateUnitFromSupportedSuffixShouldWord()
    {
        $unit = SystemCtl::unitFromSuffix('service', 'SuccessService');
        $this->assertInstanceOf(UnitInterface::class, $unit);
        $this->assertInstanceOf(Service::class, $unit);
        $this->assertEquals('SuccessService', $unit->getName());
    }

    public function testCreateUnitFromUnsupportedSuffixShouldRaiseException()
    {
        $this->expectException(UnitTypeNotSupportedException::class);
        SystemCtl::unitFromSuffix('unsupported', 'FailUnit');
    }

    public function testGetServiceWithName()
    {
        $output = 'testService.service Active Running';
        $systemctl = $this->buildSystemCtlMock($output);

        $service = $systemctl->getService('testService');
        $this->assertInstanceOf(Service::class, $service);
        $this->assertEquals('testService', $service->getName());
    }

    public function testGetServices()
    {
        $output = <<<EOT
PLACEHOLDER STUFF
  superservice.service      Active running
  awesomeservice.service    Active running
  nonservice.timer          Active running
PLACEHOLDER STUFF
  
EOT;

        $systemctl = $this->buildSystemCtlMock($output);
        $services = $systemctl->getServices();

        $this->assertCount(2, $services);
    }

    /**
     * @param string $output
     * @return \PHPUnit_Framework_MockObject_MockObject|SystemCtl
     */
    private function buildSystemCtlMock($output)
    {

        $process = $this->getMockBuilder(Process::class)
            ->disableOriginalConstructor()
            ->setMethods(['getOutput'])
            ->getMock();

        $process->method('getOutput')->willReturn($output);

        $processBuilder = $this->getMockBuilder(ProcessBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(['getProcess'])
            ->getMock();

        $processBuilder->method('getProcess')->willReturn($process);

        $systemctl = $this->getMockBuilder(SystemCtl::class)
            ->setMethods(['getProcessBuilder'])
            ->getMock();

        $systemctl->method('getProcessBuilder')->willReturn($processBuilder);

        return $systemctl;
    }
}
