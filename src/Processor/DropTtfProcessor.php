<?php

namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Context;
use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;

class DropTtfProcessor extends Processor
{
    public function process(Font $font, Context $ctx, Storage $result): void
    {
        unlink($result->get(Font::TYPE_TTF));
        $result->drop(Font::TYPE_TTF);
    }
}
