<?php

namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Storage;
use Fatum12\TransfonterCore\Util\Path;

class CssWriter extends AbstractCssWriter
{
    protected function getRule(Storage $options, Storage $result, $format, $file)
    {
        return Path::join($options->get('fontsDirectory'), basename($file));
    }

    protected function getTemplateName()
    {
        return 'font_face';
    }
}
