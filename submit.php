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

include 'header.php';
$myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
require XOOPS_ROOT_PATH . '/modules/flashgames/class/upload.class.php';

global $flashgames_managed;

$eh = new ErrorHandler(); //ErrorHandler object
$mytree = new XoopsTree($xoopsDB->prefix('flashgames_cat'), 'cid', 'pid');

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

    // Check if Title exist

    if ('' == $_POST['title']) {
        $eh::show('1001');
    }

    // Check if Game exist

    $file = $_POST['uploadFileName'][0];

    if ('' == $file || !isset($file)) {
        $eh::show('7000');
    }

    // Check if Screenshot exists

    $file1 = $_POST['uploadFileName1'][0];

    if ('' == $file1 || !isset($file1)) {
        $eh::show('7000');
    }

    // Check if Description exist

    if ('' == $_POST['description']) {
        $eh::show('1008');
    }

    if (!empty($_POST['cid'])) {
        $cid = $_POST['cid'];
    } else {
        $cid = 0;
    }

    $newid = $xoopsDB->genId($xoopsDB->prefix('flashgames_games') . '_lid_seq');

    $field = $GLOBALS['uploadFileName1'][0];

    //	echo "<h1>".$GLOBALS['uploadFileName'][0]."<br>".$HTTP_POST_FILES[$field]['tmp_name']."<br>".$HTTP_POST_FILES[$field]['name']."<br>".$xoopsConfig['avatar_allow_upload']."</h1>";

    if ('' != $HTTP_POST_FILES[$field]['tmp_name']) {
        $field = $GLOBALS['uploadFileName'][0];

        $upload = new Upload();

        $upload->setAllowedMimeTypes(['image/gif', 'image/pjpeg', 'image/jpeg', 'image/x-png', 'application/x-shockwave-flash']);

        $upload->setMaxImageSize($flashgames_width, $flashgames_heigth);

        $upload->setMaxFileSize($flashgames_fsize);

        $tmp_name = 'tmp_' . mt_rand();

        $upload->setUploadFileNamesArrName('xoops_upload_file');

        $upload->setDestinationFileName((string)$tmp_name);

        $upload->setUploadPath(XOOPS_ROOT_PATH . '/modules/flashgames/games');

        if ($upload->doUpload()) {
            $url = $myts->addSlashes($url);

            $title = $myts->addSlashes($_POST['title']);

            $email = $myts->addSlashes($_POST['email']);

            $description = $myts->addSlashes($_POST['description']);

            $license = $_POST['license'];

            $res_x = $_POST['res_x'];

            $res_y = $_POST['res_y'];

            $bgcolor = $_POST['bgcolor'];

            $date = time();

            $ext = preg_replace("/^.+\.([^.]+)$/sU", '\\1', $HTTP_POST_FILES[$field]['name']);
//            print XOOPS_ROOT_PATH."/modules/flashgames/games/$tmp_name.$ext<<<<br>";

            if (!file_exists(XOOPS_ROOT_PATH . "/modules/flashgames/games/$tmp_name.$ext")) {
                print '<br>strlow';

                $ext = mb_strtolower($ext);
            }
//            print XOOPS_ROOT_PATH."/modules/flashgames/games/$tmp_name.$ext<<<<br>";

            //$dim = GetImageSize(XOOPS_ROOT_PATH."/modules/flashgames/games/$tmp_name.$ext");

            $q = 'INSERT INTO ' . $xoopsDB->prefix('flashgames_games') . " (lid, cid, title, ext, res_x, res_y, bgcolor, submitter, status, date, hits, rating, votes, comments, gametype, license) VALUES ($newid, $cid, '$title', '$ext', '$res_x', '$res_y', '$bgcolor', $submitter, $flashgames_managed, $date, 0, 0, 0, 0, '$gametype', '$license')";

            $xoopsDB->query($q) or $eh::show('0013');

            if (0 == $newid) {
                $newid = $xoopsDB->getInsertId();
            }

            //	echo "<h1>".$tmp_name.$ext."<br></h1>";

            rename(
                XOOPS_ROOT_PATH . "/modules/flashgames/games/$tmp_name.$ext",
                XOOPS_ROOT_PATH . "/modules/flashgames/games/$newid.$ext"
            );

            rename(
                XOOPS_ROOT_PATH . "/modules/flashgames/games/$tmp_name.jpg",
                XOOPS_ROOT_PATH . "/modules/flashgames/games/$newid.jpg"
            );

            rename(
                XOOPS_ROOT_PATH . "/modules/flashgames/games/$tmp_name.gif",
                XOOPS_ROOT_PATH . "/modules/flashgames/games/$newid.gif"
            );

            //			createThumb(XOOPS_ROOT_PATH."/modules/flashgames/games/$newid.$ext", $newid, $ext); OKa

            $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('flashgames_text') . " (lid, description) VALUES ($newid, '$description')") or $eh::show('0013');

            redirect_header('index.php', 2, _ALBM_RECEIVED . '');

            exit();
        }

        $errors = $upload->getUploadErrors();

        require XOOPS_ROOT_PATH . '/header.php';

        OpenTable();

        mainheader();

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

        require XOOPS_ROOT_PATH . '/footer.php';

        exit();
    }

    redirect_header('submit.php', 2, _ALBM_FILEERROR);

// daniel
} else {
    if (!$xoopsUser) {
        redirect_header(XOOPS_URL . '/user.php', 2, _ALBM_MUSTREGFIRST);

        exit();
    }

    $result = $xoopsDB->query('SELECT count(*) as count FROM ' . $xoopsDB->prefix('flashgames_cat') . '', $mylinks_newlinks, 0);

    [$count] = $xoopsDB->fetchRow($result);

    if (0 == $count) {
        redirect_header(XOOPS_URL . '/modules/flashgames', 2, _ALBM_MUSTADDCATFIRST);

        exit();
    }

    require XOOPS_ROOT_PATH . '/header.php';

    OpenTable();

    mainheader();

    require_once '../../class/xoopsformloader.php';

    $form = new XoopsThemeForm(_ALBM_GAMEUPLOAD, 'uploadavatar', 'submit.php');

    $pixels_label = new Xoopsformhidden(_ALBM_MAXPIXEL, $flashgames_width . ' x ' . $flashgames_heigth);

    $size_label = new XoopsFormhidden(_ALBM_MAXSIZE, $flashgames_fsize);

    $form->setExtra("enctype='multipart/form-data'");

    $question_text = new XoopsFormText(_ALBM_GAMETITLE, 'title', 50, 255);

    $cat = new XoopsFormSelect(_ALBM_GAMECAT, 'cid', $cid);

    $tree = $mytree->getChildTreeArray();

    foreach ($tree as $leaf) {
        $leaf['prefix'] = mb_substr($leaf['prefix'], 0, -1);

        $leaf['prefix'] = str_replace('.', '--', $leaf['prefix']);

        $cat->addOption($leaf['cid'], $leaf['prefix'] . $leaf['title']);
    }

    $desc_tarea = new XoopsFormTextarea(_ALBM_GAMEDESC, 'description');

    $file_form = new XoopsFormFile(_ALBM_SELECTFILE, 'avatarfile', $flashgames_fsize);

    $file_form1 = new XoopsFormFile(_ALBM_SELECTIMAGE, 'avatarfile1', $flashgames_fsize); // OKa

    $op_hidden = new XoopsFormHidden('op', 'submit');

    $upload_hidden = new XoopsFormHidden('uploadFileName[0]', 'avatarfile');

    $upload_hidden1 = new XoopsFormHidden('uploadFileName1[0]', 'avatarfile1'); // OKa

    $counter_hidden = new XoopsFormHidden('fieldCounter', 1);

    $counter_hidden1 = new XoopsFormHidden('fieldCounter1', 1); //OKa

    //$gametype = new XoopsFormText(_ALBM_HIGHSCORETYPE, "gametype", 1, 1); //Oka

    $gametype = new XoopsFormSelect(_ALBM_GAMECAT, 'gametype', '1');

    $gametype->addOption('1', 'Numeric - Highest score wins');

    $gametype->addOption('2', 'Numeric - Lowest score wins');

    $gametype->addOption('3', 'Time based - Highest score wins');

    $gametype->addOption('4', 'Time based - Lowest score wins');

    $license_text = new XoopsFormText(_ALBM_GAMELICENSE, 'license', 50, 255);

    $width_text = new XoopsFormText(_ALBM_GAMEWIDTH, 'res_x', 3, 3, '300');

    $height_text = new XoopsFormText(_ALBM_GAMEHEIGHT, 'res_y', 3, 3, '300');

    $bgcolor_text = new XoopsFormText(_ALBM_GAMEBGCOLOR, 'bgcolor', 6, 6, 'FFFFFF');

    $submit_button = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');

    $form->addElement($pixels_label);

    $form->addElement($size_label);

    $form->addElement($question_text);

    $form->addElement($desc_tarea);

    $form->addElement($width_text);

    $form->addElement($height_text);

    $form->addElement($bgcolor_text);

    $form->addElement($cat);

    $form->addElement($file_form1); //oka
    $form->addElement($upload_hidden1); //oka
    $form->addElement($file_form);

    $form->addElement($upload_hidden);

    $form->addElement($counter_hidden);

    $form->addElement($counter_hidden1); //OKa
    $form->addElement($gametype); //Oka
    $form->addElement($license_text);

    $form->addElement($op_hidden);

    $form->addElement($submit_button);

    $form->setRequired($file_form);

    $form->setRequired($file_form1); //oka

    $form->display();

    echo _ALBM_GAMESOURCEREMINDER;

    CloseTable();
}

include 'footer.php';
