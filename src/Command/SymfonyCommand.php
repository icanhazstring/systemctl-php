<?php
declare(strict_types=1);

namespace SystemCtl\Command;

use Symfony\Component\Process\Process;
use SystemCtl\Exception\CommandFailedException;

/**
 * SymfonyCommand
 *
 * @package SystemCtl\Command
 * @author icanhazstring <blubb0r05+github@gmail.com>
 */
class SymfonyCommand implements CommandInterface
{
    /**
     * List of valid exit codes of systemctl commands.
     * When systemctl is-active is checked on a non active unit, we receive exit code 3
     */
    private const VALID_EXITCODES = [0, 3];

    /** @var Process */
    private $process;

    /**
     * @param Process $process
     */
    public function __construct(Process $process)
    {
        $this->process = $process;
    }

    /**
     * @inheritdoc
     */
    public function getOutput(): string
    {
        return $this->process->getOutput();
    }

    /**
     * @inheritdoc
     */
    public function isSuccessful(): bool
    {
        return $this->process->isSuccessful();
    }

    /**
     * @inheritdoc
     */
    public function run(): CommandInterface
    {
        $this->process->run();

        $exitCode = $this->process->getExitCode();

        if (!\in_array((int)$exitCode, self::VALID_EXITCODES, true)) {
            throw new CommandFailedException($this->process->getErrorOutput());
        }

        return $this;
    }
}
