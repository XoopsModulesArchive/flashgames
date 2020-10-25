<?php

include 'header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopscomments.php';

if (isset($_POST['op'])) {
    switch ($_POST['op']) {
        case 'preview':
        require XOOPS_ROOT_PATH . '/header.php';
        $myts = MyTextSanitizer::getInstance();
        $p_subject = htmlspecialchars($subject, ENT_QUOTES | ENT_HTML5);
        if ($nosmiley && $nohtml) {
            $p_comment = $myts->previewTarea($message, 0, 0, 1);
        } elseif ($nohtml) {
            $p_comment = $myts->previewTarea($message, 0, 1, 1);
        } elseif ($nosmiley) {
            $p_comment = $myts->previewTarea($message, 1, 0, 1);
        } else {
            $p_comment = $myts->previewTarea($message, 1, 1, 1);
        }
        themecenterposts($p_subject, $p_comment);
        $subject = htmlspecialchars($subject, ENT_QUOTES | ENT_HTML5);
        $message = htmlspecialchars($message, ENT_QUOTES | ENT_HTML5);
        OpenTable();
        require XOOPS_ROOT_PATH . '/include/commentform.inc.php';
        CloseTable();
        break;
        case 'post':
        if (!empty($comment_id)) {
            $gamecomment = new XoopsComments($xoopsDB->prefix('flashgames_comments'), $comment_id);

            $accesserror = 0;

            if ($xoopsUser) {
                if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
                    if ($gamecomment->getVar('user_id') != $xoopsUser->getVar('uid')) {
                        $accesserror = 1;
                    }
                }
            } else {
                $accesserror = 1;
            }

            if (1 == $accesserror) {
                redirect_header('game.php?lid=' . $item_id . '&amp;comment_id=' . $comment_id . '&amp;order=' . $order . '&amp;mode=' . $mode . '', 1, _ALBM_EDITNOTALLOWED);

                exit();
            }
        } else {
            $gamecomment = new XoopsComments($xoopsDB->prefix('flashgames_comments'));

            $gamecomment->setVar('pid', $pid);

            $gamecomment->setVar('item_id', $item_id);

            $gamecomment->setVar('ip', $REMOTE_ADDR);

            if ($xoopsUser) {
                $uid = $xoopsUser->getVar('uid');
            } else {
                if (1 == $xoopsConfig['anonpost']) {
                    $uid = 0;
                } else {
                    redirect_header('game.php?lid==' . $item_id . '&amp;comment_id=' . $comment_id . '&amp;order=' . $order . '&amp;mode=' . $mode . '&amp;pid=' . $pid . '', 1, _ALBM_ANONNOTALLOWED);

                    exit();
                }
            }

            $gamecomment->setVar('user_id', $uid);
        }
        $gamecomment->setVar('subject', $subject);
        $gamecomment->setVar('comment', $message);
        $gamecomment->setVar('nohtml', $nohtml);
        $gamecomment->setVar('nosmiley', $nosmiley);
        $gamecomment->setVar('icon', $icon);
        $gamecomment->store();

        if (false !== stripos($_SERVER['HTTP_REFERER'], "newcomment")) {
            $xoopsDB->queryF('update ' . $xoopsDB->prefix('flashgames_games') . " set comments=comments+1 where lid=$item_id ");
        }
        redirect_header('game.php?lid=' . $item_id . '&amp;comment_id=' . $comment_id . '&amp;order=' . $order . '&amp;mode=' . $mode . '', 2, _ALBM_THANKSFORPOST);
        exit();
        break;
    }
} else {
    redirect_header("game.php?lid=$item_id&comment_id=$comment_id&order=$order&mode=$mode", 2);

    exit();
}
require XOOPS_ROOT_PATH . '/footer.php';
