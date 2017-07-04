<?php

namespace SystemCtl\Unit;

use Symfony\Component\Process\ProcessBuilder;

abstract class AbstractUnit implements UnitInterface
{
    /** @var string */
    private $name;

    /** @var ProcessBuilder */
    protected $processBuilder;

    /**
     * Create new service with given name
     *
     * @param string $name
     * @param ProcessBuilder $processBuilder
     */
    public function __construct(string $name, ProcessBuilder $processBuilder)
    {
        $this->name = $name;
        $this->processBuilder = $processBuilder;
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
     */
    public function getInstanceName(): ?string
    {
        $parts = explode('@', $this->name);
        return $parts[1] ?? null;
    }

    /**
     * Execute a single command
     *
     * @param string $command
     * @return bool
     */
    abstract protected function execute(string $command): bool;

    public function start(): bool
    {
        return $this->execute(__FUNCTION__);
    }

    public function stop(): bool
    {
        return $this->execute(__FUNCTION__);
    }

    public function disable(): bool
    {
        return $this->execute(__FUNCTION__);
    }

    public function reload(): bool
    {
        return $this->execute(__FUNCTION__);
    }

    public function restart(): bool
    {
        return $this->execute(__FUNCTION__);
    }

    public function enable(): bool
    {
        return $this->execute(__FUNCTION__);
    }
}
