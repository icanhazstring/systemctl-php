<?php

namespace SystemCtl;

use SystemCtl\Command\CommandDispatcherInterface;
use SystemCtl\Command\SymfonyCommandDispatcher;
use SystemCtl\Exception\UnitNotFoundException;
use SystemCtl\Exception\UnitTypeNotSupportedException;
use SystemCtl\Unit\Service;
use SystemCtl\Unit\Timer;
use SystemCtl\Unit\UnitInterface;

/**
 * Class SystemCtl
 *
 * @package SystemCtl
 */
class SystemCtl
{
    /** @var string systemctl binary path */
    private static $binary = '/bin/systemctl';

    /** @var int timeout for commands */
    private static $timeout = 3;

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

    private $commandDispatcher;

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
     * @param string $unitSuffix
     * @param string $unitName
     *
     * @return UnitInterface
     * @throws UnitTypeNotSupportedException
     * @deprecated This static method is deprecated, please refer to a specifc get method for a unit
     */
    public static function unitFromSuffix(string $unitSuffix, string $unitName): UnitInterface
    {
        $unitClass = 'SystemCtl\\Unit\\' . ucfirst($unitSuffix);

        if (!class_exists($unitClass)) {
            throw new UnitTypeNotSupportedException('Unit type ' . $unitSuffix . ' not supported');
        }

        $commandDispatcher = (new SymfonyCommandDispatcher)
            ->setTimeout(self::$timeout)
            ->setBinary(self::$binary);

        return new $unitClass($unitName, $commandDispatcher);
    }

    /**
     * List all supported units
     *
     * @param null|string $unitPrefix
     * @param string[]    $unitTypes
     *
     * @return array|\string[]
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
     * @param string $unitName
     * @param array[] $units
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
}
