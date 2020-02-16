<?php

namespace icanhazstring\SystemCtl\Exception;

use RuntimeException;

/**
 * Class UnitNotFoundException
 *
 * @package icanhazstring\SystemCtl\Exception
 */
class UnitNotFoundException extends RuntimeException implements ExceptionInterface
{
    /**
     * @param string $type
     * @param string $name
     *
     * @return UnitNotFoundException
     */
    public static function create(string $type, string $name): self
    {
        return new self(
            sprintf('Could not find %s "%s"', ucfirst($type), $name)
        );
    }
}
