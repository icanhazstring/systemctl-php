<?php
declare(strict_types=1);

namespace SystemCtl\Tests\Unit\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use SystemCtl\Command\SymfonyCommand;
use SystemCtl\Exception\CommandFailedException;

/**
 * SymfonyCommandTest
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

        self::assertInstanceOf(SymfonyCommand::class, $command);
    }

    /**
     * @test
     */
    public function itShouldReturnOutputFromProcess()
    {
        $process = $this->prophesize(Process::class);
        $process->getOutput()->willReturn('test');

        $command = new SymfonyCommand($process->reveal());
        self::assertEquals('test', $command->getOutput());
    }

    /**
     * @test
     */
    public function itShouldReturnSuccessfulFromProcess()
    {
        $process = $this->prophesize(Process::class);
        $process->getExitCode()->willReturn(0);

        $command = new SymfonyCommand($process->reveal());
        self::assertTrue($command->isSuccessful());
    }

    /**
     * @test
     */
    public function itShouldReturnTheCommandIfCommandRanSuccessfull(): void
    {
        $process = $this->prophesize(Process::class);
        $process->run()->shouldBeCalled();
        $process->getErrorOutput()->willReturn('testError');
        $process->getExitCode()->willReturn(0);

        $command = new SymfonyCommand($process->reveal());
        self::assertEquals($command, $command->run());
    }

    /**
     * @test
     */
    public function itShouldRaiseAnExceptionIfProcessWasNotSuccessfull(): void
    {
        $process = $this->prophesize(Process::class);
        $process->run()->shouldBeCalled();
        $process->getErrorOutput()->willReturn('testError');
        $process->getExitCode()->willReturn(1);

        $command = new SymfonyCommand($process->reveal());
        $this->expectException(CommandFailedException::class);
        $this->expectExceptionMessage('testError');

        $command->run();
    }
}
