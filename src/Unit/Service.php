<?php


namespace SystemCtl\Unit;

use SystemCtl\Exception\CommandFailedException;

class Service extends AbstractUnit
{
    public const UNIT = 'service';

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
