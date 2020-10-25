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

/******************************************************************************
 * Function: b_mylinks_top_show
 * Input   : $options[0] = date for the most recent links
 *                    hits for the most popular links
 *           $block['content'] = The optional above content
 *           $options[1]   = How many reviews are displayes
 * Output  : Returns the desired most recent or most popular links
 *****************************************************************************
 * @param $options
 * @return array
 */
function b_flashgames_top_show_old($options)
{
    global $xoopsDB;

    $block = [];

    $block['content'] = '<small>';

    $myts = MyTextSanitizer::getInstance();

    $result = $xoopsDB->query('SELECT lid, cid, title, date, hits FROM ' . $xoopsDB->prefix('flashgames_games') . ' WHERE status>0 ORDER BY ' . $options[0] . ' DESC', $options[1], 0);

    while ($myrow = $xoopsDB->fetchArray($result)) {
        $link = htmlspecialchars($myrow['title'], ENT_QUOTES | ENT_HTML5);

        if (!XOOPS_USE_MULTIBYTES) {
            if (mb_strlen($link) >= 19) {
                $link = mb_substr($link, 0, 18) . '...';
            }
        }

        $block['content'] .= "&nbsp;&nbsp;<strong><big>&middot;</big></strong>&nbsp;<a href='" . XOOPS_URL . '/modules/flashgames/game.php?lid=' . $myrow['lid'] . "'>" . $link . '</a> ';

        if ('date' == $options[0]) {
            $block['content'] .= '(' . formatTimestamp($myrow['date'], 's') . ')<br>';

            $block['title'] = _ALBM_FLASHGAMES_TITLE1;
        } elseif ('hits' == $options[0]) {
            $block['content'] .= '(' . $myrow['hits'] . ')<br>';

            $block['title'] = _ALBM_FLASHGAMES_TITLE2;
        }
    }

    $block['content'] .= '</small>';

    return $block;
}

function b_flashgames_top_show($options)
{
    global $xoopsDB;

    $myts = MyTextSanitizer::getInstance();

    $block = [];

    $sql = 'SELECT lid, cid, title, date, hits  FROM ' . $xoopsDB->prefix('flashgames_games') . ' WHERE status >0 ORDER BY ' . $options[0] . ' DESC';

    $result = $xoopsDB->query($sql, $options[1], 0);

    while ($myrow = $xoopsDB->fetchArray($result)) {
        $news = [];

        $title = htmlspecialchars($myrow['title'], ENT_QUOTES | ENT_HTML5);

        ///	if ( !XOOPS_USE_MULTIBYTES ) {

        //		if (strlen($myrow['title']) >= $options[2]) {

        //			$title = htmlspecialchars(substr($myrow['title'],0,($options[2] -1)))."..";

        //		}

        //	}

        $news['title'] = $title;

        $news['lid'] = $myrow['lid'];

        if ('date' == $options[0]) {
            $news['date'] = formatTimestamp($myrow['date'], 's');
        } elseif ('hits' == $options[0]) {
            $news['hits'] = $myrow['hits'];
        }

        $block['games'][] = $news;
    }

    return $block;
}

function b_flashgames_top_edit($options)
{
    $form = '' . _ALBM_MYLINKS_DISP . '&nbsp;';

    $form .= "<input type='hidden' name='options[]' value='";

    if ('date' == $options[0]) {
        $form .= "date'";
    } else {
        $form .= "hits'";
    }

    $form .= '>';

    $form .= "<input type='text' name='options[]' value='" . $options[1] . "'>&nbsp;" . _ALBM_MYLINKS_LINKS . '';

    return $form;
}

// functions for showing topplayers
function b_flashgames_topplayer_show($options)
{
    // Oka

    //Manually assign the weights to each score position for now

    $firstPlace = 10;

    $secondPlace = 9;

    $thirdPlace = 8;

    $fourthPlace = 7;

    $fifthPlace = 6;

    $sixthPlace = 5;

    $seventhPlace = 4;

    $eighthPlace = 3;

    $ninthPlace = 2;

    $tenthPlace = 1;

    // end OKa

    global $xoopsDB;

    $myts = MyTextSanitizer::getInstance();

    $block = [];

    $sql = 'SELECT lid, cid, title, date, hits  FROM ' . $xoopsDB->prefix('flashgames_games') . ' WHERE status >0 ORDER BY ' . $options[0] . ' DESC';

    $result = $xoopsDB->query($sql, $options[1], 0);

    while ($myrow = $xoopsDB->fetchArray($result)) {
        $news = [];

        $title = htmlspecialchars($myrow['title'], ENT_QUOTES | ENT_HTML5);

        ///	if ( !XOOPS_USE_MULTIBYTES ) {

        //		if (strlen($myrow['title']) >= $options[2]) {

        //			$title = htmlspecialchars(substr($myrow['title'],0,($options[2] -1)))."..";

        //		}

        //	}

        $news['title'] = $title;

        $news['lid'] = $myrow['lid'];

        if ('date' == $options[0]) {
            $news['date'] = formatTimestamp($myrow['date'], 's');
        } elseif ('hits' == $options[0]) {
            $news['hits'] = $myrow['hits'];
        }

        $block['games'][] = $news;
    }

    return $block;
}

function b_flashgames_topplayer_edit($options)
{
    $form = '' . _ALBM_MYLINKS_DISP . '&nbsp;';

    //	$form .= "<input type='hidden' name='options[]' value='";

    //	if($options[0] == "date"){

    //		$form .= "date'";

    //	}else {

    //		$form .= "hits'";

    //	}

    //	$form .= ">";

    $form .= "<input type='text' name='options[]' value='" . $options[0] . "'>&nbsp;" . _ALBM_MYLINKS_LINKS . '';

    return $form;
}








