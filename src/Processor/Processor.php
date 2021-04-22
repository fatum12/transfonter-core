<?php

namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Context;
use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;

abstract class Processor
{
    abstract public function process(Font $font, Context $ctx, Storage $result);

    public function finalize(Context $ctx): void
    {
    }
}
