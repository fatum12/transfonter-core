<?php

namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;
use Fatum12\TransfonterCore\Tools\Woff2;
use Fatum12\TransfonterCore\Util\Path;

class Woff2Processor implements Processor
{
    public function process(Font $font, $dest, Storage $options, Storage $result)
    {
        $ttfPath = $result->get(Font::TYPE_TTF);
        $target = $dest . '/' . Path::filename($ttfPath) . '.woff2';
        Woff2::compress($ttfPath);

        $result->set(Font::TYPE_WOFF2, $target);
    }
}
