<?php

namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;
use Fatum12\TransfonterCore\Util\Path;
use Fatum12\TransfonterCore\Tools\Sfnt2woff;
use Fatum12\TransfonterCore\Exception\CommandError;
use Fatum12\TransfonterCore\Tools\FontForge;

class WoffProcessor implements Processor
{
    public function process(Font $font, $dest, Storage $options, Storage $result)
    {
        $ttfPath = $result->get(Font::TYPE_TTF);
        $target = $dest . '/' . Path::filename($ttfPath) . '.woff';

        try {
            Sfnt2woff::convert($ttfPath);
        } catch (CommandError $e) {
            // fallback
            @unlink($target);
            FontForge::convert($ttfPath, $target);
        }

        $result->set(Font::TYPE_WOFF, $target);
    }
}
