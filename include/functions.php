<?php

// ------------------------------------------------------------------------- //
//                      flashgames - XOOPS game album                          //
//                     <http://bluetopia.homeip.net>                        //
// ------------------------------------------------------------------------- //
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

// $Id: functions.php,v 1.3 2006/03/21 18:43:07 mikhail Exp $

function mainheader($mainlink = 1)
{
    echo '<br><p><div align="center">';

    echo '<a href="' . XOOPS_URL . '/modules/flashgames/index.php"><img src="' . XOOPS_URL . '/modules/flashgames/images/logo.gif" border="0"></a>';

    echo '</div></p><br>';
}

function createThumb($imagePath, $name, $ext)
{
    global $mylinks_shotwidth;

    $image_stats = getimagesize($imagePath);

    if (1 == $image_stats[2]) { // no gif support, big thumbs!
        copy($imagePath, XOOPS_ROOT_PATH . "/modules/flashgames/games/thumbs/$name.$ext");

        return;
    }

    if (2 == $image_stats[2]) {
        $src_img = imagecreatefromjpeg($imagePath);
    } else {
        if (3 == $image_stats[2]) {
            $sourceImg = imagecreatefrompng($imagePath);
        } else {
            die();
        }
    }

    $imagewidth = $image_stats[0];

    $imageheight = $image_stats[1];

    $new_w = $mylinks_shotwidth;

    $scale = ($imagewidth / $new_w);

    $new_h = round($imageheight / $scale);

    $gd2 = true;

    $dst_img = @imagecreatetruecolor($new_w, $new_h);

    if ('' == $dst_img) {
        $gd2 = false;

        $dst_img = imagecreate($new_w, $new_h);

        if ('' == $dst_img) {
            // fixme: report GD error.
        }
    }

    if ($gd2) {
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, imagesx($src_img), imagesy($src_img));
    } else {
        imagecopyresized($dst_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, imagesx($src_img), imagesy($src_img));
    }

    if (2 == $image_stats[2]) {
        imagejpeg($dst_img, XOOPS_ROOT_PATH . "/modules/flashgames/games/thumbs/$name.$ext");
    } else {
        if (3 == $image_stats[2]) {
            imagepng($dst_img, XOOPS_ROOT_PATH . "/modules/flashgames/games/thumbs/$name.$ext");
        }
    }

    imagedestroy($src_img);

    imagedestroy($dst_img);
}

function newlinkgraphic($time, $status)
{
    $count = 7;

    $startdate = (time() - (86400 * $count));

    if ($startdate < $time) {
        if (1 == $status) {
            echo '&nbsp;<img src="' . XOOPS_URL . '/modules/flashgames/images/newred.gif" alt="' . _ALBM_NEWTHISWEEK . '">';
        } elseif (2 == $status) {
            echo '&nbsp;<img src="' . XOOPS_URL . '/modules/flashgames/images/update.gif" alt="' . _ALBM_UPTHISWEEK . '">';
        }
    }
}

function popgraphic($hits)
{
    global $mylinks_popular;

    if ($hits >= $mylinks_popular) {
        echo '&nbsp;<img src="' . XOOPS_URL . '/modules/flashgames/images/pop.gif" alt="' . _ALBM_POPULAR . '">';
    }
}
//Reusable Link Sorting Functions
function convertorderbyin($orderby)
{
    if ('titleA' == $orderby) {
        $orderby = 'title ASC';
    }

    if ('dateA' == $orderby) {
        $orderby = 'date ASC';
    }

    if ('hitsA' == $orderby) {
        $orderby = 'hits ASC';
    }

    if ('ratingA' == $orderby) {
        $orderby = 'rating ASC';
    }

    if ('titleD' == $orderby) {
        $orderby = 'title DESC';
    }

    if ('dateD' == $orderby) {
        $orderby = 'date DESC';
    }

    if ('hitsD' == $orderby) {
        $orderby = 'hits DESC';
    }

    if ('ratingD' == $orderby) {
        $orderby = 'rating DESC';
    }

    return $orderby;
}
function convertorderbytrans($orderby)
{
    if ('hits ASC' == $orderby) {
        $orderbyTrans = '' . _ALBM_POPULARITYLTOM . '';
    }

    if ('hits DESC' == $orderby) {
        $orderbyTrans = '' . _ALBM_POPULARITYMTOL . '';
    }

    if ('title ASC' == $orderby) {
        $orderbyTrans = '' . _ALBM_TITLEATOZ . '';
    }

    if ('title DESC' == $orderby) {
        $orderbyTrans = '' . _ALBM_TITLEZTOA . '';
    }

    if ('date ASC' == $orderby) {
        $orderbyTrans = '' . _ALBM_DATEOLD . '';
    }

    if ('date DESC' == $orderby) {
        $orderbyTrans = '' . _ALBM_DATENEW . '';
    }

    if ('rating ASC' == $orderby) {
        $orderbyTrans = '' . _ALBM_RATINGLTOH . '';
    }

    if ('rating DESC' == $orderby) {
        $orderbyTrans = '' . _ALBM_RATINGHTOL . '';
    }

    return $orderbyTrans;
}
function convertorderbyout($orderby)
{
    if ('title ASC' == $orderby) {
        $orderby = 'titleA';
    }

    if ('date ASC' == $orderby) {
        $orderby = 'dateA';
    }

    if ('hits ASC' == $orderby) {
        $orderby = 'hitsA';
    }

    if ('rating ASC' == $orderby) {
        $orderby = 'ratingA';
    }

    if ('title DESC' == $orderby) {
        $orderby = 'titleD';
    }

    if ('date DESC' == $orderby) {
        $orderby = 'dateD';
    }

    if ('hits DESC' == $orderby) {
        $orderby = 'hitsD';
    }

    if ('rating DESC' == $orderby) {
        $orderby = 'ratingD';
    }

    return $orderby;
}

//updates rating data in itemtable for a given item
function updaterating($sel_id)
{
    global $xoopsDB;

    $query = 'select rating FROM ' . $xoopsDB->prefix('flashgames_votedata') . ' WHERE lid = ' . $sel_id . '';

    //echo $query;

    $voteresult = $xoopsDB->query($query);

    $votesDB = $xoopsDB->getRowsNum($voteresult);

    $totalrating = 0;

    while (list($rating) = $xoopsDB->fetchRow($voteresult)) {
        $totalrating += $rating;
    }

    $finalrating = $totalrating / $votesDB;

    $finalrating = number_format($finalrating, 4);

    $query = 'UPDATE ' . $xoopsDB->prefix('flashgames_games') . " SET rating=$finalrating, votes=$votesDB WHERE lid = $sel_id";

    //echo $query;

    $xoopsDB->query($query) || die();
}

//returns the total number of items in items table that are accociated with a given table $table id
function getTotalItems($sel_id, $status = '')
{
    global $xoopsDB, $mytree;

    $count = 0;

    $arr = [];

    $query = 'select count(*) from ' . $xoopsDB->prefix('flashgames_games') . ' where cid=' . $sel_id . '';

    if ('' != $status) {
        $query .= " and status>=$status";
    }

    $result = $xoopsDB->query($query);

    [$thing] = $xoopsDB->fetchRow($result);

    $count = $thing;

    $arr = $mytree->getAllChildId($sel_id);

    $size = count($arr);

    for ($i = 0; $i < $size; $i++) {
        $query2 = 'select count(*) from ' . $xoopsDB->prefix('flashgames_games') . ' where cid=' . $arr[$i] . '';

        if ('' != $status) {
            $query2 .= " and status>=$status";
        }

        $result2 = $xoopsDB->query($query2);

        [$thing] = $xoopsDB->fetchRow($result2);

        $count += $thing;
    }

    return $count;
}

function ShowSubmitter($submitter)
{
    $poster = new XoopsUser($submitter);

    if ($poster) {
        if (1 == $allow_sig && '' != $this->attachsig() && 1 == $poster->attachsig()) {
            $myts = MyTextSanitizer::getInstance();

            $text .= '<p><br>_________________<br>' . $myts->displayTarea($poster->getVar('user_sig', 'N'), 0, 1, 1) . '</p>';
        }

        //		$reg_date = _JOINED;

        //		$reg_date .= formatTimestamp($poster->user_regdate(),"s");

        //		$posts = _POSTS;

        //		$posts .= $poster->posts();

        //		$user_from = _FROM;

        //		$user_from = $poster->user_from();

        $rank = $poster->rank();

        if ('' != $rank['image']) {
            $rank['image'] = "<img src='" . XOOPS_URL . '/images/ranks/' . $rank['image'] . "' alt=''>";
        }

        $avatar_image = "<img width=\"50\" src='" . XOOPS_URL . '/images/avatar/' . $poster->user_avatar() . "' alt=''>";

        if ($poster->isOnline()) {
            $online_image = "<span style='color:#ee0000;font-weight:bold;'>" . _ONLINE . '</span>';
        } else {
            $online_image = '';
        }

        $profile_image = "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $poster->uid() . "'><img src='" . XOOPS_URL . "/images/icons/profile.gif' alt='" . _PROFILE . "'></a>";

        if ($xoopsUser) {
            $pm_image = "<a href=\"javascript:openWithSelfMain('" . XOOPS_URL . '/pmlite.php?send2=1&amp;to_userid=' . $poster->uid() . "','pmlite',360,300);\"><img src='" . XOOPS_URL . "/images/icons/pm.gif' alt='" . sprintf(_SENDPMTO, $poster->uname()) . "'></a>";
        } else {
            $pm_image = '';
        }

        if ($poster->user_viewemail()) {
            $email_image = "<a href='mailto:" . $poster->email() . "'><img src='" . XOOPS_URL . "/images/icons/email.gif' alt='" . sprintf(_SENDEMAILTO, $poster->uname()) . "'></a>";
        } else {
            $email_image = '';
        }

        $posterurl = $poster->url();

        if ('' != $poster->url()) {
            $www_image = "<a href='$posterurl' target='_blank'><img src='" . XOOPS_URL . "/images/icons/www.gif' alt='" . _VISITWEBSITE . "' target='_blank'></a>";
        } else {
            $www_image = '';
        }

        if ('' != $poster->user_icq()) {
            $icq_image = "<a href='http://wwp.icq.com/scripts/search.dll?to=" . $poster->user_icq() . "'><img src='" . XOOPS_URL . "/images/icons/icq_add.gif' alt='" . _ADDTOLIST . "'></a>";
        } else {
            $icq_image = '';
        }

        if ('' != $poster->user_aim()) {
            $aim_image = "<a href='aim:goim?screenname=" . $poster->user_aim() . '&message=Hi+' . $poster->user_aim() . "+Are+you+there?'><img src='" . XOOPS_URL . "/images/icons/aim.gif' alt='aim'></a>";
        } else {
            $aim_image;
        }

        if ('' != $poster->user_yim()) {
            $yim_image = "<a href='http://edit.yahoo.com/config/send_webmesg?.target=" . $poster->user_yim() . "&.src=pg'><img src='" . XOOPS_URL . "/images/icons/yim.gif' alt='yim'></a>";
        } else {
            $yim_image = '';
        }

        if ('' != $poster->user_msnm()) {
            $msnm_image = "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $poster->uid() . "'><img src='" . XOOPS_URL . "/images/icons/msnm.gif' alt='msnm'></a>";
        } else {
            $msnm_image = '';
        }

        echo '<b>' . _ALBM_SUBMITTER . '</b>&nbsp;';

        //		echo $avatar_image."&nbsp;";

        echo "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $poster->uid() . "'>" . $poster->uname() . '</a>';

        echo " - <a href='" . XOOPS_URL . '/modules/flashgames/viewcat.php?uid=' . $poster->uid() . "'>";

        printf(_ALBM_MOREGAMES . '</a>', $poster->uname());

        //		echo $online_image."&nbsp;";
//		echo $rank['image']."&nbsp;";
//		echo "<br>";
//		echo $profile_image;
//		echo $pm_image;
//		echo $email_image;
//		echo $www_image;
//		echo $icq_image;
//		echo $aim_image;
//		echo $yim_image;
//		echo $msnm_image;
//		echo "<br>";
    }
}
function get_DateTime($string)
{
    $yyyy = mb_substr($string, 0, 4);

    $month = mb_substr($string, 4, 2);

    $dd = mb_substr($string, 6, 2);

    $hh = mb_substr($string, 8, 2);

    $mm = mb_substr($string, 10, 2);

    $ss = mb_substr($string, 12, 2);

    $ftime = "$dd.$month.$yyyy / $hh:$mm";

    return $ftime;
}

function GetFooter()
{
    return "<br><div align='center'><a href='http://www.tipsmitgrips.de'><b>Flashgames 0.9</b></a><a href='http://bluetopia.homeip.net'> (based on myAlbum)</div>" .
           "<div align='center'><a href='http://pnflashgames.com/linkback.php?type=xoops' target='_blank'>Turbinado pelo pnFlashGames.com</a></div>" .
           "<div align='center'><a href='http://pnflashgames.com/linkback.php?type=xoops' target='_blank'><img src='images/poweredByButton.gif' border='0'></a></div>";
}
function pnFlashGames_getDomain()
{
    $url = 'http://' . $GLOBALS['HTTP_HOST'] . '/';

    // get host name from URL

    preg_match(
        "/^(http:\/\/)?([^\/]+)/i",
        $url,
        $matches
    );

    $host = $matches[2];

    $host = str_replace('www.', '', $host);

    return $host;
}

function pnFlashGames_getChecksum($file)
{
    if ($fp = fopen($file, 'rb')) {
        $filecontent = fread($fp, filesize($file));

        fclose($fp);

        return md5($filecontent);
    }

    return false;
}
function pnFlashGames_storeScore($xoopsDB, $gid, $uname, $score, $ip)
{
    // Get the game's information

    $checksql = 'SELECT * FROM ' . $xoopsDB->prefix('flashgames_games') . " WHERE lid = $gid";

    $result = $xoopsDB->query($checksql);

    $gameInfo = $xoopsDB->fetchArray($result);

    if ('3' == $gameInfo['gameType'] || '4' == $gameInfo['gameType']) {
        //This game uses a time based scoring method

        if (false !== mb_strstr($score, ':')) {
            // a formated time string was passed... convert it to seconds

            $timestamp = strtotime($score);

            $formatedTime = strftime('%H:%M:%S', $timestamp);

            $hours = mb_substr($formatedTime, 0, 2);

            $minutes = mb_substr($formatedTime, 3, 2);

            $seconds = mb_substr($formatedTime, 6, 2);

            $numSeconds = (($hours * 60) * 60) + ($minutes * 60) + $seconds;

            $score = $numSeconds;
        }

        // a straight up integer value was passed, store it straight up as a number of seconds.
    }

    $scorestable = $xoopsDB->prefix('flashgames_score');

    $numscores = pnFlashGames_countrows($xoopsDB, $scorestable);

    // Configuration constants from cache/config.php
    $table_max = $flashgames_scoresave;	// in cache/config.php

    // First check to see if this user has stored a high score for this game yet.

    // Each user is allowed to store one score per game, so we check first to

    // make sure this is not below a previous score

    $checksql = "SELECT score FROM $scorestable
            	WHERE  name='$uname'
            	AND    lid=$gid";

    $check = $GLOBALS['xoopsDB']->queryF($checksql);

    if ($GLOBALS['xoopsDB']->getRowsNum($check) < 1) {
        //No rows found, this user has not stored a high score for this game yet

        $sql = "INSERT INTO $scorestable
                SET lid=$gid,
                    name='$uname',
                    score=$score,
					ip='$ip',
                    date=NOW()";

        // Check table size and prune if necessary

        if ($numscores >= $table_max) {
            switch ($gameInfo['gametype']) {
                case '1':
                case '3':
                    $orderby = 'DESC';
                    break;
                case '2':
                case '4':
                    $orderby = 'ASC';
                    break;
            }

            //			$query = $GLOBALS['xoopsDB']->queryF("SELECT name, score FROM $scorestable where lid = '$gameid' ORDER BY score $orderby LIMIT 0, 1");
//			$row = $GLOBALS['xoopsDB']->fetchRow($query);
//			if ($good_score) $GLOBALS['xoopsDB']->queryF("DELETE FROM $scorestable WHERE name='{$row[0]}'");
        }
    } else {
        $oldscore = $GLOBALS['xoopsDB']->fetchBoth($check);

        $oldscore = $oldscore['score'];

        switch ($gameInfo['gametype']) {
            case '1':
            case '3':
                if ($oldscore < $score) {
                    //Row found but the new score is higher, so update the old one

                    $sql = "UPDATE $scorestable
                            SET    score=$score,
								   ip='$ip',
                                   date=NOW()
                            WHERE  name='$uname'
                            AND    lid=$gid";
                } else {
                    //Row found but the new score is lower, so do nothing

                    $sql = '';
                }
                break;
            case '2':   //both 2 and 3 are lowest score wins type games, so store if the oldscore was higher
            case '4':   //at this point, even though 3 is time based, score has been converted to seconds, so this will still work fine
                if ($oldscore > $score) {
                    //Row found but the new score is higher, so update the old one

                    $sql = "UPDATE $scorestable
                            SET    score=$score,
								   ip='$ip',
                                   date=NOW()
                            WHERE  name='$uname'
                            AND    lid=$gid";
                } else {
                    //Row found but the new score is lower, so do nothing

                    $sql = '';
                }
        }
    }

    if ('' != $sql) {
        //Need to do something

        $GLOBALS['xoopsDB']->queryF($sql);
    }

    // Return true

    return true;
}
function pnFlashGames_countrows($xoopsDB, $table)
{
    $result = $xoopsDB->query("SELECT COUNT(1) as rowcount FROM $table");

    $count = $xoopsDB->fetchArray($result);

    return $count['rowcount'];
}
function pnFlashGames_saveGame($xoopsDB, $gid, $uname, $gameData)
{
    $savedgames = $xoopsDB->prefix('flashgames_savedGames');

    // First check to see if this user has stored game data for this game yet.

    // Each user is allowed to store one game data, so we check first to

    // make sure this is not below a previous game data

    $checksql = "SELECT COUNT(1) as rowcount FROM $savedgames
            	 WHERE  name='$uname'
            	 AND    lid=$gid";

    $check = $GLOBALS['xoopsDB']->queryF($checksql);

    $count = $GLOBALS['xoopsDB']->fetchBoth($check);

    $count = $count['rowcount'];

    if ($count < 1) {
        //No rows found, this user has not stored a high score for this game yet

        $sql = "INSERT INTO $savedgames
                SET lid=$gid,
                    name='$uname',
                    gamedata='$gameData',
                    date=NOW()";
    } else {
        //old gameData found so replace it with the new one.

        $sql = "UPDATE $savedgames
                SET    gamedata='$gameData',
                       date=NOW()
                WHERE  name='$uname'
                AND    lid=$gid";
    }

    if ('' != $sql) {
        //Need to do something

        //print "$sql<br>";

        $GLOBALS['xoopsDB']->queryF($sql);

        //print $GLOBALS['xoopsDB']->error();
    }

    // Check for an error with the database code, and if so set an appropriate

    // error message and return

    if (0 != $GLOBALS['xoopsDB']->errno()) {
        return false;
    }

    // Return true

    return true;
}
function pnFlashGames_loadGame($xoopsDB, $gid, $uname)
{
    $savedgames = $xoopsDB->prefix('flashgames_savedGames');

    $sql = "SELECT gamedata
            FROM $savedgames
            WHERE lid=$gid
            AND name='$uname'";

    $result = $GLOBALS['xoopsDB']->queryF($sql);

    if (0 != $GLOBALS['xoopsDB']->errno()) {
        return false;
    }

    if ($GLOBALS['xoopsDB']->getRowsNum($result) < 1) {
        //No data for this game and user yet...

        return '';
    }

    $gameData = $GLOBALS['xoopsDB']->fetchBoth($result);

    //Flash will unencode the data automatically, this way the data is sent back exactly as it came...

    return urlencode($gameData[0]);
}
function pnFlashGames_loadGameScores($xoopsDB, $gid)
{
    // Get the game's information

    $checksql = 'SELECT * FROM ' . $xoopsDB->prefix('flashgames_games') . " WHERE lid = $gid";

    $result = $xoopsDB->query($checksql);

    $gameInfo = $xoopsDB->fetchArray($result);

    if (1 == $gameInfo['gametype'] || 3 == $gameInfo['gameType']) {
        // sort asc

        $orderby = 'ASC';
    } else {
        $orderby = 'DESC';
    }

    $gamescores = $xoopsDB->prefix('flashgames_score');

    $sql = "SELECT score, name, date
            FROM $gamescores
            WHERE lid=$gid
			ORDER BY score $orderby";

    $result = $GLOBALS['xoopsDB']->queryF($sql);

    $output = '<scorelist>';

    if ($GLOBALS['xoopsDB']->getRowsNum($result) > 0) {
        //Found some highscores

        $dateInfo = getdate();

        $rank = 1;

        while ($highscore = $GLOBALS['xoopsDB']->fetchArray($result)) {
            $output .= "<score rank='" . $rank++ . "' score='{$highscore[score]}' player='{$highscore[name]}' date='" . get_DateTime($highscore['date']) . "'>\n";
        }
    }

    $output .= '</scorelist>';

    $output = urlencode($output);

    return $output;
}
