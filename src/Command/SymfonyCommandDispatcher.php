<?php

namespace icanhazstring\SystemCtl\Command;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Class SymfonyCommandDispatcher
 *
 * @package icanhazstring\SystemCtl\Command
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class SymfonyCommandDispatcher implements CommandDispatcherInterface
{
    /** @var string */
    private $binary;
    /** @var int */
    private $timeout;

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
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(...$commands): CommandInterface
    {
        $process = new Process(array_merge([$this->binary], $commands));
        $process->setTimeout($this->timeout);

        $process = new SymfonyCommand($process);

        return $process->run();
    }
}
