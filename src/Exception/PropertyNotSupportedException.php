<?php
declare(strict_types=1);

namespace SystemCtl\Exception;

/**
 * PropertyNotSupportedException
 *
 * @package SystemCtl\Exception
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class PropertyNotSupportedException extends \LogicException
{
    /**
     * @param string $property
     * @param string $class
     *
     * @return PropertyNotSupportedException
     */
    public static function create(string $property, string $class): self
    {
        return new self(
            sprintf('Property "%s" not supported in %s', $property, $class)
        );
    }
}
