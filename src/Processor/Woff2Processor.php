<?php

namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Context;
use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;
use Fatum12\TransfonterCore\Tools\FontForge;
use Fatum12\TransfonterCore\Tools\Woff2;
use Fatum12\TransfonterCore\Util\Path;

class Woff2Processor extends Processor
{
    public function process(Font $font, Context $ctx, Storage $result): void
    {
        $ttfPath = $result->get(Font::TYPE_TTF);
        $target = Path::uniqueFileName($ctx->fontsTargetDir . '/' . Path::filename($ttfPath) . '.woff2');
        FontForge::convert($ttfPath, $target);

        $result->set(Font::TYPE_WOFF2, $target);
    }
}
