<?php
namespace Fatum12\TransfonterCore;

use Fatum12\TransfonterCore\Util\Shell;
use Fatum12\TransfonterCore\Util\Path;

class TTCUnpacker
{
	const TYPE_TTC = 'ttc';
	const TYPE_DFONT = 'dfont';

	protected $path;
	protected $type;

	public function __construct($path)
	{
		if (!is_file($path)) {
			throw new FileNotFound("File not found: {$path}");
		}
		$this->path = realpath($path);
		if (!in_array($this->getType(), [self::TYPE_TTC, self::TYPE_DFONT])) {
			throw new ArgumentException("Wrong font collection type: {$path}");
		}
	}

	/**
	 * @return string Font collection type
	 */
	public function getType()
	{
		if (!$this->type) {
			$this->type = strtolower(pathinfo($this->path, \PATHINFO_EXTENSION));
		}

		return $this->type;
	}

	public function unpack($dest = null)
	{
		if ($dest === null) {
			$dest = dirname($this->path);
		}
		else {
			$dest = realpath($dest);
		}

		$oldDir = getcwd();
		chdir($dest);
		$command = sprintf('fontforge -script "%s/ttc2ttf.pe" "%s"', \TRANSFONTER_CORE_FONTFORGE_COMMANDS, $this->path);
		$fonts = explode("\n", Shell::exec($command));
		chdir($oldDir);

		$files = [];
		foreach ($fonts as $font) {
			if (file_exists($dest . '/' . $font)) {
				$files[] = $dest . '/' . $font;
			}
		}

		return $files;
	}
}