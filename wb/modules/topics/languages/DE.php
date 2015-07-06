<?php

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2008, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

//Modulbeschreibung
$module_description = 'Topics ist &auml;hnlich dem News-Modul, aber f&uuml;r gr&ouml;&szlig;ere Mengen an Seiten ausgelegt.';

//Frontend
$MOD_TOPICS['SEE_ALSO_FRONTEND'] = 'Siehe auch:';
$MOD_TOPICS['SEE_NEXT_POST'] = 'Neuere Themen:';
$MOD_TOPICS['SEE_PREV_POST'] = '&Auml;ltere Themen:';

$MOD_TOPICS['COMMENT_SAVED'] = 'Danke, der Kommentar wurde gespeichert';
$MOD_TOPICS['COMMENT_MODERATE'] = 'Danke, der Kommentar erwartet die Freischaltung';

$MOD_TOPICS['LAST_MODIFIED'] = ', zuletzt modifiziert von';
$MOD_TOPICS['MODIFIED_DATE'] = 'am';
$MOD_TOPICS['MODIFIED_TIME'] = 'um';



//Variablen fuer backend Texte
$MOD_TOPICS['TOPIC'] = 'Thema';
$MOD_TOPICS['SHORT_DESCRIPTION'] = 'Kurzbeschreibung';
$MOD_TOPICS['TOPICSPAGE'] = '&Uuml;bersicht-Seite';
$MOD_TOPICS['PICTURE_DIR'] = 'Bilderverzeichnis';


//Settings
$MOD_TOPICS['SETTINGS'] = 'Topics Einstellungen';
$MOD_TOPICS['SECTIONSETTINGS'] = 'Seiteneinstellung';
$MOD_TOPICS['PICTURES'] = 'Bilder';
$MOD_TOPICS['VARIOUS'] = 'Weiteres';

$MOD_TOPICS['SORT_TOPICS'] = 'Sortierung';
$MOD_TOPICS['SORT_POSITION'] = 'Nach Position';
$MOD_TOPICS['SORT_PUBL'] = 'Nach Datum (Publ.)';
$MOD_TOPICS['SORT_SCORE'] = 'Nach Wichtigkeit (Beta)';
$MOD_TOPICS['SORT_EVENT'] = 'Eventkalender';
$MOD_TOPICS['SORT_TITLE'] = 'Nach Titel';

$MOD_TOPICS['TIME_WARNING1'] = 'HINWEIS: Das Start-Datum ist in der Vergangenheit!';
$MOD_TOPICS['TIME_WARNING2'] = 'HINWEIS: Das End-Datum ist vor dem Start-Datum. Dies wird korrigiert.';
$MOD_TOPICS['EVENT_DAYNAMES'] = array('So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So');
$MOD_TOPICS['EVENT_MONTHNAMES'] = array('Dez','Jan', 'Feb', 'M&auml;rz', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sept', 'Okt', 'Nov', 'Dez');



$MOD_TOPICS['TOPICS_PER_PAGE'] = 'Eintr&auml;ge pro Seite';
$MOD_TOPICS['SINGLETOPIC'] = 'Einzelansicht';

$MOD_TOPICS['EDITOR_HEIGHTS'] = 'H&ouml;he der Editoren';
$MOD_TOPICS['EDITOR_HEIGHTS_HINT'] = '(0 = kein Editor, keine Ausgabe)';
$MOD_TOPICS['OPEN_PRESETS_FILE'] = 'Presets-File &ouml;ffnen</a> (Hinweis: Es werden nicht die aktuellen, sondern die zuletzt gespeicherten Einstellungen verwendet!';

$MOD_TOPICS['TIMEBASED_PUBL'] = 'Datum (Verwendung)';
$MOD_TOPICS['DATE_USAGE_0'] = 'Ich k&uuml;mmere mich nicht darum';
$MOD_TOPICS['DATE_USAGE_1'] = 'Das Datum soll editierbar sein';
$MOD_TOPICS['DATE_USAGE_2'] = 'Zeitgesteuerte Ver&ouml;ffentlichung';
$MOD_TOPICS['DATE_USAGE_3'] = 'Auto-Archiv';
$MOD_TOPICS['TOPICSMASTER_SELECT'] = 'TopicsMaster Sections';

$MOD_TOPICS['MOVE_TO'] = 'verschieben zu:';
$MOD_TOPICS['AUTO_ARCHIVE'] = 'Auto-Archivierung:';
$MOD_TOPICS['AUTO_ARCHIVE_0'] = 'Aus';
$MOD_TOPICS['AUTO_ARCHIVE_1'] = 'Nach Start-Zeit';
$MOD_TOPICS['AUTO_ARCHIVE_2'] = 'Nach Stop-Zeit';
$MOD_TOPICS['AUTO_ARCHIVE_3'] = 'nach max Eintr&auml;ge pro Seite';

$MOD_TOPICS['COMMENTS_HINT'] = '<small>Individuelle Einstellungen der Topics ignorieren.<br/>&nbsp;</small>';

//TopicList
$MOD_TOPICS['LINK_TO'] = 'Hier die Topics w&auml;hlen, zu denen verlinkt werden sollten.<br/>Diese Links erscheinen unter &quot;siehe auch&quot;.';
$MOD_TOPICS['SWITCHTO'] = 'Bearbeiten';



//Modify Topic
$MOD_TOPICS['ACTIVE_0'] = 'Unver&ouml;ffentlicht';
$MOD_TOPICS['ACTIVE_1'] = 'Angemeldete Besucher';
$MOD_TOPICS['ACTIVE_2'] = 'Versteckt';
$MOD_TOPICS['ACTIVE_3'] = 'Versteckt + Links';
$MOD_TOPICS['ACTIVE_4'] = '&Ouml;ffentlich';
$MOD_TOPICS['ACTIVE_5'] = 'Aktuell';
$MOD_TOPICS['ACTIVE_6'] = 'Top-Aktuell';

$MOD_TOPICS['SHOW_PREVIEWS'] = 'Auswahl zeigen';
$MOD_TOPICS['TOPICLONG'] = '(Langtext hier)';
$MOD_TOPICS['TOPICBLOCK2'] = 'Block 2';

$MOD_TOPICS['PNSA_STRING'] = 'Siehe auch, Vorherige/N&auml;chste';
$MOD_TOPICS['SEE_ALSO_LOOP'] = 'Siehe auch Schleife';
$MOD_TOPICS['PREVNEXT_LOOP'] = 'P/N Schleife';
$MOD_TOPICS['PNSA_MAX'] = 'Gesamtzahl der Links';
$MOD_TOPICS['SINGLETOPIC'] = 'Einzelansicht';

$MOD_TOPICS['ALLDISABLED'] = 'Alles aus';
$MOD_TOPICS['MODERATED'] = 'Moderiert';
$MOD_TOPICS['DELAY'] = 'Verz&ouml;gert freischalten';
$MOD_TOPICS['IMMEDIATELY'] = 'Sofort freischalten';

$MOD_TOPICS['DEFAULT_HP_LINK'] = 'Link zur Website';
$MOD_TOPICS['HP_LINK_DISABLED']  = 'Feld nicht anzeigen';
$MOD_TOPICS['HP_LINK_OFF']  = 'Kein Link';
$MOD_TOPICS['HP_LINK_MASKED'] = 'Mit Javascript maskiert';
$MOD_TOPICS['HP_LINK_NOFOLLOW'] = 'rel=nofollow';
$MOD_TOPICS['HP_LINK_SHOW'] = 'Normaler Link';

$MOD_TOPICS['EMAIL_NONE'] = 'Feld nicht anzeigen';
$MOD_TOPICS['EMAIL_OPTIONAL'] = 'Optional';
$MOD_TOPICS['EMAIL_REQUIRED'] = 'Pflichtfeld';
$MOD_TOPICS['MAXCOMMENTS'] = 'Kommentare / Seite (0=alle)';



$MOD_TOPICS['SORT_COMMENTS'] = 'Sortierung';
$MOD_TOPICS['SORT_COMMENTS_ASC'] = 'Neue zuerst';
$MOD_TOPICS['SORT_COMMENTS_DESC'] = 'Am Ende anh&auml;ngen';


$MOD_TOPICS['SEE_HELP_FILE'] = 'Siehe dazu die Hilfe-Seite';
$MOD_TOPICS['NO_PICTURES_FOUND'] = 'Keine Bilder im Verzeichnis gefunden: ';
$MOD_TOPICS['NO_PICTUREDIR_FOUND'] = 'Das Verzeichnis existiert nicht: ';
$MOD_TOPICS['NO_PICTUREDIR'] = 'Es ist kein Bilderverzeichnis angegeben';
$MOD_TOPICS['NO_PICTUREUSE'] = 'Es werden keine Bilder verwendet';

$MOD_TOPICS['EXTRA'] = 'Extra';
$MOD_TOPICS['CHANGE_URL'] = 'Dateiname &auml;ndern';
$MOD_TOPICS['CHANGE_URL_HINT'] = 'Eine &Auml;nderung kann zu einem Verlust im Suchmaschinen-Ranking f&uuml;hren!';


$MOD_TOPICS['PNSA_LINKS'] = 'Andere Seiten bearbeiten:';
$MOD_TOPICS['SEE_ALSO_CHANGE'] = '"Siehe auch" &auml;ndern';
$MOD_TOPICS['SEE_ALSO'] = 'Siehe auch';
$MOD_TOPICS['NEWTOPIC'] = "Neue Seite anlegen";
$MOD_TOPICS['COPYTOPIC'] = "Speichern und Duplizieren";
$MOD_TOPICS['PICTURE'] = 'Bild';
$MOD_TOPICS['MOVE_TOPIC_TO'] = 'Topic verschieben zu Section';
$MOD_TOPICS['EDITAUTHORS'] = 'Autoren &auml;ndern';
$MOD_TOPICS['AUTHORSHEADLINE'] = 'Weitere Autoren, die dieses Thema bearbeiten d&uuml;rfen';
$MOD_TOPICS['SETAUTHORSHEADLINE'] = 'Anderen Autor zum Inhaber machen:';

$MOD_TOPICS['SAVEFORALL'] = 'Einstellungen auch f&uuml;r alle anderen Abschnitte &uuml;bernehmen (VORSICHT!)';
$MOD_TOPICS['SAVE_FINISH'] = 'Sichern und fertig';

//Commentframe
$MOD_TOPICS['JS_ERROR'] = 'Einige Angaben sind ungueltig:';
$MOD_TOPICS['JS_NAME'] = 'Name';
$MOD_TOPICS['JS_TOO_SHORT'] = 'Kommentar ist zu kurz';
$MOD_TOPICS['JS_VERIFICATION'] = 'Pruefziffer';

// For Votes
$MOD_TOPICS['VOTE_BUTTON_TEXT'] = 'Abstimmen';



?>