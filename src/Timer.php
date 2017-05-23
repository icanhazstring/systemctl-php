<?php


namespace SystemCtl;

use SystemCtl\Exception\CommandFailedException;

class Timer extends AbstractUnit
{
    protected function execute(string $command): bool
    {
        $process = $this->processBuilder
            ->setArguments([$command, $this->getName()])
            ->getProcess();

        $process->run();

        if (!$process->isSuccessful()) {
            throw CommandFailedException::fromTimer($this->getName(), $command);
        }

        return true;
    }
}
