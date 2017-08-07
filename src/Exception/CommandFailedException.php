<?php

namespace SystemCtl\Exception;

/**
 * CommandFailedException
 *
 * @method static CommandFailedException fromService(string $unitName, string $command)
 * @method static CommandFailedException fromTimer(string $unitName, string $command)
 *
 * @package SystemCtl\Exception
 *
 * @author icanhazstring <blubb0r05@gmail.com>
 */
class CommandFailedException extends \Exception
{
    public static function __callStatic($name, $arguments)
    {
        preg_match('/from(?<unit>.*)/', $name, $match);

        $unit = strtolower($match['unit']);

        $unitName = $arguments[0];
        $command = $arguments[1];

        return new self("Failed to {$command} {$unit} {$unitName}");
    }
}
