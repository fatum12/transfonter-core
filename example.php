<?php
require __DIR__ . '/vendor/autoload.php';

use Fatum12\TransfonterCore\FontManager;
use Fatum12\TransfonterCore\Font;
use Fatum12\TransfonterCore\TTCUnpacker;

$manager = new FontManager([
	'formats' => [Font::TYPE_WOFF],
	'autohint' => false,
	'demoLanguage' => 'ru',
	'local' => true,
	'base64' => false,
	'fontFamily' => true,
]);
$manager->loadFromDir(__DIR__ . '/fonts');
$manager->process(__DIR__ . '/output');

/*
$ttc = new TTCUnpacker(__DIR__ . '/fonts/Avenir Next.ttc');
$ttc->unpack(__DIR__ . '/output');
*/