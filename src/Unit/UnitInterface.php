<?php

namespace SystemCtl\Unit;

use SystemCtl\Command\CommandInterface;
use SystemCtl\Exception\CommandFailedException;

/**
 * UnitInterface for handling single units
 *
 * @package SystemCtl
 */
interface UnitInterface
{
    /**
     * Get the units full name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Whether the unit has multiple instances or not
     *
     * @return bool
     */
    public function isMultiInstance(): bool;

    /**
     * Get instance name
     *
     * @return null|string
     */
    public function getInstanceName(): ?string;

    /**
     * @return CommandInterface
     */
    public function execute(): CommandInterface;

    /**
     * Start command
     *
     * @return bool
     * @throws CommandFailedException
     */
    public function start(): bool;

    /**
     * Stop command
     *
     * @return bool
     * @throws CommandFailedException
     */
    public function stop(): bool;

    /**
     * Disable command
     *
     * @return bool
     * @throws CommandFailedException
     */
    public function disable(): bool;

    /**
     * Reload command
     *
     * @return bool
     * @throws CommandFailedException
     */
    public function reload(): bool;

    /**
     * Restart command
     *
     * @return bool
     * @throws CommandFailedException
     */
    public function restart(): bool;

    /**
     * Enable command
     *
     * @return bool
     * @throws CommandFailedException
     */
    public function enable(): bool;

    // @todo: Add missing methods from AbstractUnit.
}
