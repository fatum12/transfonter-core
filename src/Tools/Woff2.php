<?php

namespace Fatum12\TransfonterCore\Tools;

use Fatum12\TransfonterCore\Util\Path;
use Fatum12\TransfonterCore\Util\Shell;

class Woff2
{
    public static function compress(string $source): void
    {
        $command = sprintf('woff2_compress "%s"', $source);
        Shell::exec($command);
    }

    public static function decompress(string $source, string $target = ''): void
    {
        $command = sprintf('woff2_decompress "%s"', $source);
        Shell::exec($command);
        if ($target) {
            $result = dirname($source) . '/' . Path::filename($source) . '.ttf';
            rename($result, $target);
        }
    }
}
