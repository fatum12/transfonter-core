<?php

namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;
use Fatum12\TransfonterCore\Tools\Ttfautohint;
use Fatum12\TransfonterCore\Exception\CommandError;
use Fatum12\TransfonterCore\Util\Path;

class AutohintProcessor implements Processor
{
    public function process(Font $font, $dest, Storage $options, Storage $result)
    {
        $ttfPath = $result->get(Font::TYPE_TTF);
        $originalName = basename($ttfPath);

        // prevent double hinting
        if (strpos($originalName, 'hinted-') === 0) {
            return;
        }
        $hintedPath = Path::uniqueFileName($dest . '/hinted-' . $originalName);

        try {
            Ttfautohint::autohint($ttfPath, $hintedPath);
        } catch (CommandError $e) {
            // ignore autohint errors
            @unlink($hintedPath);
            return;
        }

        if (file_exists($hintedPath)) {
            unlink($ttfPath);
            $result->set(Font::TYPE_TTF, $hintedPath);
        }
    }
}
