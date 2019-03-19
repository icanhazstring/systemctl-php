<?php

namespace SystemCtl\Utils;

/**
 * Class OutputFetcher
 *
 * @package SystemCtl\Utils
 */
class OutputFetcher
{
    /**
     * Fetch unit names from a command output
     *
     * @param string $suffix
     * @param string $output
     *
     * @return string[]
     */
    public static function fetchUnitNames(string $suffix, string $output): array
    {
        preg_match_all('/^[^[:alnum:]-_\.@]*(?<unit>.*)\.' . $suffix . '\s.*$/m', $output, $matches);
        return $matches['unit'] ?? [];
    }
}
