<?php
require __DIR__ . '/vendor/autoload.php';

use Fatum12\TransfonterCore\Font;

$html = '<table border="1">';
foreach (glob(__DIR__ . '/fonts/*.{ttf,otf}', GLOB_BRACE) as $file) {
	$font = new Font($file);
	$html .= "<tr>
	<td>{$font->getFileName()}</td>
	<td>{$font->getName()}</td>
	<td>{$font->getFullName()}</td>
	<td>{$font->getFamilyName()}</td>
	<td>{$font->getWight()}</td>
	<td>{$font->getStyle()}</td>
</tr>";
}
$html .= '</table>';

file_put_contents(__DIR__ . '/output/result.html', $html);