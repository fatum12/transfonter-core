<?php
namespace Fatum12\TransfonterCore;

use Fatum12\TransfonterCore\Util\Path;
use Fatum12\TransfonterCore\Util\Shell;
use Fatum12\TransfonterCore\Util\Template;

class FontConverter
{
	protected $font;
	protected $dest;
	protected $options = [];
	protected $files = [];

	public function __construct(Font $font, $dest, $options = [])
	{
		$this->font = $font;
		$this->dest = $dest;
		$this->options = array_replace([
			'formats' => [Font::TYPE_WOFF, Font::TYPE_WOFF2],
			'autohint' => false,
			'compress_svg' => false,
			'local' => false,
			'base64' => false,
		], $options);
	}

	public function convert()
	{
		// ttf and eot by default
		$this->toTTF();
		$this->subsets();
		if ($this->options['autohint']) {
			$this->autohint();
		}
		$this->toEOT();
		if (in_array(Font::TYPE_WOFF, $this->options['formats'])) {
			$this->toWOFF();
		}
		if (in_array(Font::TYPE_WOFF2, $this->options['formats'])) {
			$this->toWOFF2();
		}
		if (in_array(Font::TYPE_SVG, $this->options['formats'])) {
			$this->toSVG();
		}
	}

	public function getCSS()
	{
		$data = [
			'name' => $this->font->getFamilyName(),
			'weight' => $this->font->getWight(),
			'style' => $this->font->getStyle(),
			'local' => $this->options['local'],
			'localName' => $this->font->getFullName(),
			'localPostScriptName' => $this->font->getName()
		];

		foreach ($this->files as $format => $file) {
			if ($this->options['base64']) {
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

		return Template::render($this->options['base64'] ? 'font_face_base64' : 'font_face', $data);
	}

	protected function toTTF()
	{
		$target = Path::uniqueFileName($this->dest . '/' . $this->font->getSafeName() . '.ttf');
		if ($this->font->getType() == Font::TYPE_TTF) {
			// font is TTF - copy to new path
			copy($this->font->getPath(), $target);
		}
		else {
			// convert to TTF
			$command = sprintf('fontforge -script "%s/2format.pe" "%s" "%s"', \TRANSFONTER_CORE_FONTFORGE_COMMANDS, $this->font->getPath(), $target);
			Shell::exec($command);
		}

		if (file_exists($target)) {
			$this->files[Font::TYPE_TTF] = $target;
		}
	}

	protected function autohint()
	{
		$hinted = $this->dest . '/hinted-' . basename($this->files[Font::TYPE_TTF]);
		$command = sprintf('ttfautohint --strong-stem-width="" --windows-compatibility --composites -i "%s" "%s"', $this->files[Font::TYPE_TTF], $hinted);
		Shell::exec($command);

		if (file_exists($hinted)) {
			unlink($this->files[Font::TYPE_TTF]);
			$this->files[Font::TYPE_TTF] = $hinted;
		}
	}

	protected function toEOT()
	{
		$target = $this->dest . '/' . Path::filename($this->files[Font::TYPE_TTF]) . '.eot';
		$command = sprintf('ttf2eot "%s" > "%s"', $this->files[Font::TYPE_TTF], $target);
		Shell::exec($command);

		if (file_exists($target)) {
			$this->files[Font::TYPE_EOT] = $target;
		}
	}

	protected function toWOFF()
	{
		$target = $this->dest . '/' . Path::filename($this->files[Font::TYPE_TTF]) . '.woff';
		$command = sprintf('sfnt2woff "%s"', $this->files[Font::TYPE_TTF]);
		Shell::exec($command);

		if (file_exists($target)) {
			$this->files[Font::TYPE_WOFF] = $target;
		}
	}

	protected function toWOFF2()
	{
		$target = $this->dest . '/' . Path::filename($this->files[Font::TYPE_TTF]) . '.woff2';
		$command = sprintf('woff2_compress "%s"', $this->files[Font::TYPE_TTF]);
		Shell::exec($command);

		if (file_exists($target)) {
			$this->files[Font::TYPE_WOFF2] = $target;
		}
	}

	protected function toSVG()
	{
		$target = $this->dest . '/' . Path::filename($this->files[Font::TYPE_TTF]) . '.svg';
		$command = sprintf('fontforge -script "%s/2format.pe" "%s" "%s"', \TRANSFONTER_CORE_FONTFORGE_COMMANDS, $this->files[Font::TYPE_TTF], $target);
		Shell::exec($command);

		if (file_exists($target)) {
			$this->files[Font::TYPE_SVG] = $target;
		}
	}

	// TODO: compress svg font with svgo
	protected function compressSVG()
	{

	}

	protected function getSVGID()
	{
		if (!isset($this->files[Font::TYPE_SVG])) {
			return '';
		}

		return $this->font->getName();
	}

	protected function subsets()
	{
		if (!isset($this->options['subsets']) || !is_array($this->options['subsets']) || empty($this->options['subsets'])) {
			return;
		}
		$target = $this->dest . '/subset-' . basename($this->files[Font::TYPE_TTF]);
		$command = sprintf('python %s/subset.py --subset=%s --nmr --null --roundtrip --script "%s" "%s"', \TRANSFONTER_CORE_TOOLS, implode('+', $this->options['subsets']), $this->files[Font::TYPE_TTF], $target);
		Shell::exec($command);

		if (file_exists($target)) {
			unlink($this->files[Font::TYPE_TTF]);
			$this->files[Font::TYPE_TTF] = $target;
		}
	}

	protected function base64($file)
	{
		return base64_encode(file_get_contents($file));
	}
}