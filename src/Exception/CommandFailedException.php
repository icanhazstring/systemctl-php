<?php

namespace SystemCtl\Exception;

class CommandFailedException extends \Exception
{
    /**
     * @param $name
     * @param $command
     *
     * @return CommandFailedException
     */
    public static function fromService($name, $command): CommandFailedException
    {
        return new self(sprintf('Failed to %s service %s', $command, $name));
    }

    /**
     * @param $name
     * @param $command
     *
     * @return CommandFailedException
     */
    public static function fromTimer($name, $command): CommandFailedException
    {
        return new self(sprintf('Failed to %s timer %s', $command, $name));
    }
}
