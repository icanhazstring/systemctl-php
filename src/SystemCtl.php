<?php

namespace SystemCtl;

use Symfony\Component\Process\ProcessBuilder;
use SystemCtl\Exception\UnitTypeNotSupportedException;
use SystemCtl\Unit\Service;
use SystemCtl\Unit\Timer;
use SystemCtl\Unit\UnitInterface;

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
     */
    public static function unitFromSuffix(string $unitSuffix, string $unitName): UnitInterface
    {
        $unitClass = 'SystemCtl\\Unit\\' . ucfirst($unitSuffix);

        if (!class_exists($unitClass)) {
            throw new UnitTypeNotSupportedException('Unit type ' . $unitSuffix . ' not supported');
        }

        return new $unitClass($unitName, new ProcessBuilder([self::$binary]));
    }

    /**
     * List all supported units
     *
     * @param null|string $unitPrefix
     * @param string[] $unitTypes
     * @return array|\string[]
     */
    public function listUnits(?string $unitPrefix = null, array $unitTypes = self::SUPPORTED_UNITS): array
    {
        $processBuilder = $this->getProcessBuilder()
            ->add('list-units');

        if ($unitPrefix) {
            $processBuilder->add($unitPrefix . '*');
        }

        $process = $processBuilder->getProcess();

        $process->run();
        $output = $process->getOutput();

        return array_reduce($unitTypes, function ($carry, $unitSuffix) use ($output) {
            $result = Utils\OutputFetcher::fetchUnitNames($unitSuffix, $output);
            return array_merge($carry, $result);
        }, []);
    }

    /**
     * @param string $name
     * @return Service
     */
    public function getService(string $name): Service
    {
        return new Service($name, $this->getProcessBuilder());
    }

    /**
     * @param null|string $unitPrefix
     * @return Service[]
     */
    public function getServices(?string $unitPrefix = null): array
    {
        $units = $this->listUnits($unitPrefix, [Service::UNIT]);

        return array_map(function ($unitName) {
            return new Service($unitName, $this->getProcessBuilder());
        }, $units);
    }

    /**
     * @param string $name
     * @return Timer
     */
    public function getTimer(string $name): Timer
    {
        return new Timer($name, $this->getProcessBuilder());
    }

    /**
     * @param null|string $unitPrefix
     * @return Timer[]
     */
    public function getTimers(?string $unitPrefix = null): array
    {
        $units = $this->listUnits($unitPrefix, [Timer::UNIT]);

        return array_map(function ($unitName) {
            return new Timer($unitName, $this->getProcessBuilder());
        }, $units);
    }

    /**
     * @return ProcessBuilder
     */
    public function getProcessBuilder(): ProcessBuilder
    {
        $builder = ProcessBuilder::create();
        $builder->setPrefix(self::$binary);
        $builder->setTimeout(self::$timeout);

        return $builder;
    }

    /**
     * Restart the daemon to reload specs and new units
     *
     * @return bool
     */
    public function daemonReload(): bool
    {
        $processBuilder = $this->getProcessBuilder();
        $processBuilder->add('daemon-reload');

        $process = $processBuilder->getProcess();
        $process->run();

        return $process->isSuccessful();
    }
}
