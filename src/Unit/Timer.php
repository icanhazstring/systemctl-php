<?php
declare(strict_types=1);

namespace SystemCtl\Unit;

/**
 * Timer
 *
 * @package SystemCtl\Unit
 */
class Timer extends AbstractUnit
{
    /**
     * @var string
     */
    public const UNIT = 'timer';

    /**
     * @inheritdoc
     */
    protected function getUnitSuffix(): string
    {
        return static::UNIT;
    }
}
