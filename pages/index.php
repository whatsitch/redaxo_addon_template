<?php
$addon = Addon::getInstance();

echo rex_view::title($addon->rex_addon->i18n('title'));

$subPages = rex_be_controller::getCurrentPagePart();


var_dump($subPages);
var_dump(Addon::getPackageName());

rex_be_controller::includeCurrentPageSubPath();