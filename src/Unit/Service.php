<?php

namespace SystemCtl\Unit;

use SystemCtl\Exception\CommandFailedException;

class Service extends AbstractUnit
{
    /**
     * @var string
     */
    public const UNIT = 'service';

    /**
     * @inheritdoc
     *
     * @throws \SystemCtl\Exception\CommandFailedException
     */
    protected function execute(string $command): bool
    {
        $process = $this->runCommandAgainstService($command);

        if (!$process->isSuccessful()) {
            throw CommandFailedException::fromService($this->getName(), $command);
        }

        return true;
    }
}
