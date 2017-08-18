<?php

namespace SystemCtl\Tests\Unit\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use SystemCtl\Command\SymfonyCommand;
use SystemCtl\Exception\CommandFailedException;

/**
 * Class SymfonyCommandTest
 *
 * @package SystemCtl\Tests\Unit\Command
 * @author icanhazstring <blubb0r05+github@gmail.com>
 */
class SymfonyCommandTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldCreateValidInstance()
    {
        $process = $this->prophesize(Process::class);
        $command = new SymfonyCommand($process->reveal());

        $this->assertInstanceOf(SymfonyCommand::class, $command);
    }

    /**
     * @test
     */
    public function itShouldReturnOutputFromProcess()
    {
        $process = $this->prophesize(Process::class);
        $process->getOutput()->willReturn('test');

        $command = new SymfonyCommand($process->reveal());
        $this->assertEquals('test', $command->getOutput());
    }

    /**
     * @test
     */
    public function itShouldReturnSuccessfulFromProcess()
    {
        $process = $this->prophesize(Process::class);
        $process->isSuccessful()->willReturn(true);

        $command = new SymfonyCommand($process->reveal());
        $this->assertTrue($command->isSuccessful());
    }

    /**
     * @test
     */
    public function itShouldReturnTheCommandIfCommandRanSuccessFul()
    {
        $process = $this->prophesize(Process::class);
        $process->run()->shouldBeCalled();
        $process->getErrorOutput()->willReturn('testError');
        $process->isSuccessful()->willReturn(true);

        $command = new SymfonyCommand($process->reveal());
        $this->assertEquals($command, $command->run());
    }

    /**
     * @test
     */
    public function itShouldRaiseAnExceptionIfProcessWasNotSuccessfull()
    {
        $process = $this->prophesize(Process::class);
        $process->run()->shouldBeCalled();
        $process->getErrorOutput()->willReturn('testError');
        $process->isSuccessful()->willReturn(false);

        $command = new SymfonyCommand($process->reveal());
        $this->expectException(CommandFailedException::class);
        $this->expectExceptionMessage('testError');

        $command->run();
    }
}
