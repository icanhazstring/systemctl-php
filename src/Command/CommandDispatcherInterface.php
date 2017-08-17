<?php

namespace SystemCtl\Command;

use SystemCtl\Exception\CommandFailedException;

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
     * Fetch output from a given command
     *
     * @param array $commands
     *
     * @return string
     *
     */
    public function fetchOutput(...$commands): string;

    /**
     * Dispatch command and return whether the command was successful or not
     *
     * @param array $commands
     *
     * @return bool
     */
    public function dispatch(...$commands): bool;
}