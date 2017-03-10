<?php

namespace Fatum12\TransfonterCore;


class Language
{
    const LANG_EN = 'en';
    const LANG_RU = 'ru';

    public static function getLangList()
    {
        return [
            self::LANG_EN => 'English',
            self::LANG_RU => 'Russian',
        ];
    }

    public static function isValidLang($lang)
    {
        return in_array($lang, array_keys(self::getLangList()));
    }
}