<?php

include '../../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsmodule.php';
require XOOPS_ROOT_PATH . '/include/cp_functions.php';

global $xoopsDB;
$result = $xoopsDB->query('SELECT l.submitter FROM ' . $xoopsDB->prefix('flashgames_games') . ' l, ' . $xoopsDB->prefix('flashgames_text') . " t where l.lid=$lid", 0);
[$submitter] = $xoopsDB->fetchRow($result);

if ($xoopsUser) {
    $xoopsModule = XoopsModule::getByDirname('flashgames');

    if (!$xoopsUser->isAdmin($xoopsModule->mid()) and $submitter != $xoopsUser->uid()) {
        redirect_header(XOOPS_URL . '/', 3, _NOPERM);

        exit();
    }
} else {
    redirect_header(XOOPS_URL . '/', 3, _NOPERM);

    exit();
}
if (file_exists('../language/' . $xoopsConfig['language'] . '/main.php')) {
    include '../language/' . $xoopsConfig['language'] . '/main.php';
} else {
    include '../language/english/main.php';
}
