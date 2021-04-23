<?php

namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Context;
use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;
use Fatum12\TransfonterCore\Util\Path;
use Fatum12\TransfonterCore\Tools\FontForge;

class SvgProcessor extends Processor
{
    public function process(Font $font, Context $ctx, Storage $result): void
    {
        $ttfPath = $result->get(Font::TYPE_TTF);
        $target = $ctx->fontsTargetDir . '/' . Path::filename($ttfPath) . '.svg';
        FontForge::convert($ttfPath, $target);

        $result->set(Font::TYPE_SVG, $target);
    }
}
