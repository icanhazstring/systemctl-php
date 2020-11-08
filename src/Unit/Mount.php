<?php

namespace icanhazstring\SystemCtl\Unit;

/**
 * Class Mount
 *
 * @package icanhazstring\SystemCtl\Unit
 */
class Mount extends AbstractUnit
{
    /**
     * @var string
     */
    public const UNIT = 'mount';

    /**
     * @inheritdoc
     */
    protected function getUnitSuffix(): string
    {
        return static::UNIT;
    }
}
