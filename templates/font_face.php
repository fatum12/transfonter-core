@font-face {
	font-family: '<?= $name ?>';
	src: url('<?= $eot ?>');
	src: <?php if ($local):?>local('<?= $localName ?>'),<?php if ($localPostScriptName != $localName):?> local('<?= $localPostScriptName ?>'),<?php endif;?>
		<?php endif;?>url('<?= $eot ?>?#iefix') format('embedded-opentype'),
<?php if (isset($woff2)):?>
		url('<?= $woff2 ?>') format('woff2'),
<?php endif;?>
<?php if (isset($woff)):?>
		url('<?= $woff ?>') format('woff'),
<?php endif;?>		url('<?= $ttf ?>') format('truetype')<?php if (isset($svg)):?>,
		url('<?= $svg ?>#<?= $svgId ?>') format('svg')<?php endif;?>;
	font-weight: <?= $weight ?>;
	font-style: <?= $style ?>;
}