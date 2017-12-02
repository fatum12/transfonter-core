<?php
namespace Fatum12\TransfonterCore\Tools;

use Fatum12\TransfonterCore\Util\Shell;

class FontTools
{
    public static function subset($source, $target, array $unicodes = [], $characters = '')
    {
        $command = sprintf(
            "pyftsubset '%s' --unicodes='%s' --text='%s' --ignore-missing-unicodes --ignore-missing-glyphs " .
            "--output-file='%s' --glyph-names --symbol-cmap --legacy-cmap --notdef-glyph --notdef-outline " .
            "--recommended-glyphs --name-IDs='*' --name-legacy --name-languages='*'",
            $source,
            implode(',', $unicodes),
            Shell::escapeArg($characters),
            $target
        );
        Shell::exec($command);
    }

    public static function parseUnicodes($str)
    {
        $items = preg_split('/[\s\t\n,;]/', $str, -1, \PREG_SPLIT_NO_EMPTY);
        $result = [];
        foreach ($items as $item) {
            if (preg_match('/^(u|U\+)?[0-9a-fA-F]{1,4}(\-[0-9a-fA-F]{1,4})?$/', $item)) {
                $result[] = $item;
            }
        }
        return $result;
    }

    public static function fixVerticalMetrics($source)
    {
        $command = sprintf(
            "%s --autofix '%s'",
            __DIR__ . '/bin/gftools-fix-vertical-metrics.py',
            $source
        );
        Shell::exec($command);
    }
}