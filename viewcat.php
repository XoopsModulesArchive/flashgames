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

$mytree = new XoopsTree($xoopsDB->prefix('flashgames_cat'), 'cid', 'pid');

$cid = $_GET['cid'];
$lid = $_GET['uid'];
require XOOPS_ROOT_PATH . '/header.php';
//OpenTable();   // Oka
// Oka
echo "<table width='100%' border='0' cellspacing='1' cellpadding='8' style='border: 0px solid #2F5376;'><tr class='bg4'><td valign='top'>\n";

mainheader();
if ('' != $_GET['show']) {
    $show = $_GET['show'];
} else {
    $show = $mylinks_perpage;
}
$min = $_GET['min'] ?? 0;
if (!isset($max)) {
    $max = $min + $show;
}
if (isset($_GET['orderby'])) {
    $orderby = convertorderbyin($_GET['orderby']);
} else {
    $orderby = 'title ASC';
}

echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td align='center'>\n";

if (!isset($_GET['uid'])) {
    echo "<table width='100%' cellspacing='1' cellpadding='2' border='0' class='bg3'><tr><td>\n";

    //   $pathstring = "<a href='index.php'>"._ALBM_MAIN."</a>&nbsp;:&nbsp;"; // Oka
    $pathstring = "<a href='index.php'>Übersicht</a>&nbsp;:&nbsp;";  // OKa

    $nicepath = $mytree->getNicePathFromId($cid, 'title', 'viewcat.php?op=');

    $pathstring .= $nicepath;

    echo "<td align='left'><b>" . $pathstring . '</b></td>';

    if ($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())) {
        echo "</td><td align='right'><a href='submit.php?cid=$cid'><b>Add game</b></a></td></tr>";
    }

    echo '</table><br>';

    // get child category objects

    $arr = [];

    $arr = $mytree->getFirstChild($cid, 'title');

    if (count($arr) > 0) {
        echo '</td></tr>';

        //	echo "<tr><td align=\"left\"><h4>"._ALBM_CATEGORIES."</h4></td></tr>\n";

        echo '<tr><td align="left">';

        $scount = 0;

        echo '<table width="90%"><tr>';

        foreach ($arr as $ele) {
            $title = htmlspecialchars($ele['title'], ENT_QUOTES | ENT_HTML5);

            $totallink = getTotalItems($ele['cid'], 1);

            echo "<td align=\"left\"><b><a href='viewcat.php?cid=" . $ele['cid'] . "'>" . $title . '</a></b>&nbsp;(' . $totallink . ')&nbsp;&nbsp;</td>';

            $scount++;

            if (4 == $scount) {
                echo '</tr><tr>';

                $scount = 0;
            }
        }

        echo '<HR>';

        echo "</tr></table><br>\n";

        echo "<br><HR>\n";
    }
}

if (isset($_GET['uid'])) {
    if ($_GET['uid'] = -1) {
        $where = 'submitter=' . $xoopsUser->uid();

        $where2 = 'uid=' . $xoopsUser->uid();
    } else {
        $where = "submitter=$uid";

        $where2 = "uid=$uid";
    }
} else {
    $where = "cid=$cid";

    $where2 = "cid=$cid";
}

$fullcountresult = $xoopsDB->query('select count(*) from ' . $xoopsDB->prefix('flashgames_games') . " where $where and status>0");
[$numrows] = $xoopsDB->fetchRow($fullcountresult);

if ($numrows > 0) {
    $q = 'select l.lid, l.title, l.ext, l.res_x, l.res_y, l.status, l.date, l.hits, l.rating, l.votes, l.comments, l.submitter, t.description from ' . $xoopsDB->prefix('flashgames_games') . ' l, ' . $xoopsDB->prefix('flashgames_text') . " t where $where and l.lid=t.lid and status>0 order by $orderby";

    $result = $xoopsDB->query($q, $show, $min);

    //if 2 or more items in result, show the sort menu

    if ($numrows > 1) {
        $orderbyTrans = convertorderbytrans($orderby);

        echo '<HR>';

        echo '<br><small><center>' . _ALBM_SORTBY . '&nbsp;&nbsp;
              	' . _ALBM_TITLE . " (<a href='viewcat.php?$where2&orderby=titleA'><img src=\"images/up.gif\" border=\"0\" align=\"middle\" alt=\"\"></a><a href='viewcat.php?$where2&orderby=titleD'><img src=\"images/down.gif\" border=\"0\" align=\"middle\" alt=\"\"></a>)
              	" . _ALBM_DATE . " (<a href='viewcat.php?$where2&orderby=dateA'><img src=\"images/up.gif\" border=\"0\" align=\"middle\" alt=\"\"></a><a href='viewcat.php?$where2&orderby=dateD'><img src=\"images/down.gif\" border=\"0\" align=\"middle\" alt=\"\"></a>)
              " . _ALBM_RATING . " (<a href='viewcat.php?$where2&orderby=ratingA'><img src=\"images/up.gif\" border=\"0\" align=\"middle\" alt=\"\"></a><a href='viewcat.php?$where2&orderby=ratingD'><img src=\"images/down.gif\" border=\"0\" align=\"middle\" alt=\"\"></a>)
              " . _ALBM_POPULARITY . " (<a href='viewcat.php?$where2&orderby=hitsA'><img src=\"images/up.gif\" border=\"0\" align=\"middle\" alt=\"\"></a><a href='viewcat.php?$where2&orderby=hitsD'><img src=\"images/down.gif\" border=\"0\" align=\"middle\" alt=\"\"></a>)
              	";

        echo '<b><br>';

        printf(_ALBM_CURSORTEDBY, $orderbyTrans);

        echo '</b><HR></center><br><br>';
    }

    //		echo "</table>";
    echo '<br>'; // Oka
    echo '<table width="100%" cellspacing=0 cellpadding=10 border=0>';

    while (list($lid, $ltitle, $ext, $res_x, $res_y, $status, $time, $hits, $rating, $votes, $comments, $submitter, $description) = $xoopsDB->fetchRow($result)) {
        $rating = number_format($rating, 2);

        $ltitle = htmlspecialchars($ltitle, ENT_QUOTES | ENT_HTML5);

        $url = htmlspecialchars($url, ENT_QUOTES | ENT_HTML5);

        $url = urldecode($url);

        $email = htmlspecialchars($email, ENT_QUOTES | ENT_HTML5);

        $logourl = htmlspecialchars($logourl, ENT_QUOTES | ENT_HTML5);

        #		$logourl = urldecode($logourl);

        $datetime = formatTimestamp($time, 's');

        $description = $myts->displayTarea($description, 0);

        include 'include/linkformat.php';
    }

    echo '</table>';

    $orderby = convertorderbyout($orderby);

    //Calculates how many pages exist.  Which page one should be on, etc...

    $linkpages = ceil($numrows / $show);

    if (0 == $numrows % $show) {
        $linkpages -= 1;
    }

    //Page Numbering

    if (1 != $linkpages && 0 != $linkpages) {
        echo '<br><br>';

        $prev = $min - $show;

        if ($prev >= 0) {
            echo "&nbsp;<a href='viewcat.php?$where2&min=$prev&orderby=$orderby&show=$show'>";

            echo '<b>&lt; ' . _ALBM_PREVIOUS . ' ]</b></a>&nbsp;';
        }

        $counter = 1;

        $currentpage = ($max / $show);

        while ($counter <= $linkpages) {
            $mintemp = ($show * $counter) - $show;

            if ($counter == $currentpage) {
                echo "<b>$counter</b>&nbsp;";
            } else {
                echo "<a href='viewcat.php?$where2&min=$mintemp&orderby=$orderby&show=$show'>$counter</a>&nbsp;";
            }

            $counter++;
        }

        if ($numrows > $max) {
            echo "&nbsp;<a href='viewcat.php?$where2&min=$max&orderby=$orderby&show=$show'>";

            echo '<b>[ ' . _ALBM_NEXT . ' &gt;</b></a>';
        }
    }
}
echo "</td></tr></table>\n";
CloseTable();

include 'footer.php';
