<?php

namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Storage;
use Fatum12\TransfonterCore\Util\Path;

class CssWriter extends AbstractCssWriter
{
    protected function getRule(Storage $options, Storage $result, string $format, string $file): string
    {
        return Path::join($options->get('fontsDirectory'), basename($file));
    }

    protected function getTemplateName(): string
    {
        return 'font_face';
    }
}
