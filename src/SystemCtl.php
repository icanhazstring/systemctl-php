<?php
declare(strict_types=1);

namespace SystemCtl;

use SystemCtl\Command\CommandDispatcherInterface;
use SystemCtl\Command\SymfonyCommandDispatcher;
use SystemCtl\Exception\UnitNotFoundException;
use SystemCtl\Exception\UnitTypeNotSupportedException;
use SystemCtl\Template\AbstractUnitTemplate;
use SystemCtl\Template\Installer\UnitInstaller;
use SystemCtl\Template\Installer\UnitInstallerInterface;
use SystemCtl\Template\Renderer\PlatesRenderer;
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

    /** @var string install path for new units */
    private static $installPath = '/etc/systemd/system';

    /** @var string */
    private static $assetPath = __DIR__ . '/../assets';

    /** @var CommandDispatcherInterface */
    private $commandDispatcher;

    /** @var UnitInstallerInterface */
    private $unitInstaller;

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
        Timer::UNIT,
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
     * Change install path for units
     *
     * @param string $installPath
     */
    public static function setInstallPath(string $installPath): void
    {
        self::$installPath = $installPath;
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
     * List all supported units
     *
     * @param null|string $unitPrefix
     * @param string[]    $unitTypes
     *
     * @return array|string[]
     */
    public function listUnits(?string $unitPrefix = null, array $unitTypes = self::SUPPORTED_UNITS): array
    {
        $commands = ['list-units'];

        if ($unitPrefix) {
            $commands[] = $unitPrefix . '*';
        }

        $output = $this->getCommandDispatcher()->dispatch(...$commands)->getOutput();

        return array_reduce($unitTypes, function ($carry, $unitSuffix) use ($output) {
            $result = Utils\OutputFetcher::fetchUnitNames($unitSuffix, $output);

            return array_merge($carry, $result);
        }, []);
    }

    /**
     * @param string $name
     *
     * @return Service
     */
    public function getService(string $name): Service
    {
        $units = $this->listUnits($name, [Service::UNIT]);

        $unitName = $this->searchForUnitInUnits($name, $units);

        if (is_null($unitName)) {
            throw UnitNotFoundException::create(Service::UNIT, $name);
        }

        return new Service($unitName, $this->getCommandDispatcher());
    }

    /**
     * @param string   $unitName
     * @param string[] $units
     *
     * @return null|string
     */
    protected function searchForUnitInUnits(string $unitName, array $units): ?string
    {
        foreach ($units as $unit) {
            if ($unit === $unitName) {
                return $unit;
            }
        }

        return null;
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
        $units = $this->listUnits($name, [Timer::UNIT]);

        $unitName = $this->searchForUnitInUnits($name, $units);

        if (is_null($unitName)) {
            throw UnitNotFoundException::create(Timer::UNIT, $name);
        }

        return new Timer($unitName, $this->getCommandDispatcher());
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
     */
    public function daemonReload(): bool
    {
        return $this->getCommandDispatcher()->dispatch('daemon-reload')->isSuccessful();
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

        return $this->commandDispatcher;
    }

    /**
     * @param CommandDispatcherInterface $dispatcher
     *
     * @return SystemCtl
     */
    public function setCommandDispatcher(CommandDispatcherInterface $dispatcher)
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
                ->setPath(self::$installPath)
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
     * @param AbstractUnitTemplate $unitTemplate
     *
     * @return UnitInterface
     * @throws UnitTypeNotSupportedException
     */
    public function install(AbstractUnitTemplate $unitTemplate): UnitInterface
    {
        $unitSuffix = $unitTemplate->getUnitSuffix();
        $unitName = $unitTemplate->getUnitName();

        if (!in_array($unitSuffix, self::SUPPORTED_UNITS)) {
            throw UnitTypeNotSupportedException::create($unitSuffix);
        }

        $this->getUnitInstaller()->install($unitTemplate);
        $this->daemonReload();

        return $this->{'get' . ucfirst($unitSuffix)}($unitName);
    }
}
