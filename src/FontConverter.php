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
			'local' => false
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
			'ttf' => basename($this->files[Font::TYPE_TTF]),
			'eot' => basename($this->files[Font::TYPE_EOT]),
			'weight' => $this->font->getWight(),
			'style' => $this->font->getStyle(),
			'local' => $this->options['local'],
			'localName' => $this->font->getFullName(),
			'localPostScriptName' => $this->font->getName()
		];

		foreach ($this->options['formats'] as $format) {
			if (isset($this->files[$format])) {
				$data[$format] = basename($this->files[$format]);
			}
		}

		if (isset($this->files[Font::TYPE_SVG])) {
			$data['svgId'] = $this->getSVGID();
		}

		return Template::render('font_face', $data);
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
			$command = sprintf('fontforge -script %s/2ttf.pe %s %s', \TRANSFONTER_CORE_FONTFORGE_COMMANDS, $this->font->getPath(), $target);
			Shell::exec($command);
		}

		$this->files[Font::TYPE_TTF] = $target;
	}

	protected function autohint()
	{
		$hinted = $this->dest . '/hinted-' . basename($this->files[Font::TYPE_TTF]);
		$command = sprintf('ttfautohint  --strong-stem-width="" --windows-compatibility --composites %s %s', $this->files[Font::TYPE_TTF], $hinted);
		Shell::exec($command);

		if (file_exists($hinted)) {
			unlink($this->files[Font::TYPE_TTF]);
			$this->files[Font::TYPE_TTF] = $hinted;
		}
	}

	protected function toEOT()
	{
		$target = $this->dest . '/' . Path::filename($this->files[Font::TYPE_TTF]) . '.eot';
		$command = sprintf('ttf2eot %s > %s', $this->files[Font::TYPE_TTF], $target);
		Shell::exec($command);

		$this->files[Font::TYPE_EOT] = $target;
	}

	protected function toWOFF()
	{
		$target = $this->dest . '/' . Path::filename($this->files[Font::TYPE_TTF]) . '.woff';
		$command = sprintf('sfnt2woff %s', $this->files[Font::TYPE_TTF]);
		Shell::exec($command);

		$this->files[Font::TYPE_WOFF] = $target;
	}

	protected function toWOFF2()
	{
		$target = $this->dest . '/' . Path::filename($this->files[Font::TYPE_TTF]) . '.woff2';
		$command = sprintf('woff2_compress %s', $this->files[Font::TYPE_TTF]);
		Shell::exec($command);

		if (file_exists($target)) {
			$this->files[Font::TYPE_WOFF2] = $target;
		}
	}

	protected function toSVG()
	{
		$target = $this->dest . '/' . Path::filename($this->files[Font::TYPE_TTF]) . '.svg';
		$command = sprintf('fontforge -script %s/2svg.pe %s %s', \TRANSFONTER_CORE_FONTFORGE_COMMANDS, $this->files[Font::TYPE_TTF], $target);
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
		$command = sprintf('python %s/subset.py --subset=%s --nmr --null --roundtrip --script %s %s', \TRANSFONTER_CORE_TOOLS, implode('+', $this->options['subsets']), $this->files[Font::TYPE_TTF], $target);
		Shell::exec($command);

		if (file_exists($target)) {
			unlink($this->files[Font::TYPE_TTF]);
			$this->files[Font::TYPE_TTF] = $target;
		}
	}
}