<?php
namespace Fatum12\TransfonterCore;

use Fatum12\TransfonterCore\Exception\ArgumentException;
use Fatum12\TransfonterCore\Util\Template;

class FontManager
{
	/**
	 * @var Config
	 */
	protected $options;
	/**
	 * @var array Source files
	 */
	protected $files = [];

	public function __construct(array $options = [])
	{
		$this->options = new Config(array_replace([
			'stylesheetName' => 'stylesheet.css',
			'demoName' => 'demo.html',
			'demoLanguage' => 'en',
			'formats' => [Font::TYPE_WOFF, Font::TYPE_EOT, Font::TYPE_TTF],
			'autohint' => false,
			'compressSvg' => false,
			// add local rule
			'local' => false,
			// embed font in CSS
			'base64' => false,
			// family support in CSS
			'fontFamily' => true,
		], $options));
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
		if ($this->options->get('demoLanguage') == 'ru') {
			$demoLetters = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюя <br />
				АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ <br />
				abcdefghijklmnopqrstuvwxyz <br />
				ABCDEFGHIJKLMNOPQRSTUVWXYZ <br />';
			$demoString = 'Съешь же ещё этих мягких французских булок, да выпей чаю.';
		} else {
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
				'fontName' => $font->getFullName(),
				'letters' => $demoLetters,
				'string' => $demoString
			]);
		}

		// write css file
		file_put_contents($dest . '/' . $this->options->get('stylesheetName'), Template::render('stylesheet', [
			'css' => implode("\n\n", $css)
		]));
		// write demo file
		file_put_contents($dest . '/' . $this->options->get('demoName'), Template::render('demo', [
			'stylesheet' => $this->options->get('stylesheetName'),
			'styles' => implode("\n", $demoStyles),
			'text' => implode("\n", $demoTexts),
		]));
	}
}