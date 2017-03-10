<?php
namespace Fatum12\TransfonterCore;

use Fatum12\TransfonterCore\Exception\FileNotFound;
use Fatum12\TransfonterCore\Tools\FontForge;

class Font extends File
{
	const TYPE_TTF = 'ttf';
	const TYPE_OTF = 'otf';
	const TYPE_EOT = 'eot';
	const TYPE_WOFF = 'woff';
	const TYPE_WOFF2 = 'woff2';
	const TYPE_SVG = 'svg';

	/**
	 * @var array
	 * @link http://www.w3.org/TR/css3-fonts/#font-weight-numeric-values
	 */
	public static $weights = [
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

	protected $info;

	protected static $magic = [
		self::TYPE_TTF => "\x00\x01\x00\x00\x00",
		self::TYPE_OTF => 'OTTO',
		self::TYPE_WOFF => 'wOFF',
		self::TYPE_WOFF2 => 'wOF2',
		self::TYPE_SVG => '<?xml',
	];

	public function __construct($path)
	{
		$this->setPath($path);
	}

	public function setPath($path)
	{
		if (!is_file($path)) {
			throw new FileNotFound("File not found: {$path}");
		}
		$this->path = realpath($path);
		$this->type = null;
		$this->info = null;
	}

	public function getName()
	{
		return $this->getInfo()['font_name'];
	}

	public function getSafeName()
	{
		$safeName = trim(preg_replace('/[^a-zA-Z0-9\s_\-]/', '', $this->getName()));
		$safeName = preg_replace('/\s+/', '_', $safeName);
		if ($safeName === '') {
			$safeName = md5(uniqid());
		}

		return $safeName;
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

	public function getWeight()
	{
		$fontInfo = $this->getInfo();

		foreach (self::$weights as $weightName => $weightValue) {
			if (
				$weightName == strtolower($fontInfo['weight']) ||
				stripos($fontInfo['font_name'], $weightName) ||
				stripos($fontInfo['full_name'], $weightName)
			) {
				return $weightValue;
			}
		}

		return 'normal';
	}

	public function getStyle()
	{
		$fontInfo = $this->getInfo();

		if (
			stripos($fontInfo['font_name'], 'italic') ||
			stripos($fontInfo['full_name'], 'italic') ||
			$fontInfo['italic_angle'] != '0'
		) {
			return 'italic';
		}

		return 'normal';
	}

	protected function getInfo()
	{
		if (!$this->info) {
			$this->info = FontForge::getFontInfo($this->getPath());
		}

		return $this->info;
	}
}