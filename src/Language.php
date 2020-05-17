<?php

namespace Fatum12\TransfonterCore;

class Language
{
    const LANG_EN = 'en';
    const LANG_RU = 'ru';
    const LANG_KA = 'ka';

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
    const SUBSET_MYANMAR = 'myanmar';
    const SUBSET_ORIYA = 'oriya';
    const SUBSET_SINHALA = 'sinhala';
    const SUBSET_TAMIL = 'tamil';
    const SUBSET_TELUGU = 'telugu';
    const SUBSET_THAI = 'thai';
    const SUBSET_TIBETAN = 'tibetan';
    const SUBSET_VIETNAMESE = 'vietnamese';

    /**
     * @var array
     */
    public static $unicodeRanges = [
        self::SUBSET_ARABIC => ['U+0600-06FF', 'U+200C-200E', 'U+2010-2011', 'U+204F', 'U+2E41', 'U+FB50-FDFF', 'U+FE80-FEFC'],
        self::SUBSET_BENGALI => ['U+0964-0965', 'U+0981-09FB', 'U+200C-200D', 'U+20B9', 'U+25CC'],
        self::SUBSET_CYRILLIC => ['U+0400-045F', 'U+0490-0491', 'U+04B0-04B1', 'U+2116'],
        self::SUBSET_CYRILLIC_EXT => ['U+0460-052F', 'U+1C80-1C88', 'U+20B4', 'U+2DE0-2DFF', 'U+A640-A69F', 'U+FE2E-FE2F'],
        self::SUBSET_DEVANAGARI => ['U+0900-097F', 'U+1CD0-1CF6', 'U+1CF8-1CF9', 'U+200C-200D', 'U+20A8', 'U+20B9', 'U+25CC', 'U+A830-A839', 'U+A8E0-A8FB'],
        self::SUBSET_GREEK => ['U+0370-03FF'],
        self::SUBSET_GREEK_EXT => ['U+1F00-1FFF'],
        self::SUBSET_GUJARATI => ['U+0964-0965', 'U+0A80-0AFF', 'U+200C-200D', 'U+20B9', 'U+25CC', 'U+A830-A839'],
        self::SUBSET_GURMUKHI => ['U+0964-0965', 'U+0A01-0A75', 'U+200C-200D', 'U+20B9', 'U+25CC', 'U+262C', 'U+A830-A839'],
        self::SUBSET_HEBREW => ['U+0590-05FF', 'U+20AA', 'U+25CC', 'U+FB1D-FB4F'],
        self::SUBSET_KANNADA => ['U+0964-0965', 'U+0C82-0CF2', 'U+200C-200D', 'U+20B9', 'U+25CC'],
        self::SUBSET_KHMER => ['U+1780-17FF', 'U+200C', 'U+25CC'],
        self::SUBSET_LATIN => ['U+0000-00FF', 'U+0131', 'U+0152-0153', 'U+02BB-02BC', 'U+02C6', 'U+02DA', 'U+02DC', 'U+2000-206F', 'U+2074', 'U+20AC', 'U+2122', 'U+2191', 'U+2193', 'U+2212', 'U+2215', 'U+FEFF', 'U+FFFD'],
        self::SUBSET_LATIN_EXT => ['U+0100-024F', 'U+0259', 'U+1E00-1EFF', 'U+2020', 'U+20A0-20AB', 'U+20AD-20CF', 'U+2113', 'U+2C60-2C7F', 'U+A720-A7FF'],
        self::SUBSET_MALAYALAM => ['U+0307', 'U+0323', 'U+0964-0965', 'U+0D02-0D7F', 'U+200C-200D', 'U+20B9', 'U+25CC'],
        self::SUBSET_MYANMAR => ['U+1000-109F', 'U+200C-200D', 'U+25CC'],
        self::SUBSET_ORIYA => ['U+0964-0965', 'U+0B01-0B77', 'U+200C-200D', 'U+20B9', 'U+25CC'],
        self::SUBSET_SINHALA => ['U+0964-0965', 'U+0D82-0DF4', 'U+200C-200D', 'U+25CC'],
        self::SUBSET_TAMIL => ['U+0964-0965', 'U+0B82-0BFA', 'U+200C-200D', 'U+20B9', 'U+25CC'],
        self::SUBSET_TELUGU => ['U+0951-0952', 'U+0964-0965', 'U+0C00-0C7F', 'U+1CDA', 'U+200C-200D', 'U+25CC'],
        self::SUBSET_THAI => ['U+0E01-0E5B', 'U+200C-200D', 'U+25CC'],
        self::SUBSET_TIBETAN => ['U+0F00-0FFF', 'U+200C-200D', 'U+25CC'],
        self::SUBSET_VIETNAMESE => ['U+0102-0103', 'U+0110-0111', 'U+1EA0-1EF9', 'U+20AB'],
    ];

    public static function getLangList()
    {
        return [
            self::LANG_EN => 'English',
            self::LANG_RU => 'Russian',
            self::LANG_KA => 'Georgian',
        ];
    }

    public static function isValidLang($lang)
    {
        return array_key_exists($lang, self::getLangList());
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
            self::SUBSET_MYANMAR => 'Myanmar',
            self::SUBSET_ORIYA => 'Oriya',
            self::SUBSET_SINHALA => 'Sinhala',
            self::SUBSET_TAMIL => 'Tamil',
            self::SUBSET_TELUGU => 'Telugu',
            self::SUBSET_THAI => 'Thai',
            self::SUBSET_TIBETAN => 'Tibetan',
            self::SUBSET_VIETNAMESE => 'Vietnamese',
        ];
    }

    public static function isValidSubset($subset)
    {
        return array_key_exists($subset, self::getSubsetsList());
    }
}
