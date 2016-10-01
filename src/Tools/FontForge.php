<?php
namespace Fatum12\TransfonterCore\Tools;

use Fatum12\TransfonterCore\Util\Shell;

class FontForge
{
	const COMMANDS_PATH = __DIR__ . '/fontforge';

	public static function convert($source, $target)
	{
		$command = sprintf(
			'fontforge -script "%s/2format.pe" "%s" "%s"',
			self::COMMANDS_PATH,
			$source,
			$target
		);
		Shell::exec($command);
	}

	public static function getFontInfo($fontPath)
	{
		$command = sprintf(
			'fontforge -script "%s/getFontInfo.pe" "%s"',
			self::COMMANDS_PATH,
			$fontPath
		);
		$output = Shell::exec($command);

		$rows = explode("\n", $output);
		$result = [];
		foreach ($rows as $row) {
			$delimiterPos = strpos($row, ':');
			$key = trim(substr($row, 0, $delimiterPos));
			$value = trim(substr($row, $delimiterPos + 1));

			$result[$key] = $value;
		}
		return $result;
	}

	public static function unpackTTC($source, $targetDir)
	{
		$oldDir = getcwd();
		chdir($targetDir);
		$command = sprintf(
			'fontforge -script "%s/ttc2ttf.pe" "%s"',
			self::COMMANDS_PATH,
			$source
		);
		chdir($oldDir);

		return explode("\n", Shell::exec($command));
	}
}