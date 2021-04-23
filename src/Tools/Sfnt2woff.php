<?php

namespace Fatum12\TransfonterCore\Tools;

use Fatum12\TransfonterCore\Util\Shell;

class Sfnt2woff
{
    public static function convert(string $source): void
    {
        $command = sprintf('sfnt2woff "%s"', $source);
        Shell::exec($command);
    }
}
