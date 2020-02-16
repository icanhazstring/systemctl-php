<?php

namespace icanhazstring\SystemCtl\Unit;

/**
 * Class Socket
 *
 * @package icanhazstring\SystemCtl\Unit
 */
class Socket extends AbstractUnit
{
    /**
     * @var string
     */
    public const UNIT = 'socket';

    /**
     * @inheritdoc
     */
    protected function getUnitSuffix(): string
    {
        return static::UNIT;
    }
}
