<?php

namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;
use Fatum12\TransfonterCore\Util\Path;

class Base64CssWriter extends AbstractCssWriter
{
    private static $mediaTypes = [
        Font::TYPE_TTF => 'font/truetype',
        Font::TYPE_WOFF => 'application/font-woff',
        Font::TYPE_WOFF2 => 'application/font-woff2',
    ];

    protected function getRule(Storage $options, Storage $result, $format, $file)
    {
        if (
            in_array($format, [Font::TYPE_WOFF, Font::TYPE_WOFF2]) ||
            (
                $format == Font::TYPE_TTF &&
                !$result->has(Font::TYPE_WOFF) &&
                !$result->has(Font::TYPE_WOFF2)
            )
        ) {
            return 'data:' . self::$mediaTypes[$format] . ';charset=utf-8;base64,' . $this->base64($file);
        }
        return Path::join($options->get('fontsDirectory'), basename($file));
    }

    protected function getTemplateName()
    {
        return 'font_face_base64';
    }

    private function base64($file)
    {
        return base64_encode(file_get_contents($file));
    }
}
