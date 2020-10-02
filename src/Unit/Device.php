<?php


namespace icanhazstring\SystemCtl\Unit;


/**
 * Class Device
 *
 * @package icanhazstring\SystemCtl\Unit
 */
class Device extends AbstractUnit
{
    /**
     * @var string
     */
    public const UNIT = 'device';

    /**
     * @inheritdoc
     */
    protected function getUnitSuffix(): string
    {
        return static::UNIT;
    }
}
