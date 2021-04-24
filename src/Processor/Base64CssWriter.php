<?php

namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;
use Fatum12\TransfonterCore\Util\Path;

class Base64CssWriter extends AbstractCssWriter
{
    protected function getRule(Storage $options, Storage $result, string $format, string $file): string
    {
        if (
            in_array($format, [Font::TYPE_WOFF, Font::TYPE_WOFF2]) ||
            (
                $format == Font::TYPE_TTF &&
                !$result->has(Font::TYPE_WOFF) &&
                !$result->has(Font::TYPE_WOFF2)
            )
        ) {
            return 'data:' . Font::$mimeTypes[$format] . ';charset=utf-8;base64,' . $this->base64($file);
        }
        return Path::join($options->get('fontsDirectory'), basename($file));
    }

    protected function getTemplateName(): string
    {
        return 'font_face_base64';
    }

    private function base64(string $file): string
    {
        return base64_encode(file_get_contents($file));
    }
}
