<?php

namespace SystemCtl\Command;

use SystemCtl\Exception\CommandFailedException;

/**
 * Interface CommandDispatcherInterface
 *
 * @package SystemCtl\Command
 */
interface CommandDispatcherInterface
{
    /**
     * Timeout after which the dispatcher failes the execution
     *
     * @param int $timeout
     *
     * @return CommandDispatcherInterface
     */
    public function setTimeout(int $timeout): CommandDispatcherInterface;

    /**
     * Set basic binary to dispatch
     *
     * @param string $binary
     *
     * @return CommandDispatcherInterface
     */
    public function setBinary(string $binary): CommandDispatcherInterface;

    /**
     * Dispatch given commands against implementers logic and creating a new command
     * to read results
     *
     * @param array $commands
     *
     * @return CommandInterface
     * @throws CommandFailedException
     */
    public function dispatch(...$commands): CommandInterface;
}
