<?php

namespace SystemCtl;

/**
 * UnitInterface for handling single units
 *
 * @package SystemCtl
 */
interface UnitInterface
{
    public function getName(): string;

    public function stop(): bool;

    public function disable(): bool;

    public function reload(): bool;

    public function restart(): bool;

    public function enable(): bool;
}
