<?php
use function Fatum12\TransfonterCore\Util\escape;
?>
    <div class="demo">
        <h1 style="font-family: '<?= escape($fontFamily) ?>'; font-weight: <?= $fontWeight ?>; font-style: <?= $fontStyle ?>;"><?= $fontName ?></h1>
        <pre>.your-style {
    font-family: '<?= escape($fontFamily) ?>';
    font-weight: <?= $fontWeight ?>;
    font-style: <?= $fontStyle ?>;
}</pre>
        <div class="font-container" style="font-family: '<?= escape($fontFamily) ?>'; font-weight: <?= $fontWeight ?>; font-style: <?= $fontStyle ?>;">
            <p class="letters">
                <?= implode("<br>\n", $letters) ?><br>
                0123456789.:,;()*!?'@#<>$%&^+-=~
            </p>
<?php foreach ([10, 11, 12, 14, 18, 24, 30, 36, 48, 60, 72] as $size):?>
            <p class="s<?= $size ?>" style="font-size: <?= $size ?>px;"><?= $string ?></p>
<?php endforeach;?>
        </div>
    </div>