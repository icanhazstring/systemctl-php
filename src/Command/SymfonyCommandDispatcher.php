<?php
declare(strict_types=1);

namespace SystemCtl\Command;

use Symfony\Component\Process\ProcessBuilder;

/**
 * SymfonyCommandDispatcher
 *
 * @package SystemCtl\Command
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class SymfonyCommandDispatcher implements CommandDispatcherInterface
{
    /** @var string */
    private $binary;
    /** @var float */
    private $timeout;
    /** @var string[] */
    private $arguments = [];

    /**
     * @inheritdoc
     */
    public function setBinary(string $binary): CommandDispatcherInterface
    {
        $this->binary = $binary;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setTimeout(int $timeout): CommandDispatcherInterface
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setArguments(array $arguments): CommandDispatcherInterface
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @return string
     */
    public function getBinary(): string
    {
        return $this->binary;
    }

    /**
     * @return float
     */
    public function getTimeout(): float
    {
        return $this->timeout;
    }

    /**
     * @return string[]
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(string ...$commands): CommandInterface
    {
        $processBuilder = new ProcessBuilder();
        $processBuilder->setPrefix($this->binary);
        $processBuilder->setTimeout($this->timeout);
        $processBuilder->setArguments(array_merge($this->arguments, $commands));

        $process = new SymfonyCommand($processBuilder->getProcess());

        return $process->run();
    }
}
