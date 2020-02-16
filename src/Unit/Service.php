<?php

namespace icanhazstring\SystemCtl\Unit;

/**
 * Class Service
 *
 * @package icanhazstring\SystemCtl\Unit
 */
class Service extends AbstractUnit
{
    /**
     * @var string
     */
    public const UNIT = 'service';

    /**
     * @inheritdoc
     */
    protected function getUnitSuffix(): string
    {
        return static::UNIT;
    }
}
