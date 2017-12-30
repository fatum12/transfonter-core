<?php
namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;

class DropTtfProcessor implements Processor
{
    public function process(Font $font, $dest, Storage $options, Storage $result)
    {
        unlink($result->get(Font::TYPE_TTF));
        $result->drop(Font::TYPE_TTF);
    }
}