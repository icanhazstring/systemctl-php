<?php

namespace SystemCtl;

use Symfony\Component\Process\ProcessBuilder;
use SystemCtl\Exception\UnitTypeNotSupportedException;

class SystemCtl
{
    /** @var string systemctl binary path */
    private static $binary = '/bin/systemctl';

    /** @var bool */
    private static $sudo = false;

    public const AVAILABLE_UNITS = [
        'service',
        'socket',
        'device',
        'mount',
        'automount',
        'swap',
        'target',
        'path',
        'timer',
        'slice',
        'scope'
    ];

    public const SUPPORTED_UNITS = [
        'service',
        'timer'
    ];

    /**
     * @param string $binary
     */
    public static function setBinary(string $binary): void
    {
        self::$binary = $binary;
    }

    /**
     * @param bool $flag
     */
    public static function sudo(bool $flag): void
    {
        self::$sudo = $flag;
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
        $unitClass = 'SystemCtl\\' . ucfirst($unitSuffix);

        if (!class_exists($unitClass)) {
            throw new UnitTypeNotSupportedException('Unit type ' . $unitSuffix . ' not supported');
        }

        return new $unitClass($unitName, new ProcessBuilder(['sudo', self::$binary]));
    }

    /**
     * List all supported units
     *
     * @param string[] $unitTypes
     * @return array|\string[]
     */
    public function listUnits(array $unitTypes = self::SUPPORTED_UNITS): array
    {
        $process = $this->getProcessBuilder()
            ->add('list-units')
            ->getProcess();

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
     * @return Service[]
     */
    public function getServices(): array
    {
        $units = $this->listUnits(['service']);

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
     * @return Timer[]
     */
    public function getTimers(): array
    {
        $units = $this->listUnits(['timer']);

        return array_map(function ($unitName) {
            return new Timer($unitName, $this->getProcessBuilder());
        }, $units);
    }

    /**
     * @return ProcessBuilder
     */
    public function getProcessBuilder(): ProcessBuilder
    {
        $command = explode(' ', self::$binary);
        if (self::$sudo) {
            array_unshift($command, 'sudo');
        }

        $builder = ProcessBuilder::create($command);
        $builder->setTimeout(3);

        return $builder;
    }
}
