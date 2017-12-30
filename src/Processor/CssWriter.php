<?php
namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Storage;

class CssWriter extends AbstractCssWriter
{
    protected function getRule(Storage $result, $format, $file)
    {
        return basename($file);
    }

    protected function getTemplateName()
    {
        return 'font_face';
    }
}