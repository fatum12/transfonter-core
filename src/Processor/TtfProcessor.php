<?php

namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;
use Fatum12\TransfonterCore\Util\Path;
use Fatum12\TransfonterCore\Tools\FontForge;
use Fatum12\TransfonterCore\Exception\FileNotFound;

class TtfProcessor implements Processor
{
    public function process(Font $font, $dest, Storage $options, Storage $result)
    {
        $target = Path::uniqueFileName($dest . '/' . $font->getSafeName() . '.ttf');

        if ($font->getType() == Font::TYPE_TTF) {
            // font is TTF - copy to new path
            copy($font->getPath(), $target);
        } else {
            // try to convert to TTF
            FontForge::convert($font->getPath(), $target);
        }

        if (!file_exists($target)) {
            throw new FileNotFound("Can't convert to ttf: $target");
        }

        $result->set(Font::TYPE_TTF, $target);
    }
}
