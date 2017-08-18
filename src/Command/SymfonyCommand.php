<?php

namespace SystemCtl\Command;

use Symfony\Component\Process\Process;
use SystemCtl\Exception\CommandFailedException;

/**
 * Class SymfonyCommand
 *
 * @package SystemCtl\Command
 * @author icanhazstring <blubb0r05+github@gmail.com>
 */
class SymfonyCommand implements CommandInterface
{
    /** @var Process */
    private $process;

    public function __construct(Process $process)
    {
        $this->process = $process;
    }

    /**
     * @inheritdoc
     */
    public function getOutput(): string
    {
        return $this->process->getOutput();
    }

    /**
     * @inheritdoc
     */
    public function isSuccessful(): bool
    {
        return $this->process->isSuccessful();
    }

    /**
     * @inheritdoc
     */
    public function run(): CommandInterface
    {
        $this->process->run();

        if (!$this->process->isSuccessful()) {
            throw new CommandFailedException($this->process->getErrorOutput());
        }

        return $this;
    }
}
