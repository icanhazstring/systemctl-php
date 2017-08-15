<?php

namespace SystemCtl\Exception;

use Throwable;

class PropertyNotSupportedException extends \Exception
{
    /**
     * PropertyNotSupportedException constructor.
     * @param string $property
     * @param string $class
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $property, string $class, $code = 0, Throwable $previous = null)
    {
        parent::__construct("Property '{$property}' not supported in {$class}", $code, $previous);
    }
}
