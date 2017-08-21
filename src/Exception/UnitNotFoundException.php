<?php

namespace SystemCtl\Exception;

/**
 * Class UnitNotFoundException
 *
 * @package SystemCtl\Exception
 */
class UnitNotFoundException extends \RuntimeException
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
