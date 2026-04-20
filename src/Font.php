<?php

namespace Fatum12\TransfonterCore;

use Fatum12\TransfonterCore\Exception\ArgumentException;
use Fatum12\TransfonterCore\Exception\FileNotFound;
use Fatum12\TransfonterCore\Tools\FontForge;
use Fatum12\TransfonterCore\Util\Path;

class Font extends File
{
    const TYPE_TTF = 'ttf';
    const TYPE_OTF = 'otf';
    const TYPE_EOT = 'eot';
    const TYPE_WOFF = 'woff';
    const TYPE_WOFF2 = 'woff2';
    const TYPE_SVG = 'svg';

    const TYPE_TTC = 'ttc';
    const TYPE_DFONT = 'dfont';

    const TYPE_BIN = 'bin';
    const TYPE_CFF = 'cff';
    const TYPE_PFA = 'pfa';
    const TYPE_PFB = 'pfb';
    const TYPE_PS = 'ps';
    const TYPE_PT3 = 'pt3';
    const TYPE_SFD = 'sfd';
    const TYPE_T11 = 't11';
    const TYPE_T42 = 't42';

    /**
     * @var array
     * @link http://www.w3.org/TR/css3-fonts/#font-weight-numeric-values
     */
    public static $weights = [
        'thin' => '100',
        'extralight' => '200',
        'ultralight' => '200',
        'light' => '300',
        'medium' => '500',
        'semibold' => '600',
        'demibold' => '600',
        'bold' => 'bold',
        'extrabold' => '800',
        'ultrabold' => '800',
        'black' => '900',
        'heavy' => '900',
    ];

    /**
     * @var array|null
     */
    private $info;

    /**
     * @var array
     */
    protected static $magic = [
        self::TYPE_TTF => ["\x00\x01\x00\x00", "true", "typ1"],
        self::TYPE_OTF => ['OTTO'],
        self::TYPE_WOFF => ['wOFF'],
        self::TYPE_WOFF2 => ['wOF2'],
        self::TYPE_SVG => ['<?xml'],

        self::TYPE_TTC => ['ttcf'],
        self::TYPE_DFONT => ["\x00\x00\x01\x00\x00"],

        self::TYPE_BIN => ["\x00"],
        self::TYPE_CFF => ['%!PS'],
        self::TYPE_PFA => ['%!PS'],
        self::TYPE_PFB => ["\x80"],
        self::TYPE_PS => ['%!PS'],
        self::TYPE_PT3 => ['%!PS'],
        self::TYPE_SFD => ['SplineFontDB'],
        self::TYPE_T11 => ['%!PS'],
        self::TYPE_T42 => ['%!PS'],
    ];

    /**
     * @var array
     */
    public static $mimeTypes = [
        self::TYPE_TTF => 'font/ttf',
        self::TYPE_OTF => 'font/otf',
        self::TYPE_EOT => 'application/vnd.ms-fontobject',
        self::TYPE_WOFF => 'font/woff',
        self::TYPE_WOFF2 => 'font/woff2',
        self::TYPE_SVG => 'image/svg+xml',
    ];

    public function __construct(string $path)
    {
        $this->setPath($path);
    }

    public static function sourceTypes(): array
    {
        return [
            self::TYPE_TTF,
            self::TYPE_OTF,
            self::TYPE_WOFF,
            self::TYPE_WOFF2,
            self::TYPE_SVG,
            self::TYPE_TTC,
            self::TYPE_DFONT,
            self::TYPE_BIN,
            self::TYPE_CFF,
            self::TYPE_PFA,
            self::TYPE_PFB,
            self::TYPE_PS,
            self::TYPE_PT3,
            self::TYPE_SFD,
            self::TYPE_T11,
            self::TYPE_T42,
        ];
    }

    public static function collectionTypes(): array
    {
        return [self::TYPE_TTC, self::TYPE_DFONT];
    }

    public function setPath(string $path): void
    {
        if (!is_file($path)) {
            throw new FileNotFound("File not found: $path");
        }
        $this->path = realpath($path);
        $this->type = null;
        $this->info = null;
    }

    public function getName(): string
    {
        return $this->getInfo()['font_name'];
    }

    public function getSafeName(): string
    {
        $safeName = trim(preg_replace('/[^a-zA-Z0-9\s_\-]/', '', $this->getName()));
        $safeName = preg_replace('/\s+/', '_', $safeName);
        if ($safeName === '') {
            $safeName = md5(uniqid());
        }

        return $safeName;
    }

    public function getFullName(): string
    {
        return $this->getInfo()['full_name'];
    }

    public function getFamilyName(): string
    {
        $fontInfo = $this->getInfo();
        $rule = '(italic(\s|-)*)?(' . implode('|', array_keys(self::$weights)) . ')((\s|-)*italic)?|italic|regular';
        $familyName = preg_replace('/\b(' . $rule . ')$/i', '', $fontInfo['family_name']);
        $familyName = trim($familyName, ' -_');

        return $familyName;
    }

    public function getWeight(): string
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

    public function getStyle(): string
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

    protected function getInfo(): array
    {
        if (!$this->info) {
            $this->info = FontForge::getFontInfo($this->getPath());
        }

        return $this->info;
    }

    public function isCollection(): bool
    {
        return in_array($this->getType(), static::collectionTypes());
    }

    public function unpack(string $targetDir, bool $withFallback = true): array
    {
        if (!$this->isCollection()) {
            throw new ArgumentException("Wrong font collection type: " . $this->path);
        }

        Path::mkdir($targetDir);

        $fonts = FontForge::unpackTTC($this->path, $targetDir, $withFallback);

        $files = [];
        foreach ($fonts as $font) {
            if (is_file($targetDir . '/' . $font)) {
                $files[] = $targetDir . '/' . $font;
            }
        }

        return $files;
    }
}
