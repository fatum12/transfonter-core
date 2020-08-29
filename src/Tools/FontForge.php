<?php

namespace Fatum12\TransfonterCore\Tools;

use Fatum12\TransfonterCore\Exception\CommandError;
use Fatum12\TransfonterCore\Util\Path;
use Fatum12\TransfonterCore\Util\Shell;

class FontForge
{
    const COMMANDS_PATH = __DIR__ . '/fontforge';
    const UNPACK_ALTER_SIZE_LIMIT = 15 * 1000 * 1000;

    public static function convert($source, $target)
    {
        $command = sprintf(
            'fontforge -script "%s/2format.pe" "%s" "%s"',
            self::COMMANDS_PATH,
            $source,
            $target
        );
        Shell::exec($command);
    }

    public static function getFontInfo($fontPath)
    {
        $command = sprintf(
            'fontforge -script "%s/getFontInfo.pe" "%s"',
            self::COMMANDS_PATH,
            $fontPath
        );

        $rows = Shell::exec($command);
        $result = [];
        foreach ($rows as $row) {
            $delimiterPos = strpos($row, ':');
            $key = trim(substr($row, 0, $delimiterPos));
            $value = trim(substr($row, $delimiterPos + 1));

            $result[$key] = $value;
        }
        return $result;
    }

    public static function unpackTTC($source, $targetDir)
    {
        $command = sprintf(
            'fontforge -script "%s/ttc2ttf.pe" "%s" "%s"',
            self::COMMANDS_PATH,
            $source,
            $targetDir
        );

        try {
            $result = Shell::exec($command);
        } catch (CommandError $e) {
            if ($e->getCode() != Shell::STATUS_TIMEOUT && filesize($source) <= self::UNPACK_ALTER_SIZE_LIMIT) {
                return self::unpackTTCAlter($source, $targetDir);
            }
            throw $e;
        }

        return $result;
    }

    private static function unpackTTCAlter($source, $targetDir)
    {
        $fonts = self::fontsInFile($source);
        $result = [];

        foreach ($fonts as $key => $font) {
            $command = sprintf(
                'fontforge -script "%s/ttcExtractOne.pe" "%s" "%s" %s "%s"',
                self::COMMANDS_PATH,
                $source,
                $font,
                str_pad($key + 1, 2, '0', \STR_PAD_LEFT),
                $targetDir
            );
            try {
                $output = Shell::exec($command);
                if ($output) {
                    $result[] = array_shift($output);
                }
            } catch (CommandError $e) {
            }
        }

        return $result;
    }

    public static function fontsInFile($source)
    {
        $command = sprintf(
            'fontforge -script "%s/ttcFontsList.pe" "%s"',
            self::COMMANDS_PATH,
            $source
        );
        return Shell::exec($command);
    }
}
