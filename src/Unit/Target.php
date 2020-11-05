<?php

namespace icanhazstring\SystemCtl\Unit;

/**
 * Class Target
 *
 * @package icanhazstring\SystemCtl\Unit
 */
class Target extends AbstractUnit
{
    /**
     * @var string
     */
    public const UNIT = 'target';

    /**
     * @inheritdoc
     */
    protected function getUnitSuffix(): string
    {
        return static::UNIT;
    }
}
