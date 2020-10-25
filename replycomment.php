<?php

include 'header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopscomments.php';
require XOOPS_ROOT_PATH . '/header.php';
$pollcomment = new XoopsComments($xoopsDB->prefix('flashgames_comments'), $comment_id);
$r_date = formatTimestamp($pollcomment->getVar('date'));
$r_name = XoopsUser::getUnameFromId($pollcomment->getVar('user_id'));
$r_content = _ALBM_POSTERC . '' . $r_name . '&nbsp;' . _ALBM_DATEC . '' . $r_date . '<br><br>';
$r_content .= $pollcomment->getVar('comment');
$r_subject = $pollcomment->getVar('subject');
$subject = $pollcomment->getVar('subject', 'E');
themecenterposts($r_subject, $r_content);
$pid = $comment_id;
unset($comment_id);
$item_id = $pollcomment->getVar('item_id');
OpenTable();
require XOOPS_ROOT_PATH . '/include/commentform.inc.php';
CloseTable();
require XOOPS_ROOT_PATH . '/footer.php';
