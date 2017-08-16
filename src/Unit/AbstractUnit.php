<?php

namespace SystemCtl\Unit;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use SystemCtl\Exception\CommandFailedException;

abstract class AbstractUnit implements UnitInterface
{
    /** @var string */
    private $name;

    /** @var string */
    private $type;

    /** @var ProcessBuilder */
    private $processBuilder;

    /** @var bool */
    private $yell = false;

    /**
     * Create new service with given name
     *
     * @param string         $name
     * @param string         $type
     * @param ProcessBuilder $processBuilder
     */
    public function __construct(string $name, string $type, ProcessBuilder $processBuilder)
    {
        $this->name = $name;
        $this->processBuilder = $processBuilder;
        $this->type = $type;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set a flag whether to yell with an exception if an command failes or not
     *
     * @param $state
     */
    public function yell($state)
    {
        $this->yell = $state;
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
     * Execute a single command
     *
     * @param string $command
     *
     * @throws CommandFailedException Raised if process was not successful and user wants to raise exception
     *                                instead of returning the actual process exit code
     *
     * @return bool
     */
    protected function execute(string $command): bool
    {
        $process = $this->runCommandAgainstService($command);

        if (!$process->isSuccessful() && $this->yell) {
            $exceptionCall = CommandFailedException::class . '::from' . ucfirst($this->type);
            throw call_user_func_array($exceptionCall, [$this->getName(), $command]);
        }

        return $process->isSuccessful();
    }

    /**
     * @param string $command
     *
     * @return Process
     */
    protected function runCommandAgainstService(string $command): Process
    {
        $process = $this->processBuilder
            ->setArguments([$command, $this->getName()])
            ->getProcess();

        $process->run();

        return $process;
    }

    /**
     * @return bool
     */
    public function start(): bool
    {
        return $this->execute(__FUNCTION__);
    }

    /**
     * @return bool
     */
    public function stop(): bool
    {
        return $this->execute(__FUNCTION__);
    }

    /**
     * @return bool
     */
    public function disable(): bool
    {
        return $this->execute(__FUNCTION__);
    }

    /**
     * @return bool
     */
    public function reload(): bool
    {
        return $this->execute(__FUNCTION__);
    }

    /**
     * @return bool
     */
    public function restart(): bool
    {
        return $this->execute(__FUNCTION__);
    }

    /**
     * @return bool
     */
    public function enable(): bool
    {
        return $this->execute(__FUNCTION__);
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        $process = $this->runCommandAgainstService('is-enabled');

        return $process->isSuccessful() && trim($process->getOutput()) === 'enabled';
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        $process = $this->runCommandAgainstService('is-active');

        return $process->isSuccessful() && trim($process->getOutput()) === 'active';
    }

    /**
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this->isActive();
    }
}
