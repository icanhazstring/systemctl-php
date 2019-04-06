<?php
declare(strict_types=1);

namespace SystemCtl\Unit;

use SystemCtl\Command\CommandInterface;
use SystemCtl\Exception\CommandFailedException;

/**
 * UnitInterface for handling single units
 *
 * @package SystemCtl\Unit
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
     * Execute certain commands against the CommandDispatcher
     *
     * @param string ...$commands
     *
     * @return CommandInterface
     */
    public function execute(string ...$commands): CommandInterface;

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

    /**
     * Check whether unit is active
     *
     * @return bool
     */
    public function isActive(): bool;

    /**
     * Get the raw (text) output of the `is-active` command.
     *
     * @return string
     */
    public function isActiveRaw(): string;

    /**
     * Check whether unit is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Get the raw (text) output of the `is-enabled` command.
     *
     * @return string
     */
    public function isEnabledRaw(): string;
}
