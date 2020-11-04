<?php

namespace icanhazstring\SystemCtl\Unit;

/**
 * Class Slice
 *
 * @package icanhazstring\SystemCtl\Unit
 */
class Slice extends AbstractUnit
{
    /**
     * @var string
     */
    public const UNIT = 'slice';

    /**
     * @inheritdoc
     */
    protected function getUnitSuffix(): string
    {
        return static::UNIT;
    }
}
