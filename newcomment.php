<?php

include 'header.php';
require XOOPS_ROOT_PATH . '/header.php';
$q = 'select l.title from ' . $xoopsDB->prefix('flashgames_games') . ' l, ' . $xoopsDB->prefix('flashgames_text') . " t where l.lid=$item_id and l.lid=t.lid and status>0";
$result = $xoopsDB->query($q);
[$ltitle] = $xoopsDB->fetchRow($result);
$subject = $ltitle;
$pid = 0;
OpenTable();
require XOOPS_ROOT_PATH . '/include/commentform.inc.php';
CloseTable();
require XOOPS_ROOT_PATH . '/footer.php';
