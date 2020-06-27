<?php

namespace Fatum12\TransfonterCore\Tools;

use Fatum12\TransfonterCore\Exception\CommandError;
use Fatum12\TransfonterCore\Util\Path;
use Fatum12\TransfonterCore\Util\Shell;

class FontForge
{
    const COMMANDS_PATH = __DIR__ . '/fontforge';

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
        $output = Shell::exec($command);

        $rows = explode("\n", $output);
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
            'fontforge -script "%s/ttc2ttf.pe" "%s"',
            self::COMMANDS_PATH,
            $source
        );
        $output = '';

        try {
            Path::changeDirectory($targetDir, function () use ($command, &$output) {
                $output = Shell::exec($command);
            });
        } catch (CommandError $e) {
            if ($e->getCode() != Shell::STATUS_TIMEOUT && filesize($source) <= 15 * 1000 * 1000) {
                return self::unpackTTCAlter($source);
            }
            throw $e;
        }

        return explode("\n", $output);
    }

    private static function unpackTTCAlter($source)
    {
        $fonts = self::fontsInFile($source);
        $result = [];

        foreach ($fonts as $key => $font) {
            try {
                $command = sprintf(
                    'fontforge -script "%s/ttcExtractOne.pe" "%s" "%s" %s',
                    self::COMMANDS_PATH,
                    $source,
                    $font,
                    str_pad($key + 1, 2, '0', \STR_PAD_LEFT)
                );
                $result[] = Shell::exec($command);
            } catch (CommandError $e) {}
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
        $output = Shell::exec($command);

        return explode("\n", $output);
    }
}
