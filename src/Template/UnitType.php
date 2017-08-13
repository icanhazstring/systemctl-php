<?php

namespace SystemCtl\Template;

/**
 * UnitType
 *
 * Configures the unit process startup type that affects the functionality of ExecStart and related options
 *
 * @package SystemCtl
 * @author icanhazstring <blubb0r05+github@gmail.com>
 */
class UnitType
{
    /**
     * The default value. The process started with ExecStart is the main process of the service.
     */
    public const SIMPLE = 'simple';
    /**
     * The process started with ExecStart spawns a child process that becomes the main process of the service.
     * The parent process exits when the startup is complete.
     */
    public const FORKING = 'forking';
    /**
     * This type is similar to simple, but the process exits before starting consequent units.
     */
    public const ONESHOT = 'oneshot';
    /**
     * This type is similar to simple, but consequent units are started only after the main process gains a D-Bus name.
     */
    public const DBUS = 'dbus';
    /**
     * This type is similar to simple, but consequent units are started only
     * after a notification message is sent via the sd_notify() function.
     */
    public const NOTIFY = 'notify';
    /**
     * Similar to simple, the actual execution of the service binary is delayed until
     * all jobs are finished, which avoids mixing the status output with shell output of services.
     */
    public const IDLE = 'idle';

    /**
     * List of all possible unit types
     */
    public const TYPES = [
        self::SIMPLE,
        self::FORKING,
        self::ONESHOT,
        self::DBUS,
        self::NOTIFY,
        self::IDLE
    ];
}
