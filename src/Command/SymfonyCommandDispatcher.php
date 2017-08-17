<?php

namespace SystemCtl\Command;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use SystemCtl\Exception\CommandFailedException;

/**
 * Class SymfonyCommandDispatcher
 *
 * @package SystemCtl\Command
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class SymfonyCommandDispatcher implements CommandDispatcherInterface
{
    private $binary;
    private $timetout;

    /** @var Process */
    private $lastCommand;

    /**
     * @inheritdoc
     */
    public function setBinary(string $binary): CommandDispatcherInterface
    {
        $this->binary = $binary;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setTimeout(int $timeout): CommandDispatcherInterface
    {
        $this->timetout = $timeout;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function fetchOutput(...$commands): string
    {
        $process = $this->run($commands);

        if (!$process->isSuccessful()) {
            throw new CommandFailedException($process->getErrorOutput());
        }

        return $process->getOutput();
    }

    /**
     * @inheritDoc
     */
    public function dispatch(...$commands): bool
    {
        $process = $this->run($commands);

        if (!$process->isSuccessful()) {
            throw new CommandFailedException($process->getErrorOutput());
        }

        return $process->isSuccessful();
    }

    private function run(...$commands): Process
    {
        $processBuilder = new ProcessBuilder();
        $processBuilder->setPrefix($this->binary);
        $processBuilder->setTimeout($this->timetout);
        $processBuilder->setArguments(...$commands);

        $process = $processBuilder->getProcess();
        $process->run();

        return $process;
    }
}