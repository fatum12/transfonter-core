<?php
namespace Fatum12\TransfonterCore\Tools;

use Fatum12\TransfonterCore\Util\Shell;

class Woff2
{
	public static function compress($source)
	{
		// lower CPU priority
		$command = sprintf('nice woff2_compress "%s"', $source);
		Shell::exec($command);
	}
}