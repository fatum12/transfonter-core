<?php
namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;
use Fatum12\TransfonterCore\Tools\FontForge;

class FixMetaProcessor implements Processor
{
    public function process(Font $font, $dest, Storage $options, Storage $result)
    {
        FontForge::fixMeta($result->get(Font::TYPE_TTF));
    }
}