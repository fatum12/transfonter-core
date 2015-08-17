<?php
namespace Fatum12\TransfonterCore;

use Fatum12\TransfonterCore\Exception\ArgumentException;
use Fatum12\TransfonterCore\Util\Template;

class FontManager
{
	protected $options;
	/**
	 * @var array Source files
	 */
	protected $files = [];

	public function __construct(array $options = [])
	{
		$this->options = array_replace([
			'stylesheet_name' => 'stylesheet.css',
		], $options);
	}

	public function add($path)
	{
		$this->files[] = $path;
	}

	public function loadFromDir($dir)
	{
		if (!is_dir($dir)) {
			throw new ArgumentException("Wrong directory: {$dir}");
		}
		$dir = rtrim($dir, '/\\');

		foreach (glob($dir . '/*.{ttf,otf}', \GLOB_BRACE) as $file) {
			$this->files[] = $file;
		}
	}

	public function process($dest)
	{
		if (!is_writable($dest)) {
			throw new ArgumentException("Directory {$dest} is not writable");
		}

		$css = [];
		foreach ($this->files as $file) {
			$font = new Font($file);
			$converter = new FontConverter($font, $dest, $this->options);
			$converter->convert();
			$css[] = $converter->getCSS();
		}

		// write css file
		file_put_contents($dest . '/' . $this->options['stylesheet_name'], Template::render('stylesheet', ['css' => implode("\n", $css)]));
	}
}