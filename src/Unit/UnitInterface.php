<?php

namespace SystemCtl\Unit;

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
     * Start command
     *
     * @return bool
     */
    public function start(): bool;

    /**
     * Stop command
     *
     * @return bool
     */
    public function stop(): bool;

    /**
     * Disable command
     *
     * @return bool
     */
    public function disable(): bool;

    /**
     * Reload command
     *
     * @return bool
     */
    public function reload(): bool;

    /**
     * Restart command
     *
     * @return bool
     */
    public function restart(): bool;

    /**
     * Enable command
     *
     * @return bool
     */
    public function enable(): bool;

    /**
     * Check whether unit is active
     *
     * @return bool
     */
    public function isActive(): bool;

    /**
     * Check whether unit is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool;
}
