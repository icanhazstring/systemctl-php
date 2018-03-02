<?php
declare(strict_types=1);

namespace SystemCtl\Command;

use SystemCtl\Exception\CommandFailedException;

/**
 * CommandDispatcherInterface
 *
 * @package SystemCtl\Command
 * @author  icanhazstring <blubb0r05+github@gmail.com>
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
     * Set additional arguments to be passed to dispatch
     *
     * @param string[] $arguments
     *
     * @return CommandDispatcherInterface
     */
    public function setArguments(array $arguments): CommandDispatcherInterface;

    /**
     * Dispatch given commands against implementers logic and creating a new command
     * to read results
     *
     * @param string ...$commands
     *
     * @return CommandInterface
     * @throws CommandFailedException
     */
    public function dispatch(string ...$commands): CommandInterface;
}
