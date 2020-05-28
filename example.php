<?php
require __DIR__ . '/vendor/autoload.php';

use Fatum12\TransfonterCore\FontManager;
use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\TTCUnpacker;
use Fatum12\TransfonterCore\Language;
use Psr\Log\AbstractLogger;

$logger = new class extends AbstractLogger {
    public function log($level, $message, array $context = array())
    {
        $msg = strtoupper($level) . ': ' . $message;
        if ($context) {
            $msg .= ' ' . json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
        fwrite(STDOUT,  $msg);
        fwrite(STDOUT,  "\n");
    }
};
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
$manager->setLogger($logger);
$manager->loadFromDir(__DIR__ . '/fonts');
$manager->process(__DIR__ . '/output');

/*
$ttc = new TTCUnpacker(__DIR__ . '/fonts/Iowan Old Style.ttc');
$ttc->unpack(__DIR__ . '/output');
*/

$timeEnd = microtime(true);

$logger->info('Peak memory usage: ' . (memory_get_peak_usage(true) / 1000) . ' KB');
$logger->info('Execution time: ' . ($timeEnd - $timeStart) . ' s');
