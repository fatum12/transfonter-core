<?php

namespace Fatum12\TransfonterCore\Processor;

use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\Storage;
use Fatum12\TransfonterCore\Tools\FontTools;
use Fatum12\TransfonterCore\Language;
use Fatum12\TransfonterCore\Exception\CommandError;

class SubsetsProcessor implements Processor
{
    public function process(Font $font, $dest, Storage $options, Storage $result)
    {
        $subsets = $options->get('subsets', []);
        $characters = trim($options->get('text', ''));
        $userUnicodes = FontTools::parseUnicodes($options->get('unicodes', ''));

        if (
            empty($subsets) &&
            $characters === '' &&
            empty($userUnicodes)
        ) {
            return;
        }

        $unicodes = [];
        foreach ($subsets as $subsetName) {
            $unicodes = array_merge($unicodes, Language::$unicodeRanges[$subsetName]);
        }
        $unicodes = array_merge($unicodes, $userUnicodes);
        // always include space and newline characters
        $characters .= " \n";

        $ttfPath = $result->get(Font::TYPE_TTF);
        $target = $dest . '/subset-' . basename($ttfPath);

        try {
            FontTools::subset($ttfPath, $target, $unicodes, $characters);
        } catch (CommandError $e) {
            // ignore subsetting errors
            @unlink($target);
            return;
        }

        if (file_exists($target) && filesize($target) > 0) {
            unlink($ttfPath);
            $result->set(Font::TYPE_TTF, $target);
        }
    }
}
