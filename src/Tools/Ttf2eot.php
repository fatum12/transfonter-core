<?php

namespace Fatum12\TransfonterCore\Tools;

use Fatum12\TransfonterCore\Util\Shell;

class Ttf2eot
{
    public static function convert(string $source, string $target): void
    {
        $command = sprintf(
            'ttf2eot "%s" > "%s"',
            $source,
            $target
        );
        Shell::exec($command);
    }
}
