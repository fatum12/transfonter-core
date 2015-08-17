<?php
namespace Fatum12\TransfonterCore\Util;

use Fatum12\TransfonterCore\Exception\CommandNotFound;
use Fatum12\TransfonterCore\Exception\BaseException;

class Shell
{
	public static function exec($command)
	{
		exec(escapeshellcmd($command) . ' 1> /dev/null 2> /dev/null', $output, $result);

		if ($result == 127) {
			throw new CommandNotFound(sprintf('Command "%s" not found.', $command));
		} elseif ($result != 0) {
			throw new BaseException(sprintf('Command failed, return code: %d, command: %s', $result, $command));
		}

		return trim(implode("\n", $output));
	}
}