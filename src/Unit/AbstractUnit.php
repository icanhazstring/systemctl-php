<?php

namespace SystemCtl\Unit;

use Symfony\Component\Process\ProcessBuilder;
use SystemCtl\Exception\CommandFailedException;

abstract class AbstractUnit implements UnitInterface
{
    /** @var string */
    protected $type;

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
     * @param bool $raise
     *
     * @throws CommandFailedException Raised if process was not successful and user wants to raise exception
     *                                instead of returning the actual process exit code
     *
     * @return bool
     */
    protected function execute(string $command, bool $raise = true): bool
    {
        $process = $this->processBuilder
            ->setArguments([$command, $this->getName()])
            ->getProcess();

        $process->run();

        if (!$process->isSuccessful() && $raise) {
            $exceptionCall = CommandFailedException::class . '::from' . ucfirst($this->type);
            throw call_user_func_array($exceptionCall, [$this->getName(), $command]);
        }

        return $process->isSuccessful();
    }

    public function start(bool $raise = true): bool
    {
        return $this->execute(__FUNCTION__, $raise);
    }

    public function stop(bool $raise = true): bool
    {
        return $this->execute(__FUNCTION__);
    }

    public function disable(bool $raise = true): bool
    {
        return $this->execute(__FUNCTION__);
    }

    public function reload(bool $raise = true): bool
    {
        return $this->execute(__FUNCTION__);
    }

    public function restart(bool $raise = true): bool
    {
        return $this->execute(__FUNCTION__);
    }

    public function enable(bool $raise = true): bool
    {
        return $this->execute(__FUNCTION__);
    }
}
