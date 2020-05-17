<?php

namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;

class Base64CssWriter extends AbstractCssWriter
{
    protected function getRule(Storage $result, $format, $file)
    {
        if (in_array($format, [Font::TYPE_WOFF, Font::TYPE_WOFF2])) {
            return $this->base64($file);
        } elseif (
            $format == Font::TYPE_TTF &&
            !$result->has(Font::TYPE_WOFF) &&
            !$result->has(Font::TYPE_WOFF2)
        ) {
            return $this->base64($file);
        } else {
            return basename($file);
        }
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
