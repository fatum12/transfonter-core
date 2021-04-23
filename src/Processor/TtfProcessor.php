<?php

namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Context;
use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;
use Fatum12\TransfonterCore\Util\Path;
use Fatum12\TransfonterCore\Tools\FontForge;
use Fatum12\TransfonterCore\Exception\FileNotFound;

class TtfProcessor extends Processor
{
    public function process(Font $font, Context $ctx, Storage $result): void
    {
        $target = Path::uniqueFileName($ctx->fontsTargetDir . '/' . $font->getSafeName() . '.ttf');

        FontForge::convert($font->getPath(), $target);

        if (!file_exists($target)) {
            throw new FileNotFound("Can't convert to ttf: $target");
        }

        $result->set(Font::TYPE_TTF, $target);
    }
}
