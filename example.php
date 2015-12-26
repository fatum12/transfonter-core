<?php
require __DIR__ . '/vendor/autoload.php';

use Fatum12\TransfonterCore\FontManager;
use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\TTCUnpacker;

$timeStart = microtime(true);

$manager = new FontManager([
	'formats' => [Font::TYPE_TTF, Font::TYPE_EOT],
	'autohint' => false,
	'demoLanguage' => 'ru',
	'local' => false,
	'base64' => true,
	'fontFamily' => true,
]);
$manager->loadFromDir(__DIR__ . '/fonts');
$manager->process(__DIR__ . '/output');

/*
$ttc = new TTCUnpacker(__DIR__ . '/fonts/Avenir Next.ttc');
$ttc->unpack(__DIR__ . '/output');
*/

$timeEnd = microtime(true);

echo 'Peak memory usage: ' . memory_get_peak_usage() . "\n";
echo 'Execution time: ' . ($timeEnd - $timeStart) . "\n";