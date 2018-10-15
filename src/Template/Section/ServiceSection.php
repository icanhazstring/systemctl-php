<?php
declare(strict_types=1);

namespace SystemCtl\Template\Section;

/**
 * ServiceSection
 *
 * @method ServiceSection setType(string $type)               Configures the unit process startup type
 * @method ServiceSection setEnvironment(array $env)          Specify a list of environment variables
 * @method ServiceSection setEnvironmentFile(string $envFile) Specifiy a file with environment variables
 * @method ServiceSection setExecStart(string $execStart)     Specifies commands or scripts to be executed when started
 * @method ServiceSection setExecStop(string $execStop)       Specifies commands or scripts to be executed when stopped
 * @method ServiceSection setExecReload(string $execReload)   Specifies commands or scripts to be executed when reloaded
 * @method ServiceSection setRestart(string $restart)         Restart service of the process exists
 * @method ServiceSection setRemainsAfterExit(bool $rae)      Consider service as active when all processes existed
 * @method ServiceSection setPIDFile(string $pidFile)         Absolute file name pointing to the PID file of this daemon
 *
 * @method string getType()
 * @method array getEnvironment()
 * @method string getEnvironmentFile()
 * @method string getExecStart()
 * @method string getExecStop()
 * @method string getExecReload()
 * @method string getRestart()
 * @method bool getRemainsAfterExit()
 * @method string getPIDFile()
 *
 * @package SystemCtl\Template\Section
 * @author icanhazstring <blubb0r05+github@gmail.com>
 */
class ServiceSection extends AbstractSection
{
    protected const PROPERTIES = [
        'Type',
        'Environment',
        'EnvironmentFile',
        'ExecStart',
        'ExecStop',
        'ExecReload',
        'Restart',
        'RemainsAfterExit',
        'PIDFile'
    ];

    /**
     * The default value. The process started with ExecStart is the main process of the service.
     */
    public const TYPE_SIMPLE = 'simple';
    /**
     * The process started with ExecStart spawns a child process that becomes the main process of the service.
     * The parent process exits when the startup is complete.
     */
    public const TYPE_FORKING = 'forking';
    /**
     * This type is similar to simple, but the process exits before starting consequent units.
     */
    public const TYPE_ONESHOT = 'oneshot';
    /**
     * This type is similar to simple, but consequent units are started only after the main process gains a D-Bus name.
     */
    public const TYPE_DBUS = 'dbus';
    /**
     * This type is similar to simple, but consequent units are started only
     * after a notification message is sent via the sd_notify() function.
     */
    public const TYPE_NOTIFY = 'notify';
    /**
     * Similar to simple, the actual execution of the service binary is delayed until
     * all jobs are finished, which avoids mixing the status output with shell output of services.
     */
    public const TYPE_IDLE = 'idle';

    /**
     * List of all possible unit types
     */
    public const TYPES = [
        self::TYPE_SIMPLE,
        self::TYPE_FORKING,
        self::TYPE_ONESHOT,
        self::TYPE_DBUS,
        self::TYPE_NOTIFY,
        self::TYPE_IDLE
    ];

    /**
     * on-success: it will be restarted only when the service process exits cleanly.
     * In this context, a clean exit means an exit code of 0, or one of the signals SIGHUP, SIGINT, SIGTERM or SIGPIPE,
     * and additionally, exit statuses and signals specified in SuccessExitStatus=
     */
    public const RESTART_ON_SUCCESS = 'on-success';
    /**
     * on-failure: the service will be restarted when the process exits with a non-zero exit code, is terminated by a
     * signal (including on core dump, but excluding the aforementioned four signals), when an operation
     * (such as service reload) times out, and when the configured watchdog timeout is triggered.
     */
    public const RESTART_ON_FAILURE = 'on-failure';
    /**
     * on-abnormal: the service will be restarted when the process is terminated by a signal (including on
     * core dump, excluding the aforementioned four signals), when an operation times out, or when the watchdog
     * timeout is triggered.
     */
    public const RESTART_ON_ABNORMAL = 'on-abnormal';
    /**
     * on-abort: the service will be restarted only if the service process exits due to an uncaught signal
     * not specified as a clean exit status
     */
    public const RESTART_ON_ABORT = 'on-abort';
    /**
     * If set to on-watchdog, the service will be restarted only if the watchdog timeout for the service expires
     */
    public const RESTART_ON_WATCHDOG = 'on-watchdog';
    /**
     * always: the service will be restarted regardless of whether it exited cleanly or not, got terminated
     * abnormally by a signal, or hit a timeout.
     */
    public const RESTART_ALWAYS = 'always';

    public const RESTART = [
        self::RESTART_ON_SUCCESS,
        self::RESTART_ON_FAILURE,
        self::RESTART_ON_ABNORMAL,
        self::RESTART_ON_ABORT,
        self::RESTART_ON_WATCHDOG,
        self::RESTART_ALWAYS
    ];

}
