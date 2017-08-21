<?php

namespace SystemCtl\Exception;

/**
 * Class UnitTypeNotSupportedException
 *
 * @package SystemCtl\Exception
 */
class UnitTypeNotSupportedException extends \LogicException
{
    /**
     * @param string $unitSuffix
     *
     * @return UnitTypeNotSupportedException
     */
    public static function create(string $unitSuffix): self
    {
        return new self(
            sprintf('Given unit type "%s" not supported', $unitSuffix)
        );
    }
}
