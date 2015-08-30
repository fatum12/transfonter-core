	<div class="demo-<?= $index ?>">
		<h1><?= $fontName ?></h1>
		<div class="font-container">
			<p class="letters">
				<?= $letters ?>
				0123456789.:,;()*!?'@#<>$%&^+-=~
			</p>
			<?php foreach ([10, 11, 12, 14, 18, 24, 30, 36, 48, 60, 72] as $size):?>
			<p style="font-size: <?= $size ?>px;"><?= $string ?></p>
			<?php endforeach;?>
		</div>
	</div>