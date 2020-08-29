<?php

namespace Fatum12\TransfonterCore;

use Fatum12\TransfonterCore\Exception\ArgumentException;
use Fatum12\TransfonterCore\Exception\FileNotFound;
use Fatum12\TransfonterCore\Tools\FontForge;
use Fatum12\TransfonterCore\Util\Path;

class TTCUnpacker extends File
{
    const TYPE_TTC = 'ttc';
    const TYPE_DFONT = 'dfont';

    protected static $magic = [
        self::TYPE_TTC => 'ttcf',
        self::TYPE_DFONT => "\x00\x00\x01\x00\x00",
    ];

    public function __construct($path)
    {
        if (!is_file($path)) {
            throw new FileNotFound("File not found: {$path}");
        }
        $this->path = realpath($path);
        if (!in_array($this->getType(), [self::TYPE_TTC, self::TYPE_DFONT])) {
            throw new ArgumentException("Wrong font collection type: {$path}");
        }
    }

    public function unpack($dest = null)
    {
        if ($dest === null) {
            $dest = dirname($this->path);
        } else {
            Path::mkdir($dest);
        }

        $fonts = FontForge::unpackTTC($this->path, $dest);

        $files = [];
        foreach ($fonts as $font) {
            if (is_file($dest . '/' . $font)) {
                $files[] = $dest . '/' . $font;
            }
        }

        return $files;
    }
}
