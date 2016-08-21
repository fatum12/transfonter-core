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

	const SUBSET_ARABIC = 'arabic';
	const SUBSET_BENGALI = 'bengali';
	const SUBSET_CYRILLIC = 'cyrillic';
	const SUBSET_CYRILLIC_EXT = 'cyrillic-ext';
	const SUBSET_DEVANAGARI = 'devanagari';
	const SUBSET_GREEK = 'greek';
	const SUBSET_GREEK_EXT = 'greek-ext';
	const SUBSET_GUJARATI = 'gujarati';
    const SUBSET_GURMUKHI = 'gurmukhi';
	const SUBSET_HEBREW = 'hebrew';
    const SUBSET_KANNADA = 'kannada';
	const SUBSET_KHMER = 'khmer';
	const SUBSET_LATIN = 'latin';
	const SUBSET_LATIN_EXT = 'latin-ext';
    const SUBSET_MALAYALAM = 'malayalam';
    const SUBSET_ORIYA = 'oriya';
	const SUBSET_TAMIL = 'tamil';
	const SUBSET_TELUGU = 'telugu';
	const SUBSET_THAI = 'thai';
	const SUBSET_VIETNAMESE = 'vietnamese';

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
	/**
	 * @var array
	 */
	public static $unicodeRanges = [
		self::SUBSET_ARABIC => ['U+0600-06FF', 'U+200C-200E', 'U+2010-2011', 'U+FB50-FDFF', 'U+FE80-FEFC'],
		self::SUBSET_BENGALI => ['U+0964-0965', 'U+0981-09FB', 'U+200B-200D', 'U+20B9', 'U+25CC'],
		self::SUBSET_CYRILLIC => ['U+0400-045F', 'U+0490-0491', 'U+04B0-04B1', 'U+2116'],
		self::SUBSET_CYRILLIC_EXT => ['U+0460-052F', 'U+20B4', 'U+2DE0-2DFF', 'U+A640-A69F'],
		self::SUBSET_DEVANAGARI => ['U+02BC', 'U+0900-097F', 'U+1CD0-1CF6', 'U+1CF8-1CF9', 'U+200B-200D', 'U+20A8',
			'U+20B9', 'U+25CC', 'U+A830-A839', 'U+A8E0-A8FB'],
		self::SUBSET_GREEK => ['U+0370-03FF'],
		self::SUBSET_GREEK_EXT => ['U+1F00-1FFF'],
		self::SUBSET_GUJARATI => ['U+0964-0965', 'U+0A80-0AFF', 'U+200B-200D', 'U+20B9', 'U+25CC', 'U+A830-A839'],
        self::SUBSET_GURMUKHI => ['U+0964-0965', 'U+0A01-0A75', 'U+200B-200D', 'U+20B9', 'U+25CC', 'U+262C',
            'U+A830-A839'],
		self::SUBSET_HEBREW => ['U+0590-05FF', 'U+20AA', 'U+25CC', 'U+FB1D-FB4F'],
        self::SUBSET_KANNADA => ['U+0964-0965', 'U+0C82-0CF2', 'U+200B-200D', 'U+20B9', 'U+25CC'],
		self::SUBSET_KHMER => ['U+1780-17FF', 'U+200B-200C', 'U+25CC'],
		self::SUBSET_LATIN => ['U+0000-00FF', 'U+0131', 'U+0152-0153', 'U+02C6', 'U+02DA', 'U+02DC', 'U+2000-206F',
			'U+2074', 'U+20AC', 'U+2212', 'U+2215', 'U+E0FF', 'U+EFFD', 'U+F000'],
		self::SUBSET_LATIN_EXT => ['U+0100-024F', 'U+1E00-1EFF', 'U+20A0-20AB', 'U+20AD-20CF', 'U+2C60-2C7F',
			'U+A720-A7FF'],
        self::SUBSET_MALAYALAM => ['U+0307', 'U+0323', 'U+0964-0965', 'U+0D02-0D7F', 'U+200B-200D', 'U+20B9', 'U+25CC'],
		self::SUBSET_ORIYA => [''],
        self::SUBSET_TAMIL => ['U+0964-0965', 'U+0B82-0BFA', 'U+200B-200D', 'U+20B9', 'U+25CC'],
		self::SUBSET_TELUGU => ['U+0951-0952', 'U+0964-0965', 'U+0C00-0C7F', 'U+1CDA', 'U+200C-200D', 'U+25CC'],
		self::SUBSET_THAI => ['U+0E01-0E5B', 'U+200B-200D', 'U+25CC'],
		self::SUBSET_VIETNAMESE => ['U+0102-0103', 'U+1EA0-1EF1', 'U+20AB'],
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

	public static function getSubsetsList()
	{
		return [
			self::SUBSET_LATIN => 'Latin',
			self::SUBSET_LATIN_EXT => 'Latin Extended',
			self::SUBSET_ARABIC => 'Arabic',
			self::SUBSET_BENGALI => 'Bengali',
			self::SUBSET_CYRILLIC => 'Cyrillic',
			self::SUBSET_CYRILLIC_EXT => 'Cyrillic Extended',
			self::SUBSET_DEVANAGARI => 'Devanagari',
			self::SUBSET_GREEK => 'Greek',
			self::SUBSET_GREEK_EXT => 'Greek Extended',
			self::SUBSET_GUJARATI => 'Gujarati',
            self::SUBSET_GURMUKHI => 'Gurmukhi',
			self::SUBSET_HEBREW => 'Hebrew',
            self::SUBSET_KANNADA => 'Kannada',
			self::SUBSET_KHMER => 'Khmer',
            self::SUBSET_MALAYALAM => 'Malayalam',
            self::SUBSET_ORIYA => 'Oriya',
			self::SUBSET_TAMIL => 'Tamil',
			self::SUBSET_TELUGU => 'Telugu',
			self::SUBSET_THAI => 'Thai',
			self::SUBSET_VIETNAMESE => 'Vietnamese',

		];
	}

	/**
	 * @return string Path to font
	 */
	public function getPath()
	{
		return $this->path;
	}

	public function getFileName()
	{
		return basename($this->getPath());
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
		$safeName = str_replace(' ', '_', $this->getName());
		$safeName = str_replace("'", '', $safeName);

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
			$command = sprintf('fontforge -script "%s/getFontInfo.pe" "%s"', \TRANSFONTER_CORE_FONTFORGE_COMMANDS, $this->path);
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