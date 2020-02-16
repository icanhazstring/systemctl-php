<?php

namespace icanhazstring\SystemCtl\Command;

use icanhazstring\SystemCtl\Exception\CommandFailedException;

/**
 * Interface CommandInterface
 *
 * @package icanhazstring\SystemCtl\Command
 * @author icanhazstring <blubb0r05+github@gmail.com>
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
