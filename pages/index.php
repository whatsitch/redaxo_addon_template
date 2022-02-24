<?php
$addon = Addon::getInstance();

echo rex_view::title($addon->rex_addon->i18n('title'));

$subPages = rex_be_controller::getCurrentPagePart();

rex_be_controller::includeCurrentPageSubPath();