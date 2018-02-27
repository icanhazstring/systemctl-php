<?php
declare(strict_types=1);

namespace SystemCtl\Command;

use SystemCtl\Exception\CommandFailedException;

/**
 * CommandInterface
 *
 * @package SystemCtl\Command
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
interface CommandInterface
{
    /**
     * @return CommandInterface
     * @throws CommandFailedException
     */
    public function run(): CommandInterface;

    /**
     * @return string
     */
    public function getOutput(): string;

    /**
     * @return bool
     */
    public function isSuccessful(): bool;
}
