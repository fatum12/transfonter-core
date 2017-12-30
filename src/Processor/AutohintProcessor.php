<?php
namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;
use Fatum12\TransfonterCore\Tools\Ttfautohint;
use Fatum12\TransfonterCore\Exception\CommandError;

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
        $hinted = $dest . '/hinted-' . $originalName;

        try {
            Ttfautohint::autohint($ttfPath, $hinted);
        } catch (CommandError $e) {
            // ignore autohint errors
            @unlink($hinted);
            return;
        }

        if (file_exists($hinted)) {
            unlink($ttfPath);
            $result->set(Font::TYPE_TTF, $hinted);
        }
    }
}