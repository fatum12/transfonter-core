<?php
namespace Fatum12\TransfonterCore;

use Fatum12\TransfonterCore\Exception\CommandError;
use Fatum12\TransfonterCore\Exception\FileNotFound;
use Fatum12\TransfonterCore\Tools\FontForge;
use Fatum12\TransfonterCore\Tools\Pyftsubset;
use Fatum12\TransfonterCore\Tools\Ttf2eot;
use Fatum12\TransfonterCore\Tools\Ttfautohint;
use Fatum12\TransfonterCore\Tools\Woff2;
use Fatum12\TransfonterCore\Util\Path;
use Fatum12\TransfonterCore\Util\Template;

class FontConverter
{
	/**
	 * @var Font
	 */
	protected $font;
	/**
	 * @var string
	 */
	protected $dest;
	/**
	 * @var Config
	 */
	protected $options;
	/**
	 * @var array
	 */
	protected $files = [];

	public function __construct(Config $options)
	{
		$this->options = $options;
	}

	/**
	 * @param Font $font Source font file
	 * @param string $dest Destination directory
	 */
	public function convert(Font $font, $dest)
	{
		$this->font = $font;
		$this->dest = $dest;
		$this->files = [];

		// ttf by default
		$this->toTTF();
		$this->subsets();
		if ($this->options->get('autohint')) {
			$this->autohint();
		}
		$formats = $this->options->get('formats', []);
		if (in_array(Font::TYPE_EOT, $formats)) {
			$this->toEOT();
		}
		if (in_array(Font::TYPE_WOFF, $formats)) {
			$this->toWOFF();
		}
		if (in_array(Font::TYPE_WOFF2, $formats)) {
			$this->toWOFF2();
		}
		if (in_array(Font::TYPE_SVG, $formats)) {
			$this->toSVG();
		}
	}

	public function getCSS()
	{
		$useFamily = $this->options->get('fontFamily');
		$formats = $this->options->get('formats', []);
		$addLocalRule = $this->options->get('local', false);

		if ($this->font->getType() == Font::TYPE_SVG) {
			$addLocalRule = false;
		}

		$data = [
			'name' => $useFamily ? $this->font->getFamilyName() : $this->font->getName(),
			'weight' => $useFamily ? $this->font->getWeight() : 'normal',
			'style' => $useFamily ? $this->font->getStyle() : 'normal',
			'local' => $addLocalRule,
			'localName' => $this->font->getFullName(),
			'localPostScriptName' => $this->font->getName(),
			'eotOnly' => count($formats) == 1 && in_array(Font::TYPE_EOT, $formats)
		];

		foreach ($this->files as $format => $file) {
			if ($format == Font::TYPE_TTF && !in_array(Font::TYPE_TTF, $formats)) {
				continue;
			}
			if ($this->options->get('base64')) {
				if (in_array($format, [Font::TYPE_WOFF, Font::TYPE_WOFF2])) {
					$data[$format] = $this->base64($file);
				} elseif ($format == Font::TYPE_TTF && !isset($this->files[Font::TYPE_WOFF]) &&
					!isset($this->files[Font::TYPE_WOFF2])) {
					$data[$format] = $this->base64($file);
				} else {
					$data[$format] = basename($file);
				}
			} else {
				$data[$format] = basename($file);
			}
		}

		if (isset($this->files[Font::TYPE_SVG])) {
			$data['svgId'] = $this->getSVGID();
		}

		return Template::render($this->options->get('base64') ? 'font_face_base64' : 'font_face', $data);
	}

	protected function toTTF()
	{
		$target = Path::uniqueFileName($this->dest . '/' . $this->font->getSafeName() . '.ttf');
		if ($this->font->getType() == Font::TYPE_TTF) {
			// font is TTF - copy to new path
			copy($this->font->getPath(), $target);
		} else {
			// try to convert to TTF
			FontForge::convert($this->font->getPath(), $target);
		}

		if (!file_exists($target)) {
			throw new FileNotFound($target);
		}
		FontForge::fixMeta($target);
		$this->files[Font::TYPE_TTF] = $target;
	}

	protected function autohint()
	{
		$originalName = basename($this->files[Font::TYPE_TTF]);
		// prevent double hinting
		if (strpos($originalName, 'hinted-') === 0) {
			return;
		}
		$hinted = $this->dest . '/hinted-' . $originalName;

		try {
			Ttfautohint::autohint($this->files[Font::TYPE_TTF], $hinted);
		} catch (CommandError $e) {
			// ignore autohint errors
			@unlink($hinted);
			return;
		}

		if (file_exists($hinted)) {
			unlink($this->files[Font::TYPE_TTF]);
			$this->files[Font::TYPE_TTF] = $hinted;
		}
	}

	protected function toEOT()
	{
		$target = $this->dest . '/' . Path::filename($this->files[Font::TYPE_TTF]) . '.eot';
		Ttf2eot::convert($this->files[Font::TYPE_TTF], $target);

		if (file_exists($target)) {
			$this->files[Font::TYPE_EOT] = $target;
		}
	}

	protected function toWOFF()
	{
		$target = $this->dest . '/' . Path::filename($this->files[Font::TYPE_TTF]) . '.woff';
		FontForge::convert($this->files[Font::TYPE_TTF], $target);

		if (file_exists($target)) {
			$this->files[Font::TYPE_WOFF] = $target;
		}
	}

	protected function toWOFF2()
	{
		$target = $this->dest . '/' . Path::filename($this->files[Font::TYPE_TTF]) . '.woff2';
		Woff2::compress($this->files[Font::TYPE_TTF]);

		if (file_exists($target)) {
			$this->files[Font::TYPE_WOFF2] = $target;
		}
	}

	protected function toSVG()
	{
		$target = $this->dest . '/' . Path::filename($this->files[Font::TYPE_TTF]) . '.svg';
		FontForge::convert($this->files[Font::TYPE_TTF], $target);

		if (file_exists($target)) {
			$this->files[Font::TYPE_SVG] = $target;
		}
	}

	// TODO: compress svg font using svgo
	protected function compressSVG()
	{

	}

	protected function getSVGID()
	{
		return $this->font->getName();
	}

	protected function subsets()
	{
		$subsets = $this->options->get('subsets', []);
		$characters = trim($this->options->get('text', ''));
		$userUnicodes = Pyftsubset::parseUnicodes($this->options->get('unicodes', ''));
		if (
			empty($subsets) &&
			$characters === '' &&
			empty($userUnicodes)
		) {
			return;
		}
		$unicodes = [];
		foreach ($subsets as $subsetName) {
			$unicodes = array_merge($unicodes, Language::$unicodeRanges[$subsetName]);
		}
		$unicodes = array_merge($unicodes, $userUnicodes);
		// always include space and newline characters
		$characters .= " \n";

		$target = $this->dest . '/subset-' . basename($this->files[Font::TYPE_TTF]);

		try {
			Pyftsubset::subset($this->files[Font::TYPE_TTF], $target, $unicodes, $characters);
		} catch (CommandError $e) {
			// ignore subsetting errors
			@unlink($target);
			return;
		}

		if (file_exists($target) && filesize($target) > 0) {
			unlink($this->files[Font::TYPE_TTF]);
			$this->files[Font::TYPE_TTF] = $target;
		}
	}

	protected function base64($file)
	{
		return base64_encode(file_get_contents($file));
	}
}