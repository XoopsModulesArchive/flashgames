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
require dirname(__DIR__, 2) . '/mainfile.php';
require XOOPS_ROOT_PATH . '/header.php';

require __DIR__ . '/cache/config.php';

if (empty($xoopsUser)) {
    $uid = 0;
} else {
    $uid = $xoopsUser->getVar('uid');
}
if (!isset($xoopsDB)) {
    die('<H1>Database errror</H1>');
}

# .. Neave Games MySQL/PHP high scores script
# .. Requires at least PHP 4.1.0 and MySQL on your server

# .. Upload this file to your server and rename for better security
# .. Set up a MySQL table for the game containing 'name', 'score' and 'ip' and a games_banned_ip table
# .. Edit the game Flash file to point to this file on your server

# .. For more help see http://www.neave.com/games/help/

#$name_max = 16; # Maximum player name length allowed
$display_max = 100; # Maximum number of scores to display (multiple of 10)
$table_max = 125; # Maximum number of scores kept in table
$table_max = 7; # Maximum number of scores kept in table

$table_max = $flashgames_scoresave;
$display_max = $flashgames_scoreshow;

# Error handler
function error_msg($msg)
{
    exit("success=0&errorMsg=$msg");
}

# Store POSTed info
#@$player_name = $_POST['name'];
#begin  OKa
# get player name from DB
$result = $xoopsDB->query('SELECT uname FROM ' . $xoopsDB->prefix('users') . " WHERE uid = $uid") || die('Error');
$myrow = $xoopsDB->fetchArray($result);
$player_name = $myrow['uname'];
#$record_date = NOW();
#end oka

@$player_score = $_POST['score'];
@$game_name = $_POST['game'];

# SQL table name is 'games_pacman' for Pac-Man, etc.
#$table_name = 'xoops_games_' . strtolower($game_name);
//$table_name = 'xoops_games_score';

$table_name = $xoopsDB->prefix('flashgames_score');

$player_ip = $_SERVER['REMOTE_ADDR'];

# oka game-id auslesen
$gameid = $_POST['id'];

# Need table
if (!isset($game_name)) {
    error_msg('Could not access game table.');
}

# DB config - it's more secure to keep this in an external PHP file, not publically accessible.
# Set appropriate $db_name, $db_hostname, $db_username and $db_password variables
#require_once __DIR__ . '//home/yourname/includes/db_inc.php';
#require "funktion.php";
#$db_name = 'yourname';
#$db_username = 'yourname';
#$db_password = 'password';
#$db_hostname = 'localhost';

# Connect to DB
#@mysql_connect($db_hostname, $db_username, $db_password) or error_msg('Could not connect to database.');
#mysqli_select_db($GLOBALS['xoopsDB']->conn, $db_name) or error_msg('Could not access database.');

# Saving new score?
if (isset($player_score) && is_numeric($player_score) && isset($player_name) && mb_strlen($player_name) > 0) {
    # Is this IP banned?

    #	$query = $GLOBALS['xoopsDB']->queryF('SELECT ip FROM games_banned_ip') or error_msg('Could not access database.');

    #	while (false !== ($row = $GLOBALS['xoopsDB']->fetchRow($query))) {

    #		if ($player_ip == $row[0]) error_msg('Sorry, high scores have been disabled for your computer.');

    #	}

    # Has this name played already?

    $query = $GLOBALS['xoopsDB']->queryF("SELECT lid, name, score FROM $table_name") or error_msg('Could not access database.');

    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($query);

    $name_found = false;

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchRow($query))) {
        if ($player_name == $row[1] && $gameid == $row[0]) {
            $name_found = true;

            break;
        }
    }

    # oka

    #if ($gameid == 32)

    #{

    #  $player_name = '32___32';

    #}

    # score in highscoretabelle speichern

    if ($name_found) {
        # If name already exists, and score is good enough, update it

        if (((int)$player_score) > ((int)$row[2])) {
            $GLOBALS['xoopsDB']->queryF("UPDATE $table_name SET score='$player_score'  WHERE lid = '$gameid' and  name='$player_name' ") or error_msg('Could not update score.');
        }
    } else {
        # If scores table is full, check score and delete lowest entry before inserting

        if ($num_rows >= $table_max) {
            $query = $GLOBALS['xoopsDB']->queryF("SELECT name, score FROM $table_name where lid = '$gameid' ORDER BY score ASC LIMIT 0, 1") or error_msg('Could not retrieve scores.');

            $row = $GLOBALS['xoopsDB']->fetchRow($query);

            $good_score = (((int)$player_score) > ((int)$row[2]));

            if ($good_score) {
                $GLOBALS['xoopsDB']->queryF("DELETE FROM $table_name WHERE name='$row[0]'") or error_msg('Could not delete score.');
            }
        } else {
            $good_score = true;
        }

        # Insert new name, score and ip

        if ($good_score) {
            $GLOBALS['xoopsDB']->queryF("INSERT INTO $table_name (lid, name, score, ip ) VALUES ('$gameid', '$player_name', '$player_score', '$player_ip')") or error_msg('Could not insert score.');
        }
    }
}

# Return new scores table
$query = $GLOBALS['xoopsDB']->queryF("SELECT name, score FROM $table_name  ORDER BY score DESC LIMIT 0, $display_max") or error_msg('Could not retrieve scores.');

$i = 1;
echo 'success=1&errorMsg=OK&maxScore=' . $display_max;
while (false !== ($row = $GLOBALS['xoopsDB']->fetchRow($query))) {
    echo "&name$i=$row[0]&score$i=$row[1]";

    $i++;
}
