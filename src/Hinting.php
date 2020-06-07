<?php

namespace Fatum12\TransfonterCore;

class Hinting
{
    const KEEP_EXISTING = '';
    const TTFAUTOHINT = 'ttfautohint';
    const DEHINT = 'dehint';

    public static function getList()
    {
        return [
            self::KEEP_EXISTING => 'Keep existing',
            self::TTFAUTOHINT => 'TTFAutohint',
            self::DEHINT => 'Strip hinting',
        ];
    }
}
