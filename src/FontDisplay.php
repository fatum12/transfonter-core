<?php

namespace Fatum12\TransfonterCore;

class FontDisplay
{
    const AUTO = 'auto';
    const BLOCK = 'block';
    const SWAP = 'swap';
    const FALLBACK = 'fallback';
    const OPTIONAL = 'optional';

    public static function getList()
    {
        return [
            self::AUTO => self::AUTO,
            self::BLOCK => self::BLOCK,
            self::SWAP => self::SWAP,
            self::FALLBACK => self::FALLBACK,
            self::OPTIONAL => self::OPTIONAL,
        ];
    }
}
