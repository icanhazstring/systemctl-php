<?php

namespace SystemCtl\Unit;

use SystemCtl\Exception\CommandFailedException;

class Timer extends AbstractUnit
{
    /**
     * @var string
     */
    public const UNIT = 'timer';

    /**
     * @inheritdoc
     *
     * @throws \SystemCtl\Exception\CommandFailedException
     */
    protected function execute(string $command): bool
    {
        $process = $this->runCommandAgainstService($command);

        if (!$process->isSuccessful()) {
            throw CommandFailedException::fromTimer($this->getName(), $command);
        }

        return true;
    }
}
