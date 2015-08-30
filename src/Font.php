<?php
namespace Fatum12\TransfonterCore;

use Fatum12\TransfonterCore\Exception\FileNotFound;
use Fatum12\TransfonterCore\Exception\ArgumentException;
use Fatum12\TransfonterCore\Util\Shell;

class Font
{
	const TYPE_TTF = 'ttf';
	const TYPE_OTF = 'otf';
	const TYPE_EOT = 'eot';
	const TYPE_WOFF = 'woff';
	const TYPE_WOFF2 = 'woff2';
	const TYPE_SVG = 'svg';

	/**
	 * @link http://www.w3.org/TR/css3-fonts/#font-weight-numeric-values
	 */
	protected static $weights = [
		'thin' => 100,
		'extralight' => 200,
		'ultralight' => 200,
		'light' => 300,
		'medium' => 500,
		'semibold' => 600,
		'demibold' => 600,
		'extrabold' => 800,
		'ultrabold' => 800,
		'bold' => 'bold',
		'black' => 900,
		'heavy' => 900,
	];

	protected $path;
	protected $type;
	protected $name;
	protected $info;

	public function __construct($path)
	{
		if (!is_file($path)) {
			throw new FileNotFound("File not found: {$path}");
		}
		$this->path = realpath($path);
		if (!in_array($this->getType(), [self::TYPE_TTF, self::TYPE_OTF])) {
			throw new ArgumentException("Wrong font type: {$path}");
		}
	}

	/**
	 * @return string Path to font
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * @return string Font type
	 */
	public function getType()
	{
		if (!$this->type) {
			$this->type = strtolower(pathinfo($this->path, \PATHINFO_EXTENSION));
		}

		return $this->type;
	}

	public function getName()
	{
		return $this->getInfo()['font_name'];
	}

	public function getSafeName()
	{
		return str_replace(' ', '_', $this->getName());
	}

	public function getFullName()
	{
		return $this->getInfo()['full_name'];
	}

	public function getFamilyName()
	{
		$fontInfo = $this->getInfo();
		$rule = implode('|', array_keys(self::$weights)) . '|italic|regular';
		$familyName = preg_replace('/\b(' . $rule . ')$/i', '', $fontInfo['family_name']);
		$familyName = trim($familyName, ' -_');

		return $familyName;
	}

	public function getWight()
	{
		$fontInfo = $this->getInfo();

		foreach (self::$weights as $weightName => $weightValue) {
			if ($weightName == strtolower($fontInfo['weight']) ||
				stripos($fontInfo['font_name'], $weightName) ||
				stripos($fontInfo['full_name'], $weightName)) {

				return $weightValue;
			}
		}

		return 'normal';
	}

	public function getStyle()
	{
		$fontInfo = $this->getInfo();

		if (stripos($fontInfo['font_name'], 'italic') ||
			stripos($fontInfo['full_name'], 'italic') ||
			$fontInfo['italic_angle'] != '0') {

			return 'italic';
		}

		return 'normal';
	}

	protected function getInfo()
	{
		if (!$this->info) {
			$command = sprintf('fontforge -script %s/getFontInfo.pe %s', \TRANSFONTER_CORE_FONTFORGE_COMMANDS, $this->path);
			$output = Shell::exec($command);
			$rows = explode("\n", $output);
			$this->info = [];
			foreach ($rows as $row) {
				$delimiterPos = strpos($row, ':');
				$key = trim(substr($row, 0, $delimiterPos));
				$value = trim(substr($row, $delimiterPos + 1));

				$this->info[$key] = $value;
			}
		}

		return $this->info;
	}
}