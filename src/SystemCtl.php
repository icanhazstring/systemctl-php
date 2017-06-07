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
     * @return array|Service[]
     */
    public function getServices(?string $unitPrefix = null): array
    {
        $units = $this->listUnits($unitPrefix, ['service']);

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
     * @return array|Timer[]
     */
    public function getTimers(?string $unitPrefix = null): array
    {
        $units = $this->listUnits($unitPrefix, ['timer']);

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
        $builder->setTimeout(3);

        return $builder;
    }
}
