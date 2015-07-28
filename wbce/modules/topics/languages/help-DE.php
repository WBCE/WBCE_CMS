<?php

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2007, Ryan Djurovich

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

// Help file in German
?>
<p><strong>Topics</strong> basiert auf dem &quot;News&quot;-Modul, wurde aber darauf ausgelegt, eine gr&ouml;&szlig;ere
  Anzahl von Artikeln, Tutorials oder Stories darzustellen. Speziell die interne
  Verlinkung, Suchmaschinen-Optimierung, Kommentarfunktion, Umgang mit Bildern
  und zahlreiche weitere Optionen wurden verbessert bzw. eingebunden.<br>
  Die vom &quot;News&quot;-Modul gewohnte Aufteilung in Gruppen entf&auml;llt; diese Funktion
erf&uuml;llt die Seite (genauer: die Section)</p>
<p>Topics kann sehr individuell eingerichtet werden, deswegen gibt es sehr viel
  Platzhalter, Klassen und Einstellungsm&ouml;glichkeiten. Wer davon &uuml;berfordert
  ist (was schnell mal vorkommt..), kann nat&uuml;rlich
  einfach die Grundeinstellungen belassen oder die Presets ausprobieren.</p>
<p>Weiter Hilfe gibt es auf <strong><a href="http://websitebaker.at/wb/module/topics.html" target="_blank">websitebaker.at</a></strong></p>
<p><a href="#seiten">Seiteneinstellungen</a><br>
  <a href="#pictures">Bilder</a><br>
  <a href="#loop">&Uuml;bersicht-Seite</a><br>
  <a href="#topic">Thema (Einzelansicht)</a><br>
  <a href="#pnsa">Siehe auch, Vorige/N&auml;chste</a><br>
  <a href="#comments">Kommentare</a><br>
  <a href="#various">Weiteres</a><br>
  <a href="#master">Topics-Master</a><br>
  <a href="#advanced">Erweiterte Hilfe f&uuml;r Admins</a><br>
  <a href="#placeholders">Platzhalter-&Uuml;bersicht
</a></p>
<hr>
<h2><a name="seiten" id="seiten"></a>Seiteneinstellung</h2>
<p>Normalerweise gibt man &quot;Beschreibung&quot; mit dem Platzhalter [SECTION_DESCRIPTION]
  nur auf der &Uuml;bersichtseite aus. <br>
  Beschreibung kann HTML - oder eine Zahl (section_id)
  enthalten: dann wird dieser Abschnitt eingebunden. (Hinweis: Das wird nicht
  mit allen
  Modulen funktionieren - also ausprobieren)</p>
<p><strong>Datum -&gt;Auro-Archiv:</strong> Mit diesen Einstellungen k&ouml;nnen
  Topics automatisch auf andere Abschnitte verschoben werden. Wichtig: die Topics
  werden tats&auml;chlich
  verschoben,
  ein &quot;Undo&quot;
  ist nur durch manuelles Zur&uuml;ck-verschieben (jedes Topic einzeln) m&ouml;glich. </p>
<hr>
<h2><a name="pictures" id="pictures"></a>Bilder</h2>
<p><strong>Bildverzeichnis</strong>: Vorgabe: /media/topics-pictures<br>
  Normalerweise konnen Topics nur zwischen Abschnitten verschoben
  bzw archiviert werden, die das gleiche Bildverzeichnis haben. <br>
  Ausnahme:  <a href="#advanced">besondere
    Einstellungen</a></p>
<p><strong>Upload/Handling:</strong> &quot;0&quot; bedeutet: keine &Auml;nderung bzw Proportionen beibehalten. <br>
  Zu beachten: F&uuml;r jede Section gelten eigene Werte. Bei mehreren Abschnitten
  (bzw Seiten) sollten diese abgeglichen werden. Die Einstellungen gelten nur
  f&uuml;r
  den Upload; eine nachtr&auml;gliche &Auml;nderung der Einstellungen hat keine
  direkten Auswirkung auf vorhandene Bilder. Allerdings kann beim Upload angew&auml;hlt
   werden, dass alle vorhandenen Bilder in einem Bildverzeichnisse neu berechnet
  werden sollen (beta und rechenintensiv!).</p>
<p>Platzhalter f&uuml;r Bilder:<br>
  {PICTURE} gibt das Topic-Bild mit &lt;img&gt;-Tag, eventuell auch Link und n&ouml;tigen
    Klassen aus.<br>
    [PICTURE] (Klammern beachten!) gibt nur den Dateinamen aus. &lt;a&gt;, &lt;img&gt; muss
angegeben werden.</p>
<p>{THUMB}<br>
  entspricht also ungef&auml;hr:<br>&lt;a href=&quot;[LINK]&quot;&gt;&lt;img
class=&quot;tp_thumb tp_thumb[TOPIC_ID]&quot; src=&quot;[PICTURE_DIR]/thumbs/[PICTURE]&quot; alt=&quot;&quot; /&gt;&lt;/a&gt;</p>
<p>Der Unterschied ist, dass {THUMB} keinen Link enth&auml;lt, wenn es keinen Link
  gibt, die
  &quot;lange Variante&quot; dann trotzdem einen Link erzeugt.<br>
  Daher wird man meist {THUMB} und {PICTURE} verwenden und nur unter besondernen
Umst&auml;nden die volle HTML-Version.</p>
<p>[PICTURE] kann eventuell auch eine volle URL (beginnend mit http://) enthalten.
Dann funktioniert {PICTURE} bzw{THUMB} immer noch.</p>
<p>Ist eine Zoom Class angegeben, wird ein Link auf da Zoom-Bild gesetzt.<br>
  Wenn eines der 3 Extra-Felder ([XTRA1-3]) den Namen &quot;Picture Link&quot; hat und
  mit http beginnt, wird dieser Link bei {PICTURE} verwendet.<br>
</p>
<hr>
<h2><a name="loop" id="loop"></a>&Uuml;bersicht-Seite</h2>
<p>Das ist die Seite, auf der die Topics gelistet werden. Hier gibt es sehr viele
m&ouml;gliche Platzhalter:</p>
<p>  Allgemein: 
    [TOPIC_ID] [TITLE] {TITLE} [SHORT_DESCRIPTION] [TOPIC_SHORT] [LINK] [READ_MORE]
  [COMMENTSCOUNT] [EDITLINK]</p>
<p>  Datum und Zeit: 
  [MODI_DATE]
  [MODI_TIME] [PUBL_DATE], [PUBL_TIME] <br>
  [EVENT_START_DATE] [EVENT_STOP_DATE] [EVENT_START_DAY] [EVENT_START_MONTH]
  [EVENT_START_YEAR] [EVENT_START_DAYNAME] [EVENT_START_MONTHNAME] [EVENT_START_TIME]
  [EVENT_STOP_DAY] [EVENT_STOP_MONTH] [EVENT_STOP_YEAR] [EVENT_STOP_DAYNAME]
  [EVENT_STOP_MONTHNAME] [EVENT_STOP_TIME] </p>
<p>  Zur Verwendung in Klassen: [ACTIVE]  [COUNTER] [COUNTER2] [COUNTER3]  [COMMENTSCLASS]<br>
  Bilder: [PICTURE_DIR] [PICTURE]
  {PICTURE} {THUMB} siehe oben.</p>
<p>    Diverse Felder: 
    [XTRA1] [XTRA2]
    [XTRA3] </p>
<p>  User: [USER_ID] [USER_NAME] [USER_DISPLAY_NAME] [USER_EMAIL] [USER_MODIFIEDINFO]</p>
<p>  Header/Footer:
      [SECTION_TITLE] [SECTION_DESCRIPTION] [PICTURE_DIR] {PREV_NEXT_PAGES} [PREVIOUS_LINK]
        [NEXT_LINK] [TOTALNUM] {JUMP_LINKS_LIST}</p>
<p>Nicht jammern - das sind alles Userw&uuml;nsche ;-)</p>
<p>Zeit und Datumsplatzhalter sind aus Kompatibilit&auml;tsgr&uuml;nden tw identisch.<br>
  Wer viele User hat, sollte die entsprechend Platzhalter (beginnend mit &quot;[USER_&quot;
    ) vermeiden; sie bremsen stark.</p>
<p>Besondere Platzhalter: <br>
[CONTENT_EXTRA]: Das Extra-Feld (WYSIWYG)<br>
[CONTENT_LONG]: Inhalt des Lang-Textes<br>
[CONTENT_LONG_FIRST]: Gibt nur im ersten Eintrag den Lang-Text aus</p>
<hr>
<h2><a name="topic" id="topic"></a>Thema (Einzelansicht)</h2>
<p>Praktisch alle Platzhalter, die in der &Uuml;bersichtseite m&ouml;glich
  sind und die  Sinn machen, gibt es auch in der Einzelansicht. </p>
<p>Weiters: [SECTION_ID]
  [SHORT_DESCRIPTION] [TOPIC_SHORT] [TOPIC_EXTRA] [META_DESCRIPTION] [META_KEYWORDS]
  {SEE_ALSO} {SEE_PREVNEXT}
  [BACK] [TOPIC_SCORE] [EDITLINK]</p>
<p>Besondere Platzhalter:<br>
{FULL_TOPICS_LIST} gibt eine Art Topics-Men&uuml;-Liste aus.<br>
[ALLCOMMENTSLIST] die Kommentare. Wenn der Platzhalter NICHT verwendet wird,
werden die Kommtare am Ende ausgegeben - sonst an der Stelle, wo der Platzhalter
ist.<br>
[COMMENTFRAME] analog zu [ALLCOMMENTSLIST]</p>
<p>Hinweis: Die Platzhalter werden in <strong>view.final.php</strong> gesetzt. Diese Datei kann
  als view.final.<strong>custom</strong>.php kopiert werden, welche dann stattdessen verwendet
  wird. <br>
  view.final.custom.php wird bei einem Upgrade nicht &uuml;berschrieben. Dadurch ist
  es m&ouml;glich, ohne Probleme direkt ins php einzugreifen und die Ausgabe tiefgreifend
  zu &auml;ndern.</p>
<h3>Verwendung eines 2. Blocks:</h3>
<p>In view.final.php wird die Konstante TOPIC_BLOCK2 definiert. Diese enth&auml;lt
  den Inhalt von Thema &gt; Block 2<br>
  Verwendung im Template (Beispiel):<br>
  if(defined('TOPIC_BLOCK2') AND TOPIC_BLOCK2 != '') { echo TOPIC_BLOCK2; } else
{ page_content(2); }</p>
<p>Wichtig dabei: Block 2 muss im Template <em>nach</em> der Topics-view.php
  aufgerufen werden, weil sonst TOPIC_BLOCK2 noch nicht definiert ist. Wenn das
  nicht so ist, muss Block1 vorher gepuffert (ob_start()) werden.</p>
<hr>
<h2><a name="pnsa" id="pnsa"></a>Siehe auch, Vorige/N&auml;chste:</h2>
<p>Eine Besonderheit ist der Block &quot;Siehe auch&quot; und &quot;Neuere/&Auml;ltere
  Themen&quot;. Ausgabe in der Einzelansicht mit {SEE_ALSO} und {SEE_PREVNEXT}</p>
<p>Unter
  Siehe auch k&ouml;nnen verwandte/&auml;hnliche Topics gew&auml;hlt werden. Dabei gilt:</p>
<ul>
  <li>F&uuml;r &quot;Siehe auch&quot; k&ouml;nnen  Links auch zu anderen Sections
    gesetzt werden. </li>
  <li>&quot;Neuere/&Auml;ltere Themen&quot; sind immer innerhalb der Section, sie
    sind abh&auml;ngig von der gew&auml;hlten Sortierung.</li>
  <li>Es werden nur Seiten gelistet, die Inhalt ([LONG]) haben.</li>
</ul>
<p>Platzhalter:<br>
[TOPIC_ID] {TITLE} [TITLE] [LINK] [SHORT_DESCRIPTION] [PICTURE_DIR] [PICTURE]<br>
Da hier nur selten Thumbs verwendet werden, sind die Platzhalter auf das N&ouml;tigste
reduziert.</p>
<p>Hinweis: Diese Einstellungen werden sowohl im Frontend als auch im Backend
  benutzt, wo aber verschiedene Stylesheets gelten. Deswegen kann es besser sein,
  Styles
  direkt in die Tags zu schreiben, zB: &lt;a
  style=&quot;display:block; ....&gt;</p>
<hr>
<h2><a name="comments" id="comments"></a>Kommentare</h2>
<p>Einige Einstellungen sind nur &quot;Voreinstellungen&quot;, die an anderer Stelle individuell
  vergeben werden k&ouml;nnen, etwa:<br>
  &quot;Link zur Website&quot;: Die Art des Links kann bei jedem Kommentar einzeln ge&auml;ndert
werden.</p>
<p>Platzhalter:<br>
{NAME} [NAME] [EMAIL] [WEBSITE] [COMMENT] [DATE] [TIME] [USER_ID]<br>
Wie &uuml;blich: {NAME} enth&auml;lt einen vollen Tag - ggf mit Link, [NAME]
enth&auml;lt nur
den Feldinhalt.<br>
[EMAIL] sollte nie verwendet werden.</p>
<p>Die Kommentarfunktion ist noch verbesserungsw&uuml;rdig, sie reicht aber in den
  allermeisten F&auml;llen aus.</p>
<hr>
<h2><a name="various" id="various"></a>Weiteres</h2>
<p>Presets sind kleine Javascript-Dateien, die die Feldinhalte &auml;ndern - so als
  ob man sie selbst hineingeschrieben hat. Erst beim Speichern werden die Werte
  &uuml;bernommen. Sie befinden sich im Verzeichnis /modules/topics/presets-en/</p>
<p>Presets sind &quot;transportierbare Einstellungen&quot; - sie k&ouml;nnen gemailt, im Forum
  gepostet oder nat&uuml;rlich wieder in presets-en gespeichert werden - wo sie ab
  dann zu Auswahl stehen.</p>
<p>Ein besonders f&uuml;r ein bestimmtes Template gemachtes Preset kann im Template-Verzeichnis
  (TEMPLATE_DIR) als <strong>topics-preset.js</strong> gespeichert werden.</p>
<hr>

<h2><a name="master" id="master"></a>Topics-Master (experimentell)</h2>
<p>Ein &quot;Topics-Master&quot; ist eine Topics-Section, die keine eigenen Topics enth&auml;lt,
  sondern welche von anderen Abschnitten &uuml;bernimmt und auflistet. Dabei - und
  das ist der Clou - k&ouml;nnen andere Einstellungen verwendet werden. Ein Topic-Master
  kann also zb auf der Startseite verwendet werden, um die neuesten 5 Topics
  von anderen Abschnitten als Anrei&szlig;er darzustellen.</p>
  <hr>
<p>&nbsp;</p>
<h2><a name="advanced" id="advanced"></a>Erweiterte Hilfe f&uuml;r Admins:</h2>
<p>Neben den WB-typischen Optionen gibt es die Datei module_settings.php im Verzeichnis
  modules/topics/, mit der weitere Einstellungen  gemacht werden k&ouml;nnen. <br>
Um die Datei module_settings.php zu &auml;ndern, sind nur sehr geringe php-Kenntnisse
n&ouml;tig, vieles davon kann sp&auml;ter auch in den Optionen ver&auml;ndert
werden.</p>
<p>Wichtig: Bei der erstmaligen Installation wird die Datei: /defaults/module_settings.default.php
  als module_settings.php kopiert. Es werden immer beide Dateien geladen. Sinn
  der Sache: module_settings.php wird bei Updates <strong>nicht</strong> &uuml;berschrieben, so dass
  individuelle EInstellungen erhalten bleiben.<br>
  Es ist auch m&ouml;glich (und sinnvoll), module_settings.php zu leeren (nicht:
  l&ouml;schen!) und nur die
ge&auml;nderten Einstellungen darin zu belassen.</p>
<p>Ebenso nicht &uuml;berschrieben werden frontend.css und frontend.js.<br>
  Sollte module_settings.php und obige Dateien nicht vorhanden sein, gab es ein
  Problem mit den
Datei-Rechten.</p>
<p>Die meisten Variablen sind selbsterkl&auml;rend. Sie gelten global f&uuml;r alle Topics-Sections.
Sollten einzelne Sections andere Vorgaben haben, hilft folgendes (zB):<br>
$usepicturechooser = 1;<br>
if ($section_id==24) {$usepicturechooser = 2;}
<br>
</p>
<h3>Autoren (beta):</h3>
<p>Es gibt die M&ouml;glichkeit, eine Benutzergruppe als &quot;authorsgroup&quot; zu definieren.
  Diese User haben dann eingeschr&auml;nkte Rechte; sie d&uuml;rfen etwa nur ihre eigenen
  Topics &auml;ndern.<br>
  Autoren sollten in keiner anderen Gruppe sein.</p>
<hr>
<h2><a name="placeholders" id="placeholders"></a>Platzhalter-&Uuml;bersicht</h2>
<p>  Gut zu wissen: <br>
  H&auml;ufig gibt es sowohl [FELD] als auch {FELD}<br>
  Der Unterschied: Bei [FELD] wird nur der Inhalt ausgegeben, bei {FELD} ein
  vollst&auml;ndiger Tag: &lt;div class="topic-feld"&gt;FELD&lt;/div&gt;<br>
  Wenn ein Feld nicht zwangsl&auml;ufig etwas enth&auml;lt, ist die Variante
  {FELD} besser, weil ein leeres Feld dann auch keinen leeren Tag bzw leeren
Link erzeugt.</p>
<p>Gr&uuml;n = &Auml;nderung</p>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr>
    <td width="14%">Platzhalter</td>
    <td width="86%">Ausgabe</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><h4>In allen Ausgabefeldern:</h4></td>
  </tr>
  <tr>
    <td>[TOPIC_ID]</td>
    <td>Eindeutige Nummer</td>
  </tr>
  <tr>
    <td>[TITLE]</td>
    <td>Titel</td>
  </tr>
  <tr>
    <td>[LINK]</td>
    <td>Die URL der Topic-Seite</td>
  </tr>
  <tr>
    <td>[SHORT_DESCRIPTION]</td>
    <td>Eine kurze Beschreibung, nicht zu verwechseln mit [TOPIC_SHORT] oder
      [META_DESCRIPTION]</td>
  </tr>
  <tr>
    <td>[PICTURE_DIR]</td>
    <td>Ein gew&auml;hltes Bild-Verzeichnis. Dieses kann in den Options f&uuml;r jede Section
      getrennt eingestellt werden.<br>
      zb &quot;http:/meinedomain.de/media/meinetopicsbilder&quot;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><h4>&Uuml;bersicht: Header/Footer:</h4></td>
  </tr>
  <tr>
    <td>[SECTION_TITLE]</td>
    <td>Entspricht dem Gruppennamen</td>
  </tr>
  <tr>
    <td>[SECTION_DESCRIPTION]</td>
    <td>Entspricht der Gruppenbeschreibung</td>
  </tr>
  <tr>
    <td>{PREV_NEXT_PAGES}</td>
    <td>Der komplette Footer. Leer, wenn keine Links anzuzeigen sind.</td>
  </tr>
  <tr>
    <td>[PREVIOUS_LINK]</td>
    <td>Wie im News-Modul</td>
  </tr>
  <tr>
    <td>[NEXT_LINK]</td>
    <td>Wie im News-Modul</td>
  </tr>
  <tr>
    <td>[TOTALNUM]</td>
    <td>Wie im News-Modul</td>
  </tr>
  <tr>
    <td>{JUMP_LINKS_LIST}</td>
    <td>Ein Liste mit Sprunglinks href=&quot;#jumptid'.$t_id.'&quot;&gt;</td>
  </tr>
  <tr>
    <td>{JUMP_LINKS_LIST_PLUS}</td>
    <td>Wie oben aber inklusive Short_description</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><h4>Topic &Uuml;bersicht (Schleife) UND Einzelansicht</h4></td>
  </tr>
  <tr>
    <td>[TOPIC_SHORT]</td>
    <td>Der Anrei&szlig;er</td>
  </tr>
  <tr>
    <td>[PICTURE]</td>
    <td>der Dateiname des gew&auml;hlten Bildes, zb &quot;dasbild.jpg&quot;</td>
  </tr>
  <tr>
    <td>{PICTURE}</td>
    <td>Ein vollst&auml;ndiger &lt;img&gt; Tag</td>
  </tr>
  <tr>
    <td>[ACTIVE]</td>
    <td>Zahl. Normalerweise &gt; 3, sonst ist der Artikel nicht sichtbar, Maximal
      6</td>
  </tr>
  <tr>
    <td>[MODI_DATE]</td>
    <td>Datum der letzen Modifikation</td>
  </tr>
  <tr>
    <td>[MODI_TIME]</td>
    <td>Zeit der letzen Modifikation</td>
  </tr>
  <tr>
    <td>[PUBL_DATE]</td>
    <td>Datum der Ver&ouml;ffentlichung. Kann edititert werden</td>
  </tr>
  <tr>
    <td>[PUBL_TIME]</td>
    <td>Zeit der Ver&ouml;ffentlichung.</td>
  </tr>
  <tr>
    <td>[USER_ID]</td>
    <td>ID (Nummer) der Autors, der zuletzt bearbeitet hat.</td>
  </tr>
  <tr>
    <td><s><font color="#808080">[USERNAME]</font></s><br>
      <font color="#008000">[USER_NAME]</font></td>
    <td>Username</td>
  </tr>
  <tr>
    <td><s><font color="#808080">[DISPLAY_NAME]<br>
    </font></s><font color="#008000">[USER_DISPLAY_NAME]</font></td>
    <td>Vollst&auml;ndiger Name</td>
  </tr>
  <tr>
    <td><s><font color="#808080">[EMAIL]<br>
    </font></s><font color="#008000">[USER_EMAIL]</font></td>
    <td>eMail des Autors (mit Bedacht zu nutzen!)</td>
  </tr>
  <tr>
    <td><s><font color="#808080">    </font></s>[USER_MODIFIEDINFO]</td>
    <td>Zuletzt ver&auml;ndert von User am Tag um Zeit: Wird nur ausgegeben,
      wenn der urspr&uuml;ngliche User und der, der den Eintrag ver&auml;ndert
      hat verschieden sind UND wenn er nach dem Publishing Date modifiziert wurde.</td>
  </tr>
  <tr>
    <td>[XTRA1]<br>
    [XTRA2]<br>
    [XTRA3]</td>
    <td>3 frei definierbare Felder, siehe module_settings.php<br>
      Bei bestimmten Namen k&ouml;nnen diese Felder auch bestimmte Funktionen haben.
        zb: &quot;Picture Link&quot; erzeugt bei der Einzelansicht einen zus&auml;tzlichen Link
        um {PICTURE}. Diese Extras werden bei Bedarf erweitert.</td>
  </tr>
  <tr>
    <td>[EVENT_START_DATE]<br>
      [EVENT_STOP_DATE]<br>
      [EVENT_START_DAY]<br>
      [EVENT_START_MONTH]<br>
      [EVENT_START_YEAR]<br>
      [EVENT_START_DAYNAME]<br>
      [EVENT_START_MONTHNAME]<br>
    [EVENT_START_TIME]<br>
    [EVENT_STOP_TIME]</td>
    <td>Alle Feler die mit [EVENT_] beginnen, k&ouml;nnen f&uuml;r die Ausgabe von Start-
      und Stop Zeit/Datum verwendet werden. Sie sind zum Teil identisch mit [PUBL_DATE]
      und [PUBL_TIME]</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><h4>Nur &Uuml;bersicht (Schleife):</h4></td>
  </tr>
  <tr>
    <td>{TITLE}</td>
    <td>[TITLE] + [LINK]. Enth&auml;lt nur dann einen Link, wenn [LONG] ausreichend
      Inhalt hat.</td>
  </tr>
  <tr>
    <td>[READ_MORE]</td>
    <td>&lt;div class=&quot;topics-readmore&quot;&gt;... &quot;Weiterlesen-Link&quot;.
      Ein vollst&auml;ndiger Tag. Leer, wenn die Seite keinen weiteren Inhalt
    hat.</td>
  </tr>
  <tr>
    <td>[COMMENTSCOUNT]</td>
    <td>Zahl, Anzahl der Kommentare</td>
  </tr>
  <tr>
    <td>[COMMENTSCLASS]</td>
    <td>0 - 4; Gr&ouml;&szlig;enordnung der Anzahl der Kommentare. Gut f&uuml;r css-Klassen oder
      Symbole zu gebrauchen</td>
  </tr>
  <tr>
    <td>[COUNTER]</td>
    <td>1, 2, 3, 4 ,5 usw Laufende Nummer.</td>
  </tr>
  <tr>
    <td>[COUNTER2]</td>
    <td>0,1,0,1 Abwechselnd</td>
  </tr>
  <tr>
    <td>[COUNTER3]</td>
    <td>0,1,2,0,1,2 </td>
  </tr>
  <tr>
    <td>[CONTENT_LONG]</td>
    <td>Der lange Inhalt (Feld: Long)</td>
  </tr>
  <tr>
    <td>[CONTENT_LONG_FIRST]</td>
    <td>Wie [CONTENT_LONG], wird aber NUR beim ersten Eintrag dargestellt.</td>
  </tr>
  <tr>
    <td>[CONTENT_EXTRA]</td>
    <td>Der Extra-Inhalt (Feld: Extra)</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><h4>Nur Einzelansicht</h4></td>
  </tr>
  <tr>
    <td>[SECTION_TITLE]</td>
    <td>Titel der Section</td>
  </tr>
  <tr>
    <td>[SECTION_DESCRIPTION]</td>
    <td>Beschreibung der Section</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>[TOPIC_SHORT]</td>
    <td>Anrei&szlig;er in der &Uuml;bersicht</td>
  </tr>
  <tr>
    <td>[TOPIC_EXTRA]</td>
    <td>Ein 3. WYSIWYG Editor ist &uuml;ber module_settings.php zuschaltbar.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>[EDITLINK]</td>
    <td>Link zum direkten Bearbeiten der Seite. Nur f&uuml;r Administratoren (Gruppe
      1) sichtbar.</td>
  </tr>
  <tr>
    <td>[BACK]</td>
    <td>Enth&auml;lt NUR den Link</td>
  </tr>
  <tr>
    <td>[META_DESCRIPTION]</td>
    <td>Um die Metadescription im Template einzubinden, ist Simplepagehead n&ouml;tig.</td>
  </tr>
  <tr>
    <td>[META_KEYWORDS]</td>
    <td>ebenso</td>
  </tr>
  <tr>
    <td>[ALLCOMMENTSLIST]</td>
    <td>Alle Kommentare. Wird der Platzhalter nicht angegeben, werden die Kommentare
      wie bisher am Seitenende gezeigt.</td>
  </tr>
  <tr>
    <td>[COMMENFRAME]</td>
    <td>Die iFrame zum Kommentieren.  Wird der Platzhalter nicht angegeben, wird
      er wie in den vorigen Versionen gehandhabt.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Kommentare:</strong></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>[USER_ID]</td>
    <td>Die ID des Users, der kommentiert hat. Normalerweise 0, nur &gt; 0 , wenn
      er gerade angemeldet war.</td>
  </tr>
  <tr>
    <td>{NAME}</td>
    <td>[NAME] + [WEBSITE], wenn eine angegeben ist.</td>
  </tr>
  <tr>
    <td><p>[NAME]<br>
      [EMAIL]<br>
      [WEBSITE]<br>
      [COMMENT]<br>
      [DATE]<br>
      [TIME]</p>
    </td>
    <td>Die Feldinhalte &quot;as is&quot;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
</table>
