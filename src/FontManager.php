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
			'demo_name' => 'demo.html',
			'demo_language' => 'en',
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
		if ($this->options['demo_language'] == 'ru') {
			$demoLetters = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюя <br />
				АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ <br />';
			$demoString = 'Съешь же ещё этих мягких французских булок, да выпей чаю.';
		}
		else {
			$demoLetters = 'abcdefghijklmnopqrstuvwxyz <br />
				ABCDEFGHIJKLMNOPQRSTUVWXYZ <br />';
			$demoString = 'The quick brown fox jumps over the lazy dog.';
		}

		$css = [];
		$demoStyles = [];
		$demoTexts = [];
		foreach ($this->files as $index => $file) {
			$font = new Font($file);
			$converter = new FontConverter($font, $dest, $this->options);
			$converter->convert();
			$css[] = $converter->getCSS();
			$demoStyles[] = Template::render('demo_style', [
				'index' => $index,
				'fontName' => $font->getFamilyName(),
				'weight' => $font->getWight(),
				'style' => $font->getStyle()
			]);
			$demoTexts[] = Template::render('demo_item', [
				'index' => $index,
				'fontName' => $font->getFamilyName(),
				'letters' => $demoLetters,
				'string' => $demoString
			]);
		}

		// write css file
		file_put_contents($dest . '/' . $this->options['stylesheet_name'], Template::render('stylesheet', [
			'css' => implode("\n", $css)
		]));
		// write demo file
		file_put_contents($dest . '/' . $this->options['demo_name'], Template::render('demo', [
			'stylesheet' => $this->options['stylesheet_name'],
			'styles' => implode("\n", $demoStyles),
			'text' => implode("\n", $demoTexts),
		]));
	}
}