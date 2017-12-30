<?php
namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;
use Fatum12\TransfonterCore\Util\Path;
use Fatum12\TransfonterCore\Tools\Ttf2eot;

class EotProcessor implements Processor
{
    public function process(Font $font, $dest, Storage $options, Storage $result)
    {
        $ttfPath = $result->get(Font::TYPE_TTF);
        $target = $dest . '/' . Path::filename($ttfPath) . '.eot';
        Ttf2eot::convert($ttfPath, $target);

        $result->set(Font::TYPE_EOT, $target);
    }
}