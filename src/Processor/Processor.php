<?php
namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;

interface Processor
{
    public function process(Font $font, $dest, Storage $options, Storage $result);
}