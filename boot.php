<?php


/*----- -----*/
if (rex::isBackend() && rex::getUser()) {
    // $addon = Addon::getInstance();
    // $addon->includeFile('functions/backend_functions.php');
}

/*----- -----*/
if (rex::isFrontend()) {
    // $addon = Addon::getInstance();
    // $addon->includeFile('functions/frontend_functions.php');
}
