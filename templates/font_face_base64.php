@font-face {
	font-family: '<?= $name ?>';
	src: url('<?= $eot ?>');
	font-weight: <?= $weight ?>;
	font-style: <?= $style ?>;
}

@font-face {
	font-family: '<?= $name ?>';
	src: <?php if ($local):?>local('<?= $localName ?>')<?php if ($localPostScriptName != $localName):?>, local('<?= $localPostScriptName ?>')<?php endif;?>,
		<?php endif;?>
<?php if (isset($woff2)):?>url(data:application/font-woff2;charset=utf-8;base64,<?= $woff2 ?>) format('woff2'),
		<?php endif;?>
<?php if (isset($woff)):?>url(data:application/font-woff;charset=utf-8;base64,<?= $woff ?>) format('woff'),
		<?php endif;?>
<?php if (isset($woff2) || isset($woff)):?>url('<?= $ttf ?>')<?php else:?>
url(data:font/truetype;charset=utf-8;base64,<?= $ttf ?>)<?php endif;?> format('truetype')<?php if (isset($svg)):?>,
		url('<?= $svg ?>#<?= $svgId ?>') format('svg')<?php endif;?>;
	font-weight: <?= $weight ?>;
	font-style: <?= $style ?>;
}