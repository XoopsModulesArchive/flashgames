Flashgames Module V0.9 for Xoops 2.0.x


README DEUTSCH                 |
--------------------------------
Autor: Oliver Kaufhold (aka gripsmaker)

Beschreibung:
Dieses Xoops-Modul basiert auf dem Galeriemodul myalbum. Statt Bilder, werden jedoch Flashanwendungen unterstützt;
In erster Linie Flashspiele.

Weitere Infos und Support unter www.tipsmitgrips.de



Features
----------------------------------
- Unterstützung von Flashanwendungen (swf)
- Unterstützung von highscorebasierten Spielen
- automat. Speicherung von Highscores
- Übersicht aller Highscores
- Bewertung und Kommentarfunktionen
...


Installationsanweisungen       |
--------------------------------

Den Ordner "Flashgames" in das Modules Verzeichnis kopieren und über das Admin Menü installieren
Die Ordner /games und /cache benötigenb Schreibrechte (chmod 777)

Deinstallationsanweisungen       |
--------------------------------

Das Modul über das Admin Modul deinstallieren. Anschließend die Spiele und Bilder (1.swf,1.gif...) aus dem Ordner /games löschen.



Installation von Spielen
-------------------------------|
- Zunächst eine Kategorie in der Administration anlegen

Titel:           eingeben
Beschreibung:    eingeben
Kategorie:       auswählen
Bild:            Vorschaubild auswählen zum jeweiligen Spiel, unterstützt wird 'jpg' und 'gif'
Spiel auswählen: Spiel oder Flashanwendung auswählen mit der Endung 'swf'
Highscoretyp:    '0' für Spiele ohne Highscore-Support
                 '1' scorebasiertes Spiel, höchster Score gewinnt
                 '2' scorebasiertes Spiel, niedrigster Score gewinnt 
                 '3' zeitbasiertes Spiel, höchste Zeit gewinnt 
                 '4' zeitbasiertes Spiel, schnellste Zeit gewinnt 

Beispiel: Der Highscoretyp für Pacman ist '1'
                 
Achtung: Vorschaubild und Spiel können später nicht mehr geändert werden (außer natürlich über die DB)

Ändern von Spielen
-------------------------------|
Titel:           kann geändert werden
Beschreibung:    kann geändert werden
Kategorie:       kann geändert werden
Highscoretyp:    kann geändert werden
Gültig:          falls gesetzt, ist Spiel aktiv
Löschen:         Das Spiel und evtl. Highscores werden komplett gelöscht !!!
Score löschen:   Nur die Highscores zum Spiel werden gelöscht.



-------------------------------------------

 Changelog:
 ==========

0.9
erste Testversion
-------------------------------------------


Credits
--------
Xoops module "myalbum"              - http://bluetopia.homeip.net/    
Postnuke module "pnflashgames"      - http://www.pnflashgames.com/ (Lee Eason)
XOOPS PHP Content Management System - https://xoops.org/
