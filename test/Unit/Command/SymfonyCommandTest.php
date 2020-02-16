<?php

namespace icanhazstring\SystemCtl\Test\Unit\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use icanhazstring\SystemCtl\Command\SymfonyCommand;
use icanhazstring\SystemCtl\Exception\CommandFailedException;

/**
 * Class SymfonyCommandTest
 *
 * @package icanhazstring\SystemCtl\Test\Unit\Command
 * @author icanhazstring <blubb0r05+github@gmail.com>
 */
class SymfonyCommandTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldCreateValidInstance(): void
    {
        $process = $this->prophesize(Process::class);
        $command = new SymfonyCommand($process->reveal());

        $this->assertInstanceOf(SymfonyCommand::class, $command);
    }

    /**
     * @test
     */
    public function itShouldReturnOutputFromProcess(): void
    {
        $process = $this->prophesize(Process::class);
        $process->getOutput()->willReturn('test');

        $command = new SymfonyCommand($process->reveal());
        $this->assertEquals('test', $command->getOutput());
    }

    /**
     * @test
     */
    public function itShouldReturnSuccessfulFromProcess(): void
    {
        $process = $this->prophesize(Process::class);
        $process->isSuccessful()->willReturn(true);

        $command = new SymfonyCommand($process->reveal());
        $this->assertTrue($command->isSuccessful());
    }

    /**
     * @test
     */
    public function itShouldReturnTheCommandIfCommandRanSuccessFul(): void
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
    public function itShouldRaiseAnExceptionIfProcessWasNotSuccessfull(): void
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
