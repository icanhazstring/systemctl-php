<?php

namespace SystemCtl\Unit;

use SystemCtl\Command\CommandDispatcherInterface;
use SystemCtl\Command\CommandInterface;

/**
 * Class AbstractUnit
 *
 * @package SystemCtl\Unit
 */
abstract class AbstractUnit implements UnitInterface
{
    /** @var string */
    private $name;

    /** @var CommandDispatcherInterface */
    protected $commandDispatcher;

    /**
     * @var string
     */
    protected $unitSuffix;

    /**
     * Create new service with given name
     *
     * @param string                     $name
     * @param CommandDispatcherInterface $commandDispatcher
     */
    public function __construct(string $name, CommandDispatcherInterface $commandDispatcher)
    {
        $this->name = $name;
        $this->commandDispatcher = $commandDispatcher;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function isMultiInstance(): bool
    {
        return strpos($this->name, '@') !== false;
    }

    /**
     * @inheritDoc
     * @todo: Everything in here should happen inside the constructor and stored
     *      afterwards.
     */
    public function getInstanceName(): ?string
    {
        $instanceName = explode('@', $this->name, 2)[1] ?? null;

        if (is_string($instanceName) && strpos($instanceName, '.') !== false) {
            $instanceName = explode('.', $instanceName, 2)[0];
        }

        return $instanceName;
    }

    /**
     * @return string
     */
    abstract protected function getUnitSuffix(): string;

    /**
     * @param array $commands
     *
     * @return CommandInterface
     */
    public function execute(...$commands): CommandInterface
    {
        $commands[] = implode(
            '.',
            [
                $this->name,
                $this->getUnitSuffix(),
            ]
        );

        return $this->commandDispatcher->dispatch(...$commands);
    }

    /**
     * @return bool
     */
    public function start(): bool
    {
        return $this->execute(__FUNCTION__)->isSuccessful();
    }

    /**
     * @return bool
     */
    public function stop(): bool
    {
        return $this->execute(__FUNCTION__)->isSuccessful();
    }

    /**
     * @return bool
     */
    public function disable(): bool
    {
        return $this->execute(__FUNCTION__)->isSuccessful();
    }

    /**
     * @return bool
     */
    public function reload(): bool
    {
        return $this->execute(__FUNCTION__)->isSuccessful();
    }

    /**
     * @return bool
     */
    public function restart(): bool
    {
        return $this->execute(__FUNCTION__)->isSuccessful();
    }

    /**
     * @return bool
     */
    public function enable(): bool
    {
        return $this->execute(__FUNCTION__)->isSuccessful();
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        $output = $this->execute('is-enabled')->getOutput();

        return trim($output) === 'enabled';
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        $output = $this->execute('is-active')->getOutput();

        return trim($output) === 'active';
    }

    /**
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this->isActive();
    }
}
