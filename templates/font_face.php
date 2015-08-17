@font-face {
	font-family: '<?= $name ?>';
	src: url('<?= $eot ?>');
	src: url('<?= $eot ?>?#iefix') format('embedded-opentype'),<?php if (isset($woff)):?>
		url('<?= $woff ?>') format('woff'),
<?php endif;?>		url('<?= $ttf ?>') format('truetype')<?php if (isset($svg)):?>,
		url('<?= $svg ?>#<?= $svgId ?>') format('svg')<?php endif;?>;
	font-weight: <?= $weight ?>;
	font-style: <?= $style ?>;
}