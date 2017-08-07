<?php

namespace SystemCtl;

use Symfony\Component\Process\ProcessBuilder;
use SystemCtl\Exception\UnitTypeNotSupportedException;
use SystemCtl\Unit\AbstractUnit;
use SystemCtl\Unit\Service;
use SystemCtl\Unit\Timer;
use SystemCtl\Unit\UnitInterface;

/**
 * SystemCtl
 *
 * @method Service getService(string $unit)
 * @method Timer getTimer(string $unit)
 *
 * @method array getServices(?string $unitPrefix = null)
 * @method array getTimers(?string $unitPrefix = null)
 *
 * @package SystemCtl
 * @author icanhazstring <blubb0r05+github@gmail.com>
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

    public function __call($name, $arguments)
    {
        preg_match('/get(?<unit>[^s]+)(?<plural>s)?/', $name, $match);

        $isPlural = isset($match['plural']);
        $unitName = strtolower($match['unit']);

        if (!in_array($unitName, self::SUPPORTED_UNITS)) {
            throw new UnitTypeNotSupportedException("Unit {$unitName} not supported");
        }

        // Singular differs requested name?
        // Get a list of units
        if ($isPlural) {
            return $this->getUnits(ucfirst($unitName), $arguments);
        }

        return $this->getUnit(ucfirst($unitName), $arguments);
    }

    /**
     * @param string $unitClass
     * @param $args
     * @return AbstractUnit
     */
    private function getUnit(string $unitClass, $args): AbstractUnit
    {
        $args[] = $this->getProcessBuilder();
        $className = '\SystemCtl\Unit\\' . $unitClass;

        return new $className(...$args);
    }

    /**
     * @param string $unitName
     * @param $arguments
     * @return array
     */
    private function getUnits(string $unitName, $arguments): array
    {
        $unitPrefix = $arguments[0] ?? null;
        $units = $this->listUnits($unitPrefix, [strtolower($unitName)]);
        $unitClass = '\SystemCtl\Unit\\' . $unitName;

        return array_map(function ($unitName) use ($unitClass) {
            return new $unitClass($unitName, $this->getProcessBuilder());
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
