<?php

namespace SystemCtl\Unit;

use Symfony\Component\Process\ProcessBuilder;

class Timer extends AbstractUnit
{
    public const UNIT = 'timer';

    /**
     * @inheritdoc
     */
    public function __construct($name, ProcessBuilder $processBuilder)
    {
        parent::__construct($name, self::UNIT, $processBuilder);
    }
}
