<?php

namespace SystemCtl\Unit;

use Symfony\Component\Process\Process;
use SystemCtl\Command\CommandDispatcherInterface;

abstract class AbstractUnit implements UnitInterface
{
    /** @var string */
    private $name;

    /** @var CommandDispatcherInterface */
    protected $commandDispatcher;

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
     * @return bool
     */
    public function start(): bool
    {
        return $this->commandDispatcher->dispatch(__FUNCTION__);
    }

    /**
     * @return bool
     */
    public function stop(): bool
    {
        return $this->commandDispatcher->dispatch(__FUNCTION__);
    }

    /**
     * @return bool
     */
    public function disable(): bool
    {
        return $this->commandDispatcher->dispatch(__FUNCTION__);
    }

    /**
     * @return bool
     */
    public function reload(): bool
    {
        return $this->commandDispatcher->dispatch(__FUNCTION__);
    }

    /**
     * @return bool
     */
    public function restart(): bool
    {
        return $this->commandDispatcher->dispatch(__FUNCTION__);
    }

    /**
     * @return bool
     */
    public function enable(): bool
    {
        return $this->commandDispatcher->dispatch(__FUNCTION__);
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        $output = $this->commandDispatcher->fetchOutput('is-enabled');

        return trim($output) === 'enabled';
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        $output = $this->commandDispatcher->fetchOutput('is-active');

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
