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
     * @param bool $raise Raise exception on failure instead of process result
     * @return bool
     */
    public function start(bool $raise = false): bool;

    /**
     * Stop command
     *
     * @param bool $raise Raise exception on failure instead of process result
     * @return bool
     */
    public function stop(bool $raise = false): bool;

    /**
     * Disable command
     *
     * @param bool $raise Raise exception on failure instead of process result
     * @return bool
     */
    public function disable(bool $raise = false): bool;

    /**
     * Reload command
     *
     * @param bool $raise Raise exception on failure instead of process result
     * @return bool
     */
    public function reload(bool $raise = false): bool;

    /**
     * Restart command
     *
     * @param bool $raise Raise exception on failure instead of process result
     * @return bool
     */
    public function restart(bool $raise = false): bool;

    /**
     * Enable command
     *
     * @param bool $raise Raise exception on failure instead of process result
     * @return bool
     */
    public function enable(bool $raise = false): bool;
}
