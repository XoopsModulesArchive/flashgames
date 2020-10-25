<?php

// ------------------------------------------------------------------------- //
//                      flashgames                                           //
//                     <http://www.tipsmitgrips.de>                          //
// ------------------------------------------------------------------------- //
// based on                                                                  //
// Xoops module "myalbum"          - http://bluetopia.homeip.net/            //
// Postnuke module "pnflashgames"  - http://www.pnflashgames.com             //
// Mainly based on:                                                          //
// XOOPS PHP Content Management System - http://xoops.eti.br/               //
// and:                                                                      //
// myPHPNUKE Web Portal System - http://myphpnuke.com/                       //
// PHP-NUKE Web Portal System - http://phpnuke.org/                          //
// Thatware - http://thatware.org/                                           //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //

include '../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsmodule.php';
//require XOOPS_ROOT_PATH."/include/cp_functions.php";

global $xoopsDB;

$result = $xoopsDB->query('SELECT l.submitter FROM ' . $xoopsDB->prefix('flashgames_games') . ' l, ' . $xoopsDB->prefix('flashgames_text') . " t where l.lid=$lid", 0);
[$submitter] = $xoopsDB->fetchRow($result);

require XOOPS_ROOT_PATH . '/modules/flashgames/cache/config.php';

if ($xoopsUser) {
    $xoopsModule = XoopsModule::getByDirname('flashgames');

    if (!(($xoopsUser->uid() == $submitter and $flashgames_allowdelete) or $xoopsUser->isAdmin($xoopsModule->mid()))) {
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

require XOOPS_ROOT_PATH . '/modules/flashgames/include/functions.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . '/class/xoopscomments.php';
require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
require XOOPS_ROOT_PATH . '/class/upload.class.php';

global $myts, $xoopsDB, $xoopsConfig, $xoopsModule;

$myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object
$eh = new ErrorHandler(); //ErrorHandler object
$mytree = new XoopsTree($xoopsDB->prefix('flashgames_cat'), 'cid', 'pid');

function update($lid, $cid, $title, $desc, $valid, $gtyp, $license = '', $x = '', $y = '', $bgcolor = '', $ext = '')
{
    global $myts, $xoopsDB, $xoopsConfig, $xoopsModule;

    if ('' == $ext) {
        $GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('flashgames_games') . " 
			SET cid='$cid',title='$title', status='$valid', date=" . time() . ", gametype='$gtyp', res_x='$x', res_y='$y', bgcolor='$bgcolor', license='$license' 
			WHERE lid='$lid'");
    } else {
        $GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('flashgames_games') . " 
			SET cid='$cid',title='$title', status='$valid', date=
			" . time() . ", ext='$ext', res_x='$x', res_y='$y', bgcolor='$bgcolor', gametype='$gtyp', license='$license'  
			WHERE lid='$lid'");
    }

    $GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('flashgames_text') . " SET 
		description='$desc' WHERE lid=" . $lid . '');

    redirect_header("editgame.php?lid=$lid", 0);
}

// Delete Scores
if (isset($clear)) {
    $q = 'DELETE FROM ' . $xoopsDB->prefix('flashgames_score') . " WHERE lid = $lid";

    $xoopsDB->queryF($q) or $eh::show('0013');
}

if (isset($delete) and '' != $delete) {
    $delete = $myts->addSlashes($delete);

    $result = $xoopsDB->query('SELECT l.lid, l.cid, l.title, l.ext, l.res_x, l.bgcolor, l.res_y, l.status, l.date, l.hits, l.rating, l.votes, l.comments, t.description, l.license FROM ' . $xoopsDB->prefix('flashgames_games') . ' l, ' . $xoopsDB->prefix('flashgames_text') . " t where l.lid=t.lid and l.lid=$delete ORDER BY date DESC", $mylinks_newlinks, 0);

    [$lid, $cid, $ltitle, $ext, $res_x, $res_y, $bgcolor, $status, $time, $hits, $rating, $votes, $comments, $description, $license] = $xoopsDB->fetchRow($result);

    $q = 'DELETE FROM ' . $xoopsDB->prefix('flashgames_games') . " WHERE lid = $delete";

    $xoopsDB->queryF($q) or $eh::show('0013');

    $q = 'DELETE FROM ' . $xoopsDB->prefix('flashgames_text') . " WHERE lid = $delete";

    $xoopsDB->queryF($q) or $eh::show('0013');

    $q = 'DELETE FROM ' . $xoopsDB->prefix('flashgames_votedata') . " WHERE lid = $delete";

    $xoopsDB->queryF($q) or $eh::show('0013');

    $q = 'DELETE FROM ' . $xoopsDB->prefix('flashgames_score') . " WHERE lid = $delete";

    $xoopsDB->queryF($q) or $eh::show('0013');

    // delete comments for this photo

    $com = new XoopsComments($xoopsDB->prefix('flashgames_comments'));

    $criteria = ["item_id=$delete", 'pid=0'];

    $commentsarray = $com->getAllComments($criteria);

    foreach ($commentsarray as $comment) {
        $comment->delete();
    }

    if (is_numeric($delete)) { // last security check
        unlink(XOOPS_ROOT_PATH . "/modules/flashgames/games/$delete.$ext");

        unlink(XOOPS_ROOT_PATH . "/modules/flashgames/games/$delete.gif");

        unlink(XOOPS_ROOT_PATH . "/modules/flashgames/games/$delete.jpg");
    }

    redirect_header('index.php', 2, _ALBM_DELETINGPHOTO);

    exit();
}

if (isset($_POST['submit']) && '' != $_POST['submit']) {
    if (!$xoopsUser) {
        redirect_header(XOOPS_URL . '/user.php', 2, _ALBM_MUSTREGFIRST);

        exit();
    }

    if (!isset($_POST['submitter']) || '' == $_POST['submitter']) {
        $submitter = $xoopsUser->uid();
    } else {
        $submitter = $_POST['submitter'];
    }

    if (isset($_POST['delete'])) {
        require XOOPS_ROOT_PATH . '/include/cp_functions.php';

        require_once '../../header.php';

        OpenTable();

        echo '<h4>' . _ALBM_PHOTODEL . " $delete</h4>\n";

        echo "<table><tr><td>\n";

        echo myTextForm("editgame.php?delete=$lid", _ALBM_YES);

        echo "</td><td>\n";

        echo myTextForm("editgame.php?lid=$lid", _NO);

        echo "</td></tr></table>\n";

        CloseTable();

        require_once '../../footer.php';

        exit();
    }

    // Check if Title exist

    if ('' == $_POST['title']) {
        $eh::show('1001');
    }

    // Check if Photo exist

    //	$file = $_POST["uploadFileName"][0];

    //   	if ($file=="" || !isset($file)) {
//        	$eh->show("7000");
//    	}

    // Check if Description exist

    if ('' == $_POST['description']) {
        $eh::show('1008');
    }

    if (!empty($_POST['cid'])) {
        $cid = $_POST['cid'];
    } else {
        $cid = 0;
    }

    if (isset($_POST['valid'])) {
        $valid = 1;
    } else {
        $valid = 0;
    }

    $field = $GLOBALS['uploadFileName'][0];

    //	echo "<h1>".$GLOBALS['uploadFileName'][0]."<br>".$HTTP_POST_FILES[$field]['tmp_name']."<br>".$HTTP_POST_FILES[$field]['name']."</h1>";

    if ('' != $HTTP_POST_FILES[$field]['tmp_name']) {
        $upload = new Upload();

        $upload->setAllowedMimeTypes(['image/gif', 'image/pjpeg', 'image/jpeg', 'image/x-png']);

        $upload->setMaxImageSize($flashgames_width, $flashgames_heigth);

        $upload->setMaxFileSize($flashgames_fsize);

        $tmp_name = 'tmp_' . mt_rand();

        $upload->setDestinationFileName((string)$tmp_name);

        $upload->setUploadPath(XOOPS_ROOT_PATH . '/modules/flashgames/games');

        if ($upload->doUpload()) {
            $title = $myts->addSlashes($_POST['title']);

            $description = $myts->addSlashes($_POST['description']);

            $ext = preg_replace("/^.+\.([^.]+)$/sU", '\\1', $HTTP_POST_FILES[$field]['name']);

            $dim = getimagesize(XOOPS_ROOT_PATH . "/modules/flashgames/games/$tmp_name.$ext");

            if (is_numeric($lid)) { // last security check
                //				unlink(XOOPS_ROOT_PATH."/modules/flashgames/games/$lid.$ext");

                //				unlink(XOOPS_ROOT_PATH."/modules/flashgames/games/thumbs/$lid.$ext");

                unlink(XOOPS_ROOT_PATH . "/modules/flashgames/games/$lid.gif");

                unlink(XOOPS_ROOT_PATH . "/modules/flashgames/games/thumbs/$lid.jpg");
            }

            rename(
                XOOPS_ROOT_PATH . "/modules/flashgames/games/$tmp_name.$ext",
                XOOPS_ROOT_PATH . "/modules/flashgames/games/$lid.$ext"
            );

            //			createThumb(XOOPS_ROOT_PATH."/modules/flashgames/games/$lid.$ext", $lid, $ext);

            //			update($lid, $cid, $title, $description, $valid, $ext, $dim[0], $dim[1]);

            exit();
        }

        $errors = $upload->getUploadErrors();

        require_once '../../header.php';

        OpenTable();

        echo "<p><strong>::Errors occured::</strong><br>\n";

        while (list($filename, $values) = each($errors)) {
            'File: ' . print $filename . '<br>';

            $count = count($values);

            for ($i = 0; $i < $count; $i++) {
                echo '==>' . $values[$i] . '<br>';
            }
        }

        echo '</p>';

        CloseTable();

        require_once '../../footer.php';

        exit();
    }    //update without file upload

    $title = $myts->addSlashes($_POST['title']);

    $description = $myts->addSlashes($_POST['description']);

    $license = $_POST['license'];

    $cid = $_POST['cid'];

    $res_x = $_POST['res_x'];

    $res_y = $_POST['res_y'];

    $bgcolor = $_POST['bgcolor'];

    if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
        $valid = 1;
    }

    update($lid, $cid, $title, $description, $valid, $gametype, $license, $res_x, $res_y, $bgcolor);
} else {
    if (!$xoopsUser) {
        redirect_header(XOOPS_URL . '/user.php', 2, _ALBM_MUSTREGFIRST);

        exit();
    }

    /*
        if ($submitter == $xoopsUser->uid()) {

    //        require_once "../../../header.php";

        } else {
        	xoops_cp_header();
        }
    */

    require_once '../../header.php';

    OpenTable();

    $result = $xoopsDB->query('SELECT l.lid, l.cid, l.title, l.ext, l.res_x, l.res_y, l.bgcolor, l.status, l.date, l.hits, l.rating, l.votes, l.comments, t.description, l.gametype, l.license FROM ' . $xoopsDB->prefix('flashgames_games') . ' l, ' . $xoopsDB->prefix('flashgames_text') . " t where l.lid=t.lid and l.lid=$lid ORDER BY date DESC", $mylinks_newlinks, 0);

    [$lid, $cid, $ltitle, $ext, $res_x, $res_y, $bgcolor, $status, $time, $hits, $rating, $votes, $comments, $description, $gtype, $license] = $xoopsDB->fetchRow($result);

    require_once '../../class/xoopsformloader.php';

    $form = new XoopsThemeForm(_ALBM_GAMEUPLOAD, 'uploadavatar', "editgame.php?lid=$lid");

    $form->setExtra("enctype='multipart/form-data'");

    $question_text = new XoopsFormText(_ALBM_GAMETITLE, 'title', 50, 255, $ltitle);

    $license_text = new XoopsFormText(_ALBM_GAMELICENSE, 'license', 50, 255, $license);

    $width_text = new XoopsFormText(_ALBM_GAMEWIDTH, 'res_x', 3, 3, $res_x);

    $height_text = new XoopsFormText(_ALBM_GAMEHEIGHT, 'res_y', 3, 3, $res_y);

    $bgcolor_text = new XoopsFormText(_ALBM_GAMEBGCOLOR, 'bgcolor', 6, 6, $bgcolor);

    $cat = new XoopsFormSelect(_ALBM_GAMECAT, 'cid', $cid);

    $tree = $mytree->getChildTreeArray();

    foreach ($tree as $leaf) {
        $leaf['prefix'] = mb_substr($leaf['prefix'], 0, -1);

        $leaf['prefix'] = str_replace('.', '--', $leaf['prefix']);

        $cat->addOption($leaf['cid'], $leaf['prefix'] . $leaf['title']);
    }

    $desc_tarea = new XoopsFormTextarea(_ALBM_GAMEDESC, 'description', $description);

    $file_form = new XoopsFormFile(_ALBM_SELECTFILE, 'avatarfile', $flashgames_fsize);

    $op_hidden = new XoopsFormHidden('op', 'submit');

    $upload_hidden = new XoopsFormHidden('uploadFileName[0]', 'avatarfile');

    $counter_hidden = new XoopsFormHidden('fieldCounter', 1);

    if (1 == $status) {
        $status = '';
    }

    $valid_box = new XoopsFormCheckBox(_ALBM_VALIDGAME, 'valid', [$status]);

    $valid_box->addOption();

    $delete_box = new XoopsFormCheckBox(_ALBM_DELETEGAME, 'delete');

    $delete_box->addOption();

    $clear_box = new XoopsFormCheckBox(_ALBM_CLEARGAME, 'clear');

    $clear_box->addOption();

    //$gametype = new XoopsFormText(Highscoretyp, "gametype", 1, 1,$gtype); //Oka

    $gametype = new XoopsFormSelect(_ALBM_GAMECAT, 'gametype', $gtype);

    $gametype->addOption('1', 'Numeric - Highest score wins');

    $gametype->addOption('2', 'Numeric - Lowest score wins');

    $gametype->addOption('3', 'Time based - Highest score wins');

    $gametype->addOption('4', 'Time based - Lowest score wins');

    $submit_button = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');

    print "<center><img src=games/thumbs/$lid.$ext></center>";

    $form->addElement($question_text);

    $form->addElement($desc_tarea);

    $form->addElement($width_text);

    $form->addElement($height_text);

    $form->addElement($bgcolor_text);

    $form->addElement($cat);

    //	$form->addElement($file_form);

    $form->addElement($upload_hidden);

    $form->addElement($counter_hidden);

    $form->addElement($op_hidden);

    $form->addElement($gametype); //Oka
    $form->addElement($license_text); //Oka
if ($xoopsUser->isAdmin($xoopsModule->mid())) {
    $form->addElement($valid_box);
}

    $form->addElement($delete_box);

    $form->addElement($clear_box);

    $form->addElement($submit_button);

    //	$form->setRequired("avatarfile");

    $form->display();

    CloseTable();

    require_once '../../footer.php';
}
