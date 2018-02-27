<?php
declare(strict_types=1);

namespace SystemCtl\Exception;

/**
 * UnitTypeNotSupportedException
 *
 * @package SystemCtl\Exception
 * @author  icanhazstring <blubb0r05+github@gmail.com>
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
