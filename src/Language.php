<?php

namespace Fatum12\TransfonterCore;

class Language
{
    const LANG_DE = 'de';
    const LANG_EN = 'en';
    const LANG_ES = 'es';
    const LANG_FR = 'fr';
    const LANG_KA = 'ka';
    const LANG_PT = 'pt';
    const LANG_RU = 'ru';

    const SUBSET_ARABIC = 'arabic';
    const SUBSET_ARMENIAN = 'armenian';
    const SUBSET_BENGALI = 'bengali';
    const SUBSET_CYRILLIC = 'cyrillic';
    const SUBSET_CYRILLIC_EXT = 'cyrillic-ext';
    const SUBSET_DEVANAGARI = 'devanagari';
    const SUBSET_GEORGIAN = 'georgian';
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
    const SUBSET_MATH = 'math';
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
        self::SUBSET_ARABIC => ['U+0600-06FF', 'U+0750-077F', 'U+0870-088E', 'U+0890-0891', 'U+0897-08E1', 'U+08E3-08FF', 'U+200C-200E', 'U+2010-2011', 'U+204F', 'U+2E41', 'U+FB50-FDFF', 'U+FE70-FE74', 'U+FE76-FEFC', 'U+102E0-102FB', 'U+10E60-10E7E', 'U+10EC2-10EC4', 'U+10EFC-10EFF', 'U+1EE00-1EE03', 'U+1EE05-1EE1F', 'U+1EE21-1EE22', 'U+1EE24', 'U+1EE27', 'U+1EE29-1EE32', 'U+1EE34-1EE37', 'U+1EE39', 'U+1EE3B', 'U+1EE42', 'U+1EE47', 'U+1EE49', 'U+1EE4B', 'U+1EE4D-1EE4F', 'U+1EE51-1EE52', 'U+1EE54', 'U+1EE57', 'U+1EE59', 'U+1EE5B', 'U+1EE5D', 'U+1EE5F', 'U+1EE61-1EE62', 'U+1EE64', 'U+1EE67-1EE6A', 'U+1EE6C-1EE72', 'U+1EE74-1EE77', 'U+1EE79-1EE7C', 'U+1EE7E', 'U+1EE80-1EE89', 'U+1EE8B-1EE9B', 'U+1EEA1-1EEA3', 'U+1EEA5-1EEA9', 'U+1EEAB-1EEBB', 'U+1EEF0-1EEF1'],
        self::SUBSET_ARMENIAN => ['U+0308', 'U+0530-058F', 'U+2010', 'U+2024', 'U+25CC', 'U+FB13-FB17'],
        self::SUBSET_BENGALI => ['U+0951-0952', 'U+0964-0965', 'U+0980-09FE', 'U+1CD0', 'U+1CD2', 'U+1CD5-1CD6', 'U+1CD8', 'U+1CE1', 'U+1CEA', 'U+1CED', 'U+1CF2', 'U+1CF5-1CF7', 'U+200C-200D', 'U+20B9', 'U+25CC', 'U+A8F1'],
        self::SUBSET_CYRILLIC => ['U+0301', 'U+0400-045F', 'U+0490-0491', 'U+04B0-04B1', 'U+2116'],
        self::SUBSET_CYRILLIC_EXT => ['U+0460-052F', 'U+1C80-1C8A', 'U+20B4', 'U+2DE0-2DFF', 'U+A640-A69F', 'U+FE2E-FE2F'],
        self::SUBSET_DEVANAGARI => ['U+0900-097F', 'U+1CD0-1CF9', 'U+200C-200D', 'U+20A8', 'U+20B9', 'U+20F0', 'U+25CC', 'U+A830-A839', 'U+A8E0-A8FF', 'U+11B00-11B09'],
        self::SUBSET_GEORGIAN => ['U+0589', 'U+10A0-10FF', 'U+1C90-1CBA', 'U+1CBD-1CBF', 'U+205A', 'U+2D00-2D2F', 'U+2E31'],
        self::SUBSET_GREEK => ['U+0370-0377', 'U+037A-037F', 'U+0384-038A', 'U+038C', 'U+038E-03A1', 'U+03A3-03FF'],
        self::SUBSET_GREEK_EXT => ['U+1F00-1FFF'],
        self::SUBSET_GUJARATI => ['U+0951-0952', 'U+0964-0965', 'U+0A80-0AFF', 'U+200C-200D', 'U+20B9', 'U+25CC', 'U+A830-A839'],
        self::SUBSET_GURMUKHI => ['U+0951-0952', 'U+0964-0965', 'U+0A01-0A76', 'U+200C-200D', 'U+20B9', 'U+25CC', 'U+262C', 'U+A830-A839'],
        self::SUBSET_HEBREW => ['U+0307-0308', 'U+0590-05FF', 'U+200C-2010', 'U+20AA', 'U+25CC', 'U+FB1D-FB4F'],
        self::SUBSET_KANNADA => ['U+0951-0952', 'U+0964-0965', 'U+0C80-0CF3', 'U+1CD0', 'U+1CD2-1CD3', 'U+1CDA', 'U+1CF2', 'U+1CF4', 'U+200C-200D', 'U+20B9', 'U+25CC', 'U+A830-A835'],
        self::SUBSET_KHMER => ['U+1780-17FF', 'U+19E0-19FF', 'U+200C-200D', 'U+25CC', 'U+0000-00FF', 'U+0131', 'U+0152-0153', 'U+02BB-02BC', 'U+02C6', 'U+02DA', 'U+02DC', 'U+0304', 'U+0308', 'U+0329', 'U+2000-206F', 'U+20AC', 'U+2122', 'U+2191', 'U+2193', 'U+2212', 'U+2215', 'U+FEFF', 'U+FFFD'],
        self::SUBSET_LATIN => ['U+0000-00FF', 'U+0131', 'U+0152-0153', 'U+02BB-02BC', 'U+02C6', 'U+02DA', 'U+02DC', 'U+0304', 'U+0308', 'U+0329', 'U+2000-206F', 'U+2074', 'U+20AC', 'U+2122', 'U+2191', 'U+2193', 'U+2212', 'U+2215', 'U+FEFF', 'U+FFFD'],
        self::SUBSET_LATIN_EXT => ['U+0100-02BA', 'U+02BD-02C5', 'U+02C7-02CC', 'U+02CE-02D7', 'U+02DD-02FF', 'U+0304', 'U+0308', 'U+0329', 'U+1D00-1DBF', 'U+1E00-1E9F', 'U+1EF2-1EFF', 'U+2020', 'U+20A0-20AB', 'U+20AD-20C0', 'U+2113', 'U+2C60-2C7F', 'U+A720-A7FF'],
        self::SUBSET_MALAYALAM => ['U+0307', 'U+0323', 'U+0951-0952', 'U+0964-0965', 'U+0D00-0D7F', 'U+1CDA', 'U+1CF2', 'U+200C-200D', 'U+20B9', 'U+25CC', 'U+A830-A832'],
        self::SUBSET_MATH => ['U+0302-0303', 'U+0305', 'U+0307-0308', 'U+0310', 'U+0312', 'U+0315', 'U+031A', 'U+0326-0327', 'U+032C', 'U+032F-0330', 'U+0332-0333', 'U+0338', 'U+033A', 'U+0346', 'U+034D', 'U+0391-03A1', 'U+03A3-03A9', 'U+03B1-03C9', 'U+03D1', 'U+03D5-03D6', 'U+03F0-03F1', 'U+03F4-03F5', 'U+2016-2017', 'U+2034-2038', 'U+203C', 'U+2040', 'U+2043', 'U+2047', 'U+2050', 'U+2057', 'U+205F', 'U+2070-2071', 'U+2074-208E', 'U+2090-209C', 'U+20D0-20DC', 'U+20E1', 'U+20E5-20EF', 'U+2100-2112', 'U+2114-2115', 'U+2117-2121', 'U+2123-214F', 'U+2190', 'U+2192', 'U+2194-21AE', 'U+21B0-21E5', 'U+21F1-21F2', 'U+21F4-2211', 'U+2213-2214', 'U+2216-22FF', 'U+2308-230B', 'U+2310', 'U+2319', 'U+231C-2321', 'U+2336-237A', 'U+237C', 'U+2395', 'U+239B-23B7', 'U+23D0', 'U+23DC-23E1', 'U+2474-2475', 'U+25AF', 'U+25B3', 'U+25B7', 'U+25BD', 'U+25C1', 'U+25CA', 'U+25CC', 'U+25FB', 'U+266D-266F', 'U+27C0-27FF', 'U+2900-2AFF', 'U+2B0E-2B11', 'U+2B30-2B4C', 'U+2BFE', 'U+3030', 'U+FF5B', 'U+FF5D', 'U+1D400-1D7FF', 'U+1EE00-1EEFF'],
        self::SUBSET_MYANMAR => ['U+1000-109F', 'U+200C-200D', 'U+25CC', 'U+A92E', 'U+A9E0-A9FE', 'U+AA60-AA7F', 'U+116D0-116E3', 'U+0000-00FF', 'U+0131', 'U+0152-0153', 'U+02BB-02BC', 'U+02C6', 'U+02DA', 'U+02DC', 'U+0304', 'U+0308', 'U+0329', 'U+2000-206F', 'U+20AC', 'U+2122', 'U+2191', 'U+2193', 'U+2212', 'U+2215', 'U+FEFF', 'U+FFFD'],
        self::SUBSET_ORIYA => ['U+0951-0952', 'U+0964-0965', 'U+0B01-0B77', 'U+1CDA', 'U+1CF2', 'U+200C-200D', 'U+20B9', 'U+25CC'],
        self::SUBSET_SINHALA => ['U+0964-0965', 'U+0D81-0DF4', 'U+1CF2', 'U+200C-200D', 'U+25CC', 'U+111E1-111F4'],
        self::SUBSET_TAMIL => ['U+0964-0965', 'U+0B82-0BFA', 'U+200C-200D', 'U+20B9', 'U+25CC', 'U+0000-00FF', 'U+0131', 'U+0152-0153', 'U+02BB-02BC', 'U+02C6', 'U+02DA', 'U+02DC', 'U+0304', 'U+0308', 'U+0329', 'U+2000-206F', 'U+20AC', 'U+2122', 'U+2191', 'U+2193', 'U+2212', 'U+2215', 'U+FEFF', 'U+FFFD'],
        self::SUBSET_TELUGU => ['U+0951-0952', 'U+0964-0965', 'U+0C00-0C7F', 'U+1CDA', 'U+1CF2', 'U+200C-200D', 'U+25CC'],
        self::SUBSET_THAI => ['U+02D7', 'U+0303', 'U+0331', 'U+0E01-0E5B', 'U+200C-200D', 'U+25CC'],
        self::SUBSET_TIBETAN => ['U+0F00-0FFF', 'U+200C-200D', 'U+25CC', 'U+3008-300B'],
        self::SUBSET_VIETNAMESE => ['U+0102-0103', 'U+0110-0111', 'U+0128-0129', 'U+0168-0169', 'U+01A0-01A1', 'U+01AF-01B0', 'U+0300-0301', 'U+0303-0304', 'U+0308-0309', 'U+0323', 'U+0329', 'U+1EA0-1EF9', 'U+20AB'],
    ];

    public static function getLangList(): array
    {
        return [
            self::LANG_EN => 'English',
            self::LANG_FR => 'French',
            self::LANG_KA => 'Georgian',
            self::LANG_DE => 'German',
            self::LANG_PT => 'Portuguese',
            self::LANG_RU => 'Russian',
            self::LANG_ES => 'Spanish',
        ];
    }

    public static function isValidLang($lang): bool
    {
        return array_key_exists($lang, self::getLangList());
    }

    public static function getSubsetsList(): array
    {
        return [
            self::SUBSET_ARABIC => 'Arabic',
            self::SUBSET_ARMENIAN => 'Armenian',
            self::SUBSET_BENGALI => 'Bengali',
            self::SUBSET_CYRILLIC => 'Cyrillic',
            self::SUBSET_CYRILLIC_EXT => 'Cyrillic Extended',
            self::SUBSET_DEVANAGARI => 'Devanagari',
            self::SUBSET_GEORGIAN => 'Georgian',
            self::SUBSET_GREEK => 'Greek',
            self::SUBSET_GREEK_EXT => 'Greek Extended',
            self::SUBSET_GUJARATI => 'Gujarati',
            self::SUBSET_GURMUKHI => 'Gurmukhi',
            self::SUBSET_HEBREW => 'Hebrew',
            self::SUBSET_KANNADA => 'Kannada',
            self::SUBSET_KHMER => 'Khmer',
            self::SUBSET_LATIN => 'Latin',
            self::SUBSET_LATIN_EXT => 'Latin Extended',
            self::SUBSET_MALAYALAM => 'Malayalam',
            self::SUBSET_MATH => 'Math',
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

    public static function isValidSubset($subset): bool
    {
        return array_key_exists($subset, self::getSubsetsList());
    }
}
