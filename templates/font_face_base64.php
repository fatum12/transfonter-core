<?php
use function Fatum12\TransfonterCore\Util\escape;

$rules = [];
if ($local) {
    $localStr = "local('" . escape($localName) . "')";
    if ($localPostScriptName != $localName) {
        $localStr .= ", local('" . escape($localPostScriptName) . "')";
    }
    $rules[] = $localStr;
}
if (isset($woff2)) {
    $rules[] = "url('{$woff2}') format('woff2')";
}
if (isset($woff)) {
    $rules[] = "url('{$woff}') format('woff')";
}
if (isset($ttf)) {
    $rules[] = "url('{$ttf}') format('truetype')";
}
if (isset($svg)) {
    $rules[] = "url('{$svg}#" . escape($svgId) . "') format('svg')";
}
?>
<?php if (isset($eot)):?>
@font-face {
    font-family: '<?= escape($name) ?>';
    src: url('<?= $eot ?>');
    font-weight: <?= $weight ?>;
    font-style: <?= $style ?>;
}
<?php endif;?>
<?php if (!empty($rules) && !$eotOnly):?>
@font-face {
    font-family: '<?= escape($name) ?>';
    src: <?= implode(",\n        ", $rules) ?>;
    font-weight: <?= $weight ?>;
    font-style: <?= $style ?>;
<?php if (isset($display)):?>
    font-display: <?= $display ?>;
<?php endif;?>
}
<?php endif;?>