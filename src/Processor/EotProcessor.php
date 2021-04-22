<?php

namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Context;
use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;
use Fatum12\TransfonterCore\Util\Path;
use Fatum12\TransfonterCore\Tools\Ttf2eot;

class EotProcessor extends Processor
{
    public function process(Font $font, Context $ctx, Storage $result)
    {
        $ttfPath = $result->get(Font::TYPE_TTF);
        $target = $ctx->fontsTargetDir . '/' . Path::filename($ttfPath) . '.eot';
        Ttf2eot::convert($ttfPath, $target);

        $result->set(Font::TYPE_EOT, $target);
    }
}
