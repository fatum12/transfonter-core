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
if (isset($eot)) {
    $rules[] = "url('{$eot}?#iefix') format('embedded-opentype')";
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
@font-face {
    font-family: '<?= escape($name) ?>';
<?php if (isset($eot)):?>
    src: url('<?= $eot ?>');
<?php endif;?>
<?php if (!empty($rules) && !$eotOnly):?>
    src: <?= implode(",\n        ", $rules) ?>;
<?php endif;?>
    font-weight: <?= $weight ?>;
    font-style: <?= $style ?>;
<?php if (isset($display)):?>
    font-display: <?= $display ?>;
<?php endif;?>
}
