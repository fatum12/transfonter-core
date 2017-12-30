<?php
require __DIR__ . '/vendor/autoload.php';

use Fatum12\TransfonterCore\FontManager;
use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\TTCUnpacker;
use Fatum12\TransfonterCore\Language;

$timeStart = microtime(true);

$manager = new FontManager([
    'formats' => [Font::TYPE_TTF, Font::TYPE_EOT, Font::TYPE_WOFF, Font::TYPE_WOFF2, Font::TYPE_SVG],
    'subsets' => [Language::SUBSET_CYRILLIC, Language::SUBSET_LATIN],
    'autohint' => true,
    'demoLanguage' => Language::LANG_EN,
    'local' => true,
    'base64' => false,
    'fontFamily' => true,
    'fixVerticalMetrics' => true,
]);
$manager->loadFromDir(__DIR__ . '/fonts');
$manager->process(__DIR__ . '/output');

/*
$ttc = new TTCUnpacker(__DIR__ . '/fonts/Iowan Old Style.ttc');
$ttc->unpack(__DIR__ . '/output');
*/

$timeEnd = microtime(true);

echo 'Peak memory usage: ' . (memory_get_peak_usage(true) / 1000) . " KB\n";
echo 'Execution time: ' . ($timeEnd - $timeStart) . "\n";