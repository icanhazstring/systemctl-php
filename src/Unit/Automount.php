<?php

namespace icanhazstring\SystemCtl\Unit;

/**
 * Class Automount
 *
 * @package icanhazstring\SystemCtl\Unit
 */
class Automount extends AbstractUnit
{
    /**
     * @var string
     */
    public const UNIT = 'automount';

    /**
     * @inheritdoc
     */
    protected function getUnitSuffix(): string
    {
        return static::UNIT;
    }
}
