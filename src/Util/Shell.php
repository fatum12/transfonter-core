<?php
namespace Fatum12\TransfonterCore\Util;

use Fatum12\TransfonterCore\Exception\CommandNotFound;
use Fatum12\TransfonterCore\Exception\CommandError;

class Shell
{
    const STATUS_TIMEOUT = 124;
    const STATUS_NOT_FOUND = 127;

    /**
     * @var callable[]
     */
    private static $modifiers = [];

    public static function exec($command)
    {
        foreach (self::$modifiers as $modifier) {
            $command = $modifier($command);
        }

        exec($command . ' 2> /dev/null', $output, $result);

        if ($result == self::STATUS_NOT_FOUND) {
            throw new CommandNotFound(sprintf('Command "%s" not found.', $command));
        } elseif ($result != 0) {
            throw new CommandError(sprintf('Command failed, return code: %d, command: %s', $result, $command), $result);
        }

        return trim(implode("\n", $output));
    }

    public static function escapeArg($arg)
    {
        return str_replace("'", "'\\''", $arg);
    }

    public static function addModifier(callable $modifier)
    {
        self::$modifiers[] = $modifier;
    }
}