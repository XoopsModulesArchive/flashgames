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

$modcheck = $_POST['module'];
$funccheck = $_POST['func'];

if ('pnFlashGames' == $modcheck && !empty($xoopsUser)) {
    $uid = $xoopsUser->getVar('uid');

    if (0 == $uid) {
        print '&opSuccess=Not Logged In&endvar=true';

        return;
    }

    if (!isset($xoopsDB)) {
        print '&opSuccess=DB Error&endvar=true';

        return;
    }

    $gid = $_POST['gid'];

    // Get current player's username

    $uid = $xoopsUser->uid();

    $result = $xoopsDB->query('SELECT uname FROM ' . $xoopsDB->prefix('users') . " WHERE uid = $uid") || die('Error getting username');

    $myrow = $xoopsDB->fetchArray($result);

    $player_name = $myrow['uname'];

    // Determine what the game is asking us to do

    switch ($funccheck) {
        case 'storeScore':
            $player_score = $_POST['score'];
            $player_ip = $_SERVER['REMOTE_ADDR'];

            $result = pnFlashGames_storeScore($xoopsDB, $gid, $player_name, $player_score, $player_ip);
            if ($result) {
                print '&opSuccess=true&endvar=true';
            } else {
                print '&opSuccess=Error&endvar=true';
            }
            break;
        case 'saveGame':
            $gameData = $_POST['gameData'];

            $result = pnFlashGames_saveGame($xoopsDB, $gid, $player_name, $gameData);
            if ($result) {
                print '&opSuccess=true&endvar=true';
            } else {
                print '&opSuccess=false&endvar=true';
            }
            break;
        case 'loadGame':
            $gameData = pnFlashGames_loadGame($xoopsDB, $gid, $player_name);

            if (false !== $gameData) {
                //Return true
                print "&opSuccess=true&gameData=$gameData&endvar=1"; //send endvar to keep opSuccess separate from all other output from PostNuke
            } else {
                print '&opSuccess=false&error=Error&endvar=1';
            }
            break;
        case 'loadGameScores':
            $scores = pnFlashGames_loadGameScores($xoopsDB, $gid);

            if (false !== $scores) {
                //Return true
                print "&opSuccess=true&gameScores=$scores&endvar=1"; //send endvar to keep opSuccess separate from all other output from PostNuke
            } else {
                print '&opSuccess=false&error=Error&endvar=1';
            }
            break;
    }
}

# Error handler
function error_msg($msg)
{
    exit("success=0&errorMsg=$msg");
}

$myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
$mytree = new XoopsTree($xoopsDB->prefix('flashgames_cat'), 'cid', 'pid');

if ('flashgames' == $xoopsConfig['startpage']) {
    $xoopsOption['show_rblock'] = 1;

    require XOOPS_ROOT_PATH . '/header.php';

    make_cblock();

    echo '<br>';
} else {
    $xoopsOption['show_rblock'] = 0;

    require XOOPS_ROOT_PATH . '/header.php';
}
$result = $xoopsDB->query('SELECT cid, title, imgurl FROM ' . $xoopsDB->prefix('flashgames_cat') . ' WHERE pid = 0 ORDER BY title') || die('Error');

//OpenTable(); OKa
 // Oka
echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 0px solid #2F5376;'><tr class='bg4'><td valign='top'>\n";

mainheader();
echo "<center>\n";
echo "<table border=0 cellspacing=5 cellpadding=0 width=\"90%\"><tr>\n";
echo '<HR>';
$count = 0;
while ($myrow = $xoopsDB->fetchArray($result)) {
    $title = htmlspecialchars($myrow['title'], ENT_QUOTES | ENT_HTML5);

    echo '<td valign="top" align="right">';

    if ($myrow['imgurl'] && 'http://' != $myrow['imgurl']) {
        $imgurl = htmlspecialchars($myrow['imgurl'], ENT_QUOTES | ENT_HTML5);

        echo '<a href="' . XOOPS_URL . '/modules/flashgames/viewcat.php?cid=' . $myrow['cid'] . '"><img src="' . $imgurl . '" height="50" border="0"></a>';
    } else {
        echo '';
    }

    $totallink = getTotalItems($myrow['cid'], 1);

    echo '</td><td valign="top" width="40%"><a href="' . XOOPS_URL . '/modules/flashgames/viewcat.php?cid=' . $myrow['cid'] . "\"><b>$title</b></a>&nbsp;($totallink)<br>";

    // get child category objects

    $arr = [];

    $arr = $mytree->getFirstChild($myrow['cid'], 'title');

    $space = 0;

    $chcount = 0;

    foreach ($arr as $ele) {
        $chtitle = htmlspecialchars($ele['title'], ENT_QUOTES | ENT_HTML5);

        if ($chcount > 5) {
            echo '...';

            break;
        }

        if ($space > 0) {
            echo ', ';
        }

        echo '<a href="' . XOOPS_URL . '/modules/flashgames/viewcat.php?cid=' . $ele['cid'] . '">' . $chtitle . '</a>';

        $space++;

        $chcount++;
    }

    if ($count < 1) {
        echo '</td>';
    }

    $count++;

    if (2 == $count) {
        echo '</td></tr><tr>';

        $count = 0;
    }
}
echo '</td></tr></table>';
[$numrows] = $xoopsDB->fetchRow($xoopsDB->query('select count(*) from ' . $xoopsDB->prefix('flashgames_games') . ' where status>0'));
echo '<br><br>';

// Oka
if ($xoopsUser) {
    printf(_ALBM_THEREAREADMIN, $numrows);
} else {
    printf(_ALBM_THEREARE, $numrows);
}

echo '</center>';
echo '<HR>';
CloseTable();

echo '<br>';

// OpenTable(); // OKa
// Oka
echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 0px solid #2F5376;'><tr class='bg4'><td valign='top'>\n";

echo '<div align="left"><h4>' . _ALBM_LATESTLIST . '</h4><br>';
showNew();

echo '</div>';
CloseTable();

require XOOPS_ROOT_PATH . '/modules/flashgames/footer.php';

// Shows the Latest Listings on the front page
function showNew()
{
    global $myts, $xoopsDB, $xoopsConfig, $xoopsModule;

    global $mylinks_shotwidth, $mylinks_newlinks, $mylinks_useshots;

    $result = $xoopsDB->query('SELECT l.lid, l.cid, l.title, l.ext, l.res_x, l.res_y, l.status, l.date, l.hits, l.rating, l.votes, l.comments, l.submitter, t.description FROM ' . $xoopsDB->prefix('flashgames_games') . ' l, ' . $xoopsDB->prefix('flashgames_text') . ' t where l.status>0 and l.lid=t.lid ORDER BY date DESC', $mylinks_newlinks, 0);

    echo '<table width="100%" cellspacing=0 cellpadding=10 border=0><tr><td width="110" align="center">';

    while (list($lid, $cid, $ltitle, $ext, $res_x, $res_y, $status, $time, $hits, $rating, $votes, $comments, $submitter, $description) = $xoopsDB->fetchRow($result)) {
        $rating = number_format($rating, 2);

        $ltitle = htmlspecialchars($ltitle, ENT_QUOTES | ENT_HTML5);

        $url = htmlspecialchars($url, ENT_QUOTES | ENT_HTML5);

        $email = htmlspecialchars($email, ENT_QUOTES | ENT_HTML5);

        $logourl = htmlspecialchars($logourl, ENT_QUOTES | ENT_HTML5);
//    		$logourl = urldecode($logourl);

        $datetime = formatTimestamp($time, 's');

        $description = $myts->displayTarea($description, 0);

        require XOOPS_ROOT_PATH . '/modules/flashgames/include/linkformat.php';
    }

    echo '</table>';
}
