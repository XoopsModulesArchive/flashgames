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
echo '<tr>';
if ($mylinks_useshots) {
    $tablewidth = $mylinks_shotwidth + 60;

    if (1 == $big) {
        echo "<td align='center'><center>";

        $img = "<a href='" . XOOPS_URL . "/modules/flashgames/game.php?lid=$lid&full=1'><img src='" . XOOPS_URL . "/modules/flashgames/games/$lid.$ext' width='$res_x' height='$res_y' alt=''></a>";
    } else {
        echo "<td width='$tablewidth' align='center'>";

        //		$img = "<a href='".XOOPS_URL."/modules/flashgames/game.php?lid=$lid'><img src='".XOOPS_URL."/modules/flashgames/games/thumbs/$lid.$ext' width='$mylinks_shotwidth' alt=''></a>";

        $pfad = 'games/';

        // Check if screenshot is in gif format

        $filename = "$pfad$lid.gif";

        if (file_exists($filename)) {
            $img = "<a href='" . XOOPS_URL . "/modules/flashgames/game.php?lid=$lid'><img src='" . XOOPS_URL . "/modules/flashgames/games/$lid.gif' width='$mylinks_shotwidth' alt=''></a>";

            $imag = 'yes';
        }

        // Check if screenshot is in jpg format

        $filename = "$pfad$lid.jpg";

        if (file_exists($filename)) {
            $img = "<a href='" . XOOPS_URL . "/modules/flashgames/game.php?lid=$lid'><img src='" . XOOPS_URL . "/modules/flashgames/games/$lid.jpg' width='$mylinks_shotwidth' alt=''></a>";

            $imag = 'yes';
        }

        // no image available

        if ('yes' != $imag) {
            $img = "<a href='" . XOOPS_URL . "/modules/flashgames/game.php?lid=$lid'><img src='" . XOOPS_URL . "/modules/flashgames/games/noimage.gif' width='$mylinks_shotwidth' alt=''></a>";
        }
    }

    /*  fixme!
    	print "<table width=1% border=0 cellspacing=0 cellpadding=0>
    	<tr bgcolor=black><td colspan=3 height=1><img src='".XOOPS_URL."/modules/flashgames/images/pixel_trans.gif' width=1 height=1></td></tr><tr><td bgcolor=black width=1><img src='".XOOPS_URL."/modules/flashgames/images/pixel_trans.gif' width=1 height=1></td><td><center>$img</center></td><td bgcolor=black width=1><img src='".XOOPS_URL."/modules/flashgames/images/pixel_trans.gif' width=1 height=1></td></tr><tr bgcolor=black><td colspan=3 height=1><img src='".XOOPS_URL."/modules/flashgames/images/pixel_trans.gif'></td></tr>
    	</table>";
    */

    print "<br><br><center>$img</center><br><br>";

    if (1 == $big) {
        print '</td></tr><tr><td>';
    } else {
        print '</td><td>';
    }

    //	echo "</td>";
} else {
    echo '<td>';
}

global $xoopsUser;

if ($xoopsUser) {
    //echo $xoopsUser->uid();

    if ($xoopsUser->uid() == $submitter or $xoopsUser->isAdmin($xoopsModule->mid())) {
        //		echo "<a href=\"".XOOPS_URL."/modules/flashgames/editgame.php?lid=$lid\"><img src=\"".XOOPS_URL."/modules/flashgames/images/editicon.gif\" border=\"0\" alt=\""._ALBM_EDITTHISLINK."\"></a>  ";

        $edit = '<a href="' . XOOPS_URL . "/modules/flashgames/editgame.php?lid=$lid\"><img src=\"" . XOOPS_URL . '/modules/flashgames/images/editicon.gif" border="0" alt="' . _ALBM_EDITTHISLINK . '"></a>';
    }
}
// echo "<br>";
echo "<table width='400' border='0' cellspacing='5' cellpadding='0' >";   // Oka

//echo "<tr><td>$edit<a name=\"$lid\"></a><a href=\"".XOOPS_URL."/modules/flashgames/game.php?lid=".$lid."\"><b>$ltitle</b></a>";
echo "<br>&nbsp;$edit<a name=\"$lid\"></a><a href=\"" . XOOPS_URL . '/modules/flashgames/game.php?lid=' . $lid . "\"><b>$ltitle</b></a>";
newlinkgraphic($time, $status);
//echo "<br>";
popgraphic($hits);
//echo "</td></tr>";
// echo "<br>";
echo "<tr><td width='130'  align='left'><b>" . _ALBM_DESCRIPTIONC . "</b></td><td align='left'>$description</td></tr>";

// ShowSubmitter($submitter);

echo "<tr><td  align='left'><b>" . _ALBM_LASTUPDATEC . "</b></td><td align='left'>$datetime</td></tr>";
echo "<tr><td align='left'><b>" . _ALBM_HITSC . "</b></td><td align='left'>$hits</td></tr>";

//voting & comments stats

if ('0' != $rating || '0.0' != $rating) {
    if (1 == $votes) {
        $votestring = _ALBM_ONEVOTE;
    } else {
        $votestring = sprintf(_ALBM_NUMVOTES, $votes);
    }

    echo "<tr><td  align='left'><b>" . _ALBM_RATINGC . "</b></td><td align='left'>$rating ($votestring)&nbsp;&nbsp;&nbsp;<a href=\"" . XOOPS_URL . '/modules/flashgames/rategame.php?lid=' . $lid . '">' . _ALBM_RATETHISGAME . '</a></tr><td>';
} else {
    echo "<tr><td align='left'><a href=\"" . XOOPS_URL . '/modules/flashgames/rategame.php?lid=' . $lid . '">' . _ALBM_RATETHISGAME . '</a></tr><td>';
}

echo '<br><br><br>';

echo '</table>';

//if ($comments != 0) {
//	if ($comments == 1) {
//		$poststring = _ALBM_ONEPOST;
//	} else {
//		$poststring = sprintf(_ALBM_NUMPOSTS,$comments);
//	}
//	echo "<b>"._ALBM_COMMENTSC."</b>$poststring";
//}
//echo "<br><a href=\"".XOOPS_URL."/modules/flashgames/rategame.php?lid=".$lid."\">"._ALBM_RATETHISgame."</a>";
//echo " | <a href=\"".XOOPS_URL."/modules/flashgames/modlink.php?lid=".$lid."\">"._ALBM_MODIFY."</a>";
// Oka delete
//echo " | <a target='_top' href='mailto:?subject=".sprintf(_ALBM_INTRESTLINK,$xoopsConfig['sitename'])."&body=".sprintf(_ALBM_INTLINKFOUND,$xoopsConfig['sitename']).":  ".XOOPS_URL."/modules/flashgames/game.php?lid=".$lid."'>"._ALBM_TELLAFRIEND."</a>";
//echo " | <a href>"._ALBM_VSCOMMENTS."</a>";

echo '</td></tr>';
