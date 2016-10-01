<?php
namespace Fatum12\TransfonterCore\Tools;

use Fatum12\TransfonterCore\Util\Shell;

class Ttfautohint
{
	public static function autohint($source, $target)
	{
		$command = sprintf(
			'ttfautohint --strong-stem-width="" --windows-compatibility --composites -i "%s" "%s"',
			$source,
			$target
		);
		Shell::exec($command);
	}
}