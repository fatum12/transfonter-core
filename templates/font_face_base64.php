<?php
$rules = [];
if ($local) {
	$localStr = "local('{$localName}')";
	if ($localPostScriptName != $localName) {
		$localStr .= ", local('{$localPostScriptName}')";
	}
	$rules[] = $localStr;
}
if (isset($woff2)) {
	$rules[] = "url(data:application/font-woff2;charset=utf-8;base64,{$woff2}) format('woff2')";
}
if (isset($woff)) {
	$rules[] = "url(data:application/font-woff;charset=utf-8;base64,{$woff}) format('woff')";
}
if (isset($ttf)) {
	if (isset($woff2) || isset($woff)) {
		$rules[] = "url('{$ttf}') format('truetype')";
	} else {
		$rules[] = "url(data:font/truetype;charset=utf-8;base64,{$ttf}) format('truetype')";
	}
}
if (isset($svg)) {
	$rules[] = "url('{$svg}#{$svgId}') format('svg')";
}
?>
<?php if (isset($eot)):?>
@font-face {
	font-family: '<?= $name ?>';
	src: url('<?= $eot ?>');
	font-weight: <?= $weight ?>;
	font-style: <?= $style ?>;
}
<?php endif;?>
<?php if (!empty($rules) && !$eotOnly):?>
@font-face {
	font-family: '<?= $name ?>';
	src: <?= implode(",\n\t\t", $rules) ?>;
	font-weight: <?= $weight ?>;
	font-style: <?= $style ?>;
}<?php endif;?>