<?php
declare(strict_types=1);

namespace SystemCtl\Exception;

/**
 * UnitFileExistsException
 *
 * @package SystemCtl\Exception
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class UnitFileExistsException extends \RuntimeException
{
    /**
     * @param string $unitName
     * @param string $unitSuffix
     *
     * @return UnitFileExistsException
     */
    public static function create(string $unitName, string $unitSuffix): self
    {
        return new self(
            sprintf('Unit file %s.%s already exists', $unitName, $unitSuffix)
        );
    }
}
