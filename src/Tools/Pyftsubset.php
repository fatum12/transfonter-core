<?php
namespace Fatum12\TransfonterCore\Tools;

use Fatum12\TransfonterCore\Util\Shell;

class Pyftsubset
{
	public static function subset($source, $target, array $unicodes = [], $characters = '')
	{
		$command = sprintf(
			"pyftsubset '%s' --unicodes='%s' --text='%s' --ignore-missing-unicodes --ignore-missing-glyphs " .
			"--output-file='%s' --glyph-names --symbol-cmap --legacy-cmap --notdef-glyph --notdef-outline " .
			"--recommended-glyphs --name-IDs='*' --name-legacy --name-languages='*'",
			$source,
			implode(',', $unicodes),
			Shell::escapeArg($characters),
			$target
		);
		Shell::exec($command);
	}
}