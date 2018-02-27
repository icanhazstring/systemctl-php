<?php
declare(strict_types=1);

namespace SystemCtl\Unit;

/**
 * Service
 *
 * @package SystemCtl\Unit
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
