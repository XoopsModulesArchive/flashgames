<?php

include 'header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopscomments.php';
require XOOPS_ROOT_PATH . '/header.php';
$pollcomment = new XoopsComments($xoopsDB->prefix('flashgames_comments'), $comment_id);
$nohtml = $pollcomment->getVar('nohtml');
$nosmiley = $pollcomment->getVar('nosmiley');
$icon = $pollcomment->getVar('icon');
$item_id = $pollcomment->getVar('item_id');
$subject = $pollcomment->getVar('subject', 'E');
$message = $pollcomment->getVar('comment', 'E');
OpenTable();
require XOOPS_ROOT_PATH . '/include/commentform.inc.php';
CloseTable();
require XOOPS_ROOT_PATH . '/footer.php';
