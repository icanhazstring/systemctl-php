<?php

namespace icanhazstring\SystemCtl;

use icanhazstring\SystemCtl\Command\CommandDispatcherInterface;
use icanhazstring\SystemCtl\Command\SymfonyCommandDispatcher;
use icanhazstring\SystemCtl\Exception\UnitNotFoundException;
use icanhazstring\SystemCtl\Exception\UnitTypeNotSupportedException;
use icanhazstring\SystemCtl\Unit\Service;
use icanhazstring\SystemCtl\Unit\Timer;
use icanhazstring\SystemCtl\Unit\Socket;
use icanhazstring\SystemCtl\Unit\Scope;
use icanhazstring\SystemCtl\Unit\UnitInterface;

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

    /** @var CommandDispatcherInterface */
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
        $unitClass = 'icanhazstring\SystemCtl\\Unit\\' . ucfirst($unitSuffix);

        if (!class_exists($unitClass)) {
            throw new UnitTypeNotSupportedException('Unit type ' . $unitSuffix . ' not supported');
        }

        $commandDispatcher = (new SymfonyCommandDispatcher())
            ->setTimeout(self::$timeout)
            ->setBinary(self::$binary);

        return new $unitClass($unitName, $commandDispatcher);
    }

    /**
     * List all supported units
     *
     * @param null|string $unitPrefix
     * @param string[] $unitTypes
     *
     * @return array|string[]
     * @throws Exception\CommandFailedException
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
     * @throws Exception\CommandFailedException
     */
    public function getService(string $name): Service
    {
        $units = $this->listUnits($name, [Service::UNIT]);

        $unitName = $this->searchForUnitInUnits($name, $units);

        if ($unitName === null) {
            throw UnitNotFoundException::create(Service::UNIT, $name);
        }

        return new Service($unitName, $this->getCommandDispatcher());
    }

    /**
     * @param string $unitName
     * @param array<string>|string[] $units
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
     * @param string $name
     *
     * @return Socket
     */
    public function getSocket(string $name): Socket
    {
        $units = $this->listUnits($name, [Socket::UNIT]);

        $unitName = $this->searchForUnitInUnits($name, $units);

        if (is_null($unitName)) {
            throw UnitNotFoundException::create(Socket::UNIT, $name);
        }

        return new Socket($unitName, $this->getCommandDispatcher());
    }

    /**
     * @param null|string $unitPrefix
     *
     * @return Socket[]
     */
    public function getSockets(?string $unitPrefix = null): array
    {
        $units = $this->listUnits($unitPrefix, [Socket::UNIT]);

        return array_map(function ($unitName) {
            return new Socket($unitName, $this->getCommandDispatcher());
        }, $units);
    }

    /**
     * @param string $name
     *
     * @return Scope
     */
    public function getScope(string $name): Scope
    {
        $units = $this->listUnits($name, [Scope::UNIT]);

        $unitName = $this->searchForUnitInUnits($name, $units);

        if (is_null($unitName)) {
            throw UnitNotFoundException::create(Scope::UNIT, $name);
        }

        return new Scope($unitName, $this->getCommandDispatcher());
    }

    /**
     * @param null|string $unitPrefix
     *
     * @return Scope[]
     */
    public function getScopes(?string $unitPrefix = null): array
    {
        $units = $this->listUnits($unitPrefix, [Scope::UNIT]);

        return array_map(function ($unitName) {
            return new Scope($unitName, $this->getCommandDispatcher());
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
            $this->commandDispatcher = (new SymfonyCommandDispatcher())
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
