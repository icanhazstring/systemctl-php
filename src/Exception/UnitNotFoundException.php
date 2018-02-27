<?php
declare(strict_types=1);

namespace SystemCtl\Exception;

/**
 * UnitNotFoundException
 *
 * @package SystemCtl\Exception
 * @author  icanhazstring <blubb0r05+github@gmail.com>
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
