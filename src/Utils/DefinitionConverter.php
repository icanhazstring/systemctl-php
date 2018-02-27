<?php
declare(strict_types=1);

namespace SystemCtl\Utils;

/**
 * DefinitionConverter
 *
 * Used to convert certain definition from array to a space seperated string.
 * This needs to be done due to the fact, that some definitions may be a list of items.
 * These needs to be space seperated so the unit template can be properly rendered.
 *
 * @package SystemCtl\Utils
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class DefinitionConverter
{
    /**
     * Convert a given definition from different types to a string
     *
     * @param mixed $defintiion
     *
     * @return string
     */
    public static function convert($defintiion): string
    {
        if (\is_array($defintiion)) {
            $defintiion = implode(' ', $defintiion);
        }

        return $defintiion;
    }
}
