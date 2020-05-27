<?php

namespace icanhazstring\SystemCtl\Unit;

/**
 * Class Scope
 *
 * @package icanhazstring\SystemCtl\Unit
 */
class Scope extends AbstractUnit
{
    /**
     * @var string
     */
    public const UNIT = 'scope';

    /**
     * @inheritdoc
     */
    protected function getUnitSuffix(): string
    {
        return static::UNIT;
    }
}
