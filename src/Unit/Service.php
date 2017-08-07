<?php


namespace SystemCtl\Unit;

use Symfony\Component\Process\ProcessBuilder;

class Service extends AbstractUnit
{
    public const UNIT = 'service';

    public function __construct($name, ProcessBuilder $processBuilder)
    {
        parent::__construct($name, $processBuilder);
        $this->type = self::UNIT;
    }
}
