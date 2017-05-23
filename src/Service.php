<?php


namespace SystemCtl;

use SystemCtl\Exception\CommandFailedException;

class Service extends AbstractUnit
{
    protected function execute(string $command): bool
    {
        $process = $this->processBuilder
            ->setArguments([$command, $this->getName()])
            ->getProcess();

        $process->run();

        if (!$process->isSuccessful()) {
            throw CommandFailedException::fromService($this->getName(), $command);
        }

        return true;
    }
}
