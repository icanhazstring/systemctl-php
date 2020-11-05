<?php

namespace icanhazstring\SystemCtl\Unit;

/**
 * Class Swap
 *
 * @package icanhazstring\SystemCtl\Unit
 */
class Swap extends AbstractUnit
{
    /**
     * @var string
     */
    public const UNIT = 'swap';

    /**
     * @inheritdoc
     */
    protected function getUnitSuffix(): string
    {
        return static::UNIT;
    }
}
