<?php
declare(strict_types=1);

namespace SystemCtl;

use SystemCtl\Command\CommandDispatcherInterface;
use SystemCtl\Command\SymfonyCommandDispatcher;
use SystemCtl\Exception\UnitNotFoundException;
use SystemCtl\Exception\UnitTypeNotSupportedException;
use SystemCtl\Scope\ScopeInterface;
use SystemCtl\Scope\SystemScope;
use SystemCtl\Scope\UserScope;
use SystemCtl\Template\Installer\UnitInstaller;
use SystemCtl\Template\Installer\UnitInstallerInterface;
use SystemCtl\Template\PathResolverInterface;
use SystemCtl\Template\Renderer\PlatesRenderer;
use SystemCtl\Template\UnitTemplateInterface;
use SystemCtl\Unit\AbstractUnit;
use SystemCtl\Unit\Service;
use SystemCtl\Unit\Timer;
use SystemCtl\Unit\UnitInterface;

/**
 * SystemCtl
 *
 * @package SystemCtl
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class SystemCtl
{
    /** @var string systemctl binary path */
    private static $binary = '/bin/systemctl';

    /** @var int timeout for commands */
    private static $timeout = 3;

    /** @var string */
    private static $assetPath = __DIR__ . '/../assets';

    private const INSTALL_PATHS = [
        'system' => '/etc/systemd/system/',
        'user'   => '~/.config/systemd/user/'
    ];

    /** @var PathResolverInterface */
    private $pathResolver;

    /** @var CommandDispatcherInterface */
    private $commandDispatcher;

    /** @var UnitInstallerInterface */
    private $unitInstaller;

    /** @var ScopeInterface */
    private $scope;

    /**
     * Hold scope instace with configured values
     * @var ScopeInterface
     */
    private $userScope;

    /**
     * Hold scope instance with configured values
     * @var ScopeInterface
     */
    private $systemScope;

    public const AVAILABLE_UNITS = [
        Service::UNIT,
        'socket',
        'device',
        'mount',
        'automount',
        'swap',
        'target',
        'path',
        Timer::UNIT,
        'slice',
        'scope'
    ];

    public const SUPPORTED_UNITS = [
        Service::UNIT,
        Timer::UNIT
    ];

    /**
     * Change systemctl binary
     *
     * @param string $binary
     */
    public static function setBinary(string $binary): void
    {
        self::$binary = $binary;
    }

    /**
     * Change command execution timeout
     *
     * @param int $timeout
     */
    public static function setTimeout(int $timeout): void
    {
        self::$timeout = $timeout;
    }

    /**
     * Change asset path
     *
     * @param string $assetPath
     */
    public static function setAssetPath(string $assetPath): void
    {
        self::$assetPath = $assetPath;
    }

    /**
     * List all supported units by using a given unit prefix.
     *
     * This prefix can be used with any wildcard combination.
     * E.G.:
     *  - *name*
     *  - name*
     *  - *name
     *  - *n*e*
     *
     * @param null|string $unitPrefix
     * @param string[]    $unitTypes
     *
     * @return array|string[]
     */
    public function listUnits(?string $unitPrefix = null, array $unitTypes = self::SUPPORTED_UNITS): array
    {
        $commands = ['--all', 'list-units'];

        if ($unitPrefix) {
            $commands[] = $unitPrefix;
        }

        $output = $this->getCommandDispatcher()->dispatch(...$commands)->getOutput();

        return array_reduce($unitTypes, function ($carry, $unitSuffix) use ($output) {
            $result = Utils\OutputFetcher::fetchUnitNames($unitSuffix, $output);

            return array_merge($carry, $result);
        }, []);
    }

    /**
     * Current scope this system ctl instance is running
     *
     * @return ScopeInterface
     */
    public function getScope(): ScopeInterface
    {
        if ($this->scope === null) {
            $this->scope = $this->systemScope = new SystemScope;
        }

        return $this->scope;
    }

    /**
     * Switch to user scope
     *
     * @return SystemCtl
     */
    public function user(): self
    {
        if ($this->userScope === null) {
            $this->userScope = new UserScope;
        }

        $this->scope = $this->userScope;

        return $this;
    }

    /**
     * Switch to system scope
     */
    public function system(): self
    {
        if ($this->systemScope === null) {
            $this->systemScope = new SystemScope;
        }

        $this->scope = $this->systemScope;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return Service
     */
    public function getService(string $name): Service
    {
        $unitNames = $this->listUnits($name, [Service::UNIT]);

        if (empty($unitNames)) {
            throw UnitNotFoundException::create(Service::UNIT, $name);
        }

        return new Service($unitNames[0], $this->getCommandDispatcher());
    }

    /**
     * @param null|string $unitPrefix
     *
     * @return Service[]
     */
    public function getServices(?string $unitPrefix = null): array
    {
        $units = $this->listUnits($unitPrefix, [Service::UNIT]);

        return array_map(function ($unitName) {
            return new Service($unitName, $this->getCommandDispatcher());
        }, $units);
    }

    /**
     * @param string $name
     *
     * @return Timer
     */
    public function getTimer(string $name): Timer
    {
        $unitNames = $this->listUnits($name, [Timer::UNIT]);

        if (empty($unitNames)) {
            throw UnitNotFoundException::create(Timer::UNIT, $name);
        }

        return new Timer($unitNames[0], $this->getCommandDispatcher());
    }

    /**
     * @param null|string $unitPrefix
     *
     * @return Timer[]
     */
    public function getTimers(?string $unitPrefix = null): array
    {
        $units = $this->listUnits($unitPrefix, [Timer::UNIT]);

        return array_map(function ($unitName) {
            return new Timer($unitName, $this->getCommandDispatcher());
        }, $units);
    }

    /**
     * Restart the daemon to reload specs and new units
     *
     * @return bool
     * @throws Exception\CommandFailedException
     */
    public function daemonReload(): bool
    {
        return $this->getCommandDispatcher()->dispatch('daemon-reload')->isSuccessful();
    }

    /**
     * Reset failed state of all unit so they won't be listed using listUnits
     *
     * @return bool
     * @throws Exception\CommandFailedException
     */
    public function resetFailed(): bool
    {
        return $this->getCommandDispatcher()->dispatch('reset-failed')->isSuccessful();
    }

    /**
     * @return CommandDispatcherInterface
     */
    public function getCommandDispatcher(): CommandDispatcherInterface
    {
        if ($this->commandDispatcher === null) {
            $this->commandDispatcher = (new SymfonyCommandDispatcher)
                ->setTimeout(self::$timeout)
                ->setBinary(self::$binary);
        }

        $this->commandDispatcher->setArguments([$this->getScope()->getArgument()]);

        return $this->commandDispatcher;
    }

    /**
     * @return PathResolverInterface
     */
    public function getPathResolver(): PathResolverInterface
    {
        return $this->pathResolver;
    }

    /**
     * @param PathResolverInterface $pathResolver
     * @return SystemCtl
     */
    public function setPathResolver(PathResolverInterface $pathResolver): SystemCtl
    {
        $this->pathResolver = $pathResolver;

        return $this;
    }

    /**
     * @param CommandDispatcherInterface $dispatcher
     *
     * @return SystemCtl
     */
    public function setCommandDispatcher(CommandDispatcherInterface $dispatcher): SystemCtl
    {
        $this->commandDispatcher = $dispatcher
            ->setTimeout(self::$timeout)
            ->setBinary(self::$binary);

        return $this;
    }

    /**
     * @return UnitInstallerInterface
     */
    public function getUnitInstaller(): UnitInstallerInterface
    {
        if ($this->unitInstaller === null) {
            $this->unitInstaller = (new UnitInstaller)
                ->setPath(self::INSTALL_PATHS[$this->getScope()->getName()])
                ->setRenderer(new PlatesRenderer(self::$assetPath));
        }

        return $this->unitInstaller;
    }

    /**
     * Set the unit installer
     *
     * @param UnitInstallerInterface $unitInstaller
     *
     * @return SystemCtl
     */
    public function setUnitInstaller(UnitInstallerInterface $unitInstaller): self
    {
        $this->unitInstaller = $unitInstaller;

        return $this;
    }

    /**
     * Install a given template, reload the daemon and return the freshly installed unit.
     *
     * @param UnitTemplateInterface $unitTemplate
     * @param bool                  $overwrite
     *
     * @return UnitInterface
     */
    public function install(UnitTemplateInterface $unitTemplate, bool $overwrite = false): UnitInterface
    {
        $unitSuffix = $unitTemplate->getUnitSuffix();
        $unitName = $unitTemplate->getUnitName();

        if (!\in_array($unitSuffix, self::SUPPORTED_UNITS, true)) {
            throw UnitTypeNotSupportedException::create($unitSuffix);
        }

        // TODO: set path to unit installer with current scope
        $this->getUnitInstaller()->install($unitTemplate, $overwrite);

        $unit = AbstractUnit::byType($unitSuffix, $unitName, $this->getCommandDispatcher());
        $unit->enable();

        $this->daemonReload();

        return $unit;
    }
}
