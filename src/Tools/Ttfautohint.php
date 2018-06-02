<?php
namespace Fatum12\TransfonterCore\Tools;

use Fatum12\TransfonterCore\Util\Shell;

class Ttfautohint
{
    public static function autohint($source, $target)
    {
        $command = sprintf(
            'ttfautohint --windows-compatibility --composites -i -X "-" "%s" "%s"',
            $source,
            $target
        );
        Shell::exec($command);
    }
}