<?php

namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Context;
use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;
use Fatum12\TransfonterCore\Util\Path;
use Fatum12\TransfonterCore\Tools\Sfnt2woff;
use Fatum12\TransfonterCore\Exception\CommandError;
use Fatum12\TransfonterCore\Tools\FontForge;

class WoffProcessor extends Processor
{
    public function process(Font $font, Context $ctx, Storage $result): void
    {
        $ttfPath = $result->get(Font::TYPE_TTF);
        $target = Path::uniqueFileName($ctx->fontsTargetDir . '/' . Path::filename($ttfPath) . '.woff');
        FontForge::convert($ttfPath, $target);

        $result->set(Font::TYPE_WOFF, $target);
    }
}
