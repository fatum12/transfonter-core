<?php
require __DIR__ . '/vendor/autoload.php';

use Fatum12\TransfonterCore\FontManager;
use Fatum12\TransfonterCore\Font;

$manager = new FontManager([
	'formats' => [Font::TYPE_WOFF, Font::TYPE_WOFF2, Font::TYPE_SVG],
	'autohint' => true,
]);
$manager->loadFromDir(__DIR__ . '/fonts');
$manager->process(__DIR__ . '/output');