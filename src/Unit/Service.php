<?php

namespace SystemCtl\Unit;

use Symfony\Component\Process\ProcessBuilder;

class Service extends AbstractUnit
{
    /**
     * @var string
     */
    public const UNIT = 'service';

    /**
     * @inheritdoc
     */
    public function __construct($name, ProcessBuilder $processBuilder)
    {
        parent::__construct($name, self::UNIT, $processBuilder);
    }
}
