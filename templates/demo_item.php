	<div class="demo" style="font-family: '<?= $fontFamily ?>'; font-weight: <?= $fontWeight ?>; font-style: <?= $fontStyle ?>;">
		<h1><?= $fontName ?></h1>
		<pre>.your-style {
    font-family: '<?= $fontFamily ?>';
    font-weight: <?= $fontWeight ?>;
    font-style: <?= $fontStyle ?>;
}</pre>
		<div class="font-container">
			<p class="letters">
				<?= $letters ?>
				0123456789.:,;()*!?'@#<>$%&^+-=~
			</p>
<?php foreach ([10, 11, 12, 14, 18, 24, 30, 36, 48, 60, 72] as $size):?>
			<p class="s<?= $size ?>" style="font-size: <?= $size ?>px;"><?= $string ?></p>
<?php endforeach;?>
		</div>
	</div>