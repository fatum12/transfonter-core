<?php

namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Context;
use Fatum12\TransfonterCore\Exception\CommandError;
use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;
use Fatum12\TransfonterCore\Tools\Ttfautohint;
use Fatum12\TransfonterCore\Util\Path;

class DehintProcessor extends Processor
{
    public function process(Font $font, Context $ctx, Storage $result)
    {
        $ttfPath = $result->get(Font::TYPE_TTF);
        $dehintedPath = Path::uniqueFileName($ctx->fontsTargetDir . '/dehinted-' . basename($ttfPath));

        try {
            Ttfautohint::dehint($ttfPath, $dehintedPath);
        } catch (CommandError $e) {
            // ignore errors
            @unlink($dehintedPath);
            return;
        }

        if (file_exists($dehintedPath)) {
            unlink($ttfPath);
            $result->set(Font::TYPE_TTF, $dehintedPath);
        }
    }
}
