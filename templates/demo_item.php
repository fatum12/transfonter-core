<?php
use function Fatum12\TransfonterCore\Util\escape;

/** @var string $fontFamily */
/** @var string $fontWeight */
/** @var string $fontStyle */
/** @var string $fontName */
/** @var string[] $letters */
/** @var string $pangram */
/** @var array $preloads */
?>
    <div class="demo">
        <h1 style="font-family: '<?= escape($fontFamily) ?>'; font-weight: <?= $fontWeight ?>; font-style: <?= $fontStyle ?>;"><?= $fontName ?></h1>
        <pre title="Usage">.your-style {
    font-family: '<?= escape($fontFamily) ?>';
    font-weight: <?= $fontWeight ?>;
    font-style: <?= $fontStyle ?>;
}</pre>
<?php if ($preloads): ?>
        <pre title="Preload (optional)">
<?php foreach ($preloads as [$fileName, $mimeType]): ?>
<?= htmlspecialchars("<link rel=\"preload\" href=\"{$fileName}\" as=\"font\" type=\"{$mimeType}\" crossorigin>") ?>
<?php endforeach; ?></pre>
<?php endif; ?>
        <div class="font-container" style="font-family: '<?= escape($fontFamily) ?>'; font-weight: <?= $fontWeight ?>; font-style: <?= $fontStyle ?>;">
            <p class="letters">
                <?= implode("<br>\n", $letters) ?><br>
                0123456789.:,;()*!?'@#&lt;&gt;$%&^+-=~
            </p>
<?php foreach ([10, 11, 12, 14, 18, 24, 30, 36, 48, 60, 72] as $size):?>
            <p class="s<?= $size ?>" style="font-size: <?= $size ?>px;"><?= $pangram ?></p>
<?php endforeach;?>
        </div>
    </div>
