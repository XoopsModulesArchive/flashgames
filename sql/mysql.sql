# phpMyAdmin MySQL-Dump
# version 2.2.2
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
#
# --------------------------------------------------------

#
# Table structure for table `flashgames_cat`
#

CREATE TABLE flashgames_cat (
    cid    INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    pid    INT(5) UNSIGNED NOT NULL DEFAULT '0',
    title  VARCHAR(50)     NOT NULL DEFAULT '',
    imgurl VARCHAR(150)    NOT NULL DEFAULT '',
    PRIMARY KEY (cid),
    KEY pid (pid)
)
    ENGINE = ISAM;
# --------------------------------------------------------

#
# Table structure for table `flashgames_games`
#

CREATE TABLE flashgames_games (
    lid       INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    cid       INT(5) UNSIGNED  NOT NULL DEFAULT '0',
    title     VARCHAR(100)     NOT NULL DEFAULT '',
    ext       VARCHAR(10)      NOT NULL DEFAULT '',
    res_x     INT(11)          NOT NULL DEFAULT '',
    res_y     INT(11)          NOT NULL DEFAULT '',
    bgcolor   VARCHAR(6)       NOT NULL DEFAULT 'FFFFFF',
    submitter INT(11) UNSIGNED NOT NULL DEFAULT '0',
    status    TINYINT(2)       NOT NULL DEFAULT '0',
    date      INT(10)          NOT NULL DEFAULT '0',
    hits      INT(11) UNSIGNED NOT NULL DEFAULT '0',
    rating    DOUBLE(6, 4)     NOT NULL DEFAULT '0.0000',
    votes     INT(11) UNSIGNED NOT NULL DEFAULT '0',
    comments  INT(11) UNSIGNED NOT NULL DEFAULT '0',
    gametype  TINYINT(2)       NOT NULL DEFAULT '0',
    license   TINYTEXT         NOT NULL,
    PRIMARY KEY (lid),
    KEY cid (cid),
    KEY status (status),
    KEY title (title(40))
)
    ENGINE = ISAM;
# --------------------------------------------------------

#
# Table structure for table `flashgames_text`
#

CREATE TABLE flashgames_text (
    lid         INT(11) UNSIGNED NOT NULL DEFAULT '0',
    description TEXT             NOT NULL,
    KEY lid (lid)
)
    ENGINE = ISAM;
# --------------------------------------------------------

#
# Table structure for table `flashgames_votedata`
#

CREATE TABLE flashgames_votedata (
    ratingid        INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
    lid             INT(11) UNSIGNED    NOT NULL DEFAULT '0',
    ratinguser      INT(11) UNSIGNED    NOT NULL DEFAULT '0',
    rating          TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
    ratinghostname  VARCHAR(60)         NOT NULL DEFAULT '',
    ratingtimestamp INT(10)             NOT NULL DEFAULT '0',
    PRIMARY KEY (ratingid),
    KEY ratinguser (ratinguser),
    KEY ratinghostname (ratinghostname)
)
    ENGINE = ISAM;

#
# Table structure for table `flashgames_comments`
#

CREATE TABLE flashgames_comments (
    comment_id INT(8) UNSIGNED     NOT NULL AUTO_INCREMENT,
    pid        INT(8) UNSIGNED     NOT NULL DEFAULT '0',
    item_id    INT(8) UNSIGNED     NOT NULL DEFAULT '0',
    date       INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    user_id    INT(5) UNSIGNED     NOT NULL DEFAULT '0',
    ip         VARCHAR(15)         NOT NULL DEFAULT '',
    subject    VARCHAR(255)        NOT NULL DEFAULT '',
    comment    TEXT                NOT NULL,
    nohtml     TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    nosmiley   TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    noxcode    TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    icon       VARCHAR(25)         NOT NULL DEFAULT '',
    PRIMARY KEY (comment_id),
    KEY pid (pid),
    KEY item_id (item_id),
    KEY user_id (user_id),
    KEY subject (subject(40))
)
    ENGINE = ISAM;


#
# Tabellenstruktur für Tabelle `xoops_flashgames_score`
#

CREATE TABLE flashgames_score (
    lid   INT(11) UNSIGNED NOT NULL DEFAULT '0',
    name  VARCHAR(20)      NOT NULL DEFAULT '',
    score INT(11)          NOT NULL DEFAULT '0',
    ip    VARCHAR(15)               DEFAULT NULL,
    date  TIMESTAMP(14)    NOT NULL,
    PRIMARY KEY (lid, name)
)
    ENGINE = ISAM;


#
# Table structure for savedGames table xoops_flashgames_savedGames
#

CREATE TABLE flashgames_savedGames (
    lid      INT(11) UNSIGNED NOT NULL DEFAULT '0',
    name     VARCHAR(20)      NOT NULL DEFAULT '',
    gamedata TEXT             NOT NULL DEFAULT '',
    date     TIMESTAMP(14)    NOT NULL,
    PRIMARY KEY (lid, name)
)
    ENGINE = ISAM;

