<?php
namespace Fatum12\TransfonterCore\Util;


class Path
{
	public static function uniqueFileName($path)
	{
		$info = pathinfo($path);

		$newPath = $path;
		$index = 0;
		while (file_exists($newPath)) {
			$index++;
			$newPath = $info['dirname'] . '/' . $info['filename'] . '_' . $index . '.' . $info['extension'];
		}

		return $newPath;
	}

	/**
	 * @param $file Path to file
	 * @return string Name of the file without extension
	 */
	public static function filename($file)
	{
		return pathinfo($file, PATHINFO_FILENAME);
	}
}