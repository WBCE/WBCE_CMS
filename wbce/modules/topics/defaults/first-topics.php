<?php
$t=time();
$firsttopic = "INSERT INTO ".TABLE_PREFIX."mod_".$mod_dir." SET section_id = '".$section_id."', page_id = '".$page_id."', posted_first = '".$t."', posted_by = '1', authors = '1',
content_extra = '', txtr1 = 'http://websitebaker.at/topics/schnelle-einfuehrung.html',
txtr2 = '', txtr3 = '', commenting = '2', active = '1', hascontent = '2', published_when = '', published_until = '', picture = '1.jpg', link = 'welcome', ";

if (LANGUAGE == "DE") {
$firsttopic .= "
title = 'Willkommen bei Topics',
short_description = 'Topics stellt sich kurz vor',

content_short = '<p>Topics ist so &auml;hnlich wie News, ist aber f&uuml;r ganze Artikel in gr&ouml;&szlig;erer Zahl ausgelegt. Dazu bietet Topics entsprechende M&ouml;glichkeiten: Themen-Bilder, dichte interne Verlinkung, (zeitgesteuerte) Archivierung, bessere Kommentarfunktion. </p>

<p>Daf&uuml;r gibt es aber auch mehr Schalterchen und Optionen, diese schauen wir uns hier kurz an.</p>',

content_long = '<p>Nat&uuml;rlich kann Topics auch wie das ganz normale News-Modul verwendet werden. Das Modul ist aber eher daf&uuml;r gedacht, einmal eingerichtet zu werden, damit <em>dann </em>fl&uuml;ssig und unkompliziert neue Artikel eingestellt werden k&ouml;nnen - in einheitlichem Design und Funktion.</p>
<h3>Aktiv - von &quot;unver&ouml;ffentlicht&quot; bis &quot;Top-Aktuell&quot;</h3>

<p>Ein frisch angelegtes Thema ist zuerst auf &quot;nur f&uuml;r angemeldete Besucher&quot; gestellt, wir wollen ja nicht, dass uns jeder beim Schreiben zusieht. Erst nach dem Korrekturlesen sollte die Seite auf &quot;&Ouml;ffentlich&quot; gestellt werden (aber nicht vergessen!). <br />
&quot;Aktuell&quot; und &quot;Top-Aktuell&quot; k&ouml;nnen &uuml;ber class=&quot;aktiv[ACTIVE]&quot; selektiert und hervorgehoben werden. (siehe Optionen).</p>

<h3>Bilder</h3>
<p>Es gibt jeweils 3 gleichnamige Bilder im Bildverzeichnis: <br />
/bild.jpg<br />
/thumbs/bild.jpg<br />
/zoom/bild.jpg<br />
und eventuell: /zoom/orig-bild.jpg: Wird behalten, wenn /zoom/bild.jpg heruntergerechnet wurde.</p>
<p>Der beste Weg ist nat&uuml;rlich, zuerst einige allgemeine Bilder auszuw&auml;hlen, alle diese Bilder mit Photoshop o.&Auml;. zu bearbeiten und hochzuladen. Das ergibt die beste Qualit&auml;t. Sp&auml;ter sucht man sich einfach schnell ein zum Thema passendes Bild aus und erg&auml;nzt gelegentlich die Auswahl.</p>

<p>Man kann aber auch direkt Bilder hochladen, diese werden entsprechend den Einstellungen (Optionen-&gt;Bilder) heruntergerechnet. </p>
<p>Bilder k&ouml;nnen auch nachtr&auml;glich neu berechnet werden. Dazu m&uuml;ssen aber die Dateirechte entsprechend gesetzt sein und bei vielen Bildern kann der Server in die Knie gehen. Au&szlig;erdem m&uuml;ssen dazu zumindest zoom-Bilder vorhanden sein.</p>
<h4>Bildverzeichnis (siehe Optionen)</h4>
<p>Topics speichert nicht die ganze Bild-URL, sondern nur den Namen. Werden Topics verschoben bzw. archiviert, muss das Bildverzeichnis das gleiche sein, sonst bricht der Link.</p>
<p>Deswegen wird das Bildverzeichnis wird auch verwendet, um Topics-Abschnitte (Sektionen, Seiten) zu gruppieren. In der Standardeinstellung k&ouml;nnen Topics nur zwischen Sektionen verschoben werden, die das gleiche Bildverzeichnis haben. Das gilt auch, wenn Bilder gar nicht verwendet werden.</p>

<h4>Weitere M&ouml;glichkeiten:</h4>
<p>In das Feld &quot;Bild-URL&quot; kann auch eine vollst&auml;ndige URL (beginnend mit http://) hineinkopiert werden. In diesem Fall wird dieses Bild f&uuml;r alles Ansichten verwendet.<br />
Hei&szlig;t eines der Extra-Felder &quot;Picture Link&quot; und enth&auml;lt es eine URL, dann wird in {PICTURE} dieser auch gleich angef&uuml;gt.</p>

<h3>SEO</h3>
<p>Es sollte unbedingt das Snippet &quot;Simple Page Head&quot; im Template verwendet werden, sonst werden immer nur Titel, Description und Keywords der &Uuml;bersichtseite ausgegeben.  &quot;Simple Page Head&quot; holt nicht nur bei Topics, sondern auch bei News, Bakery und vielen anderen Modulen individuelle Meta-Daten auf die Seite. Das freut Google.<br />
Die Felder Titel, Meta-Description und Meta-Keywords entsprechen dann diesen Meta-Tags.  Good to know: Beginnen die Felder mit einem Leerzeichen, werden sie beim Speichern automatisch neu erzeugt. (Derzeit gibt es noch einen Bug mit Umlauten - Sorry).<br />

Will man die Inhalte fixieren, l&ouml;scht man einfach das Leerzeichen zu Beginn.</p>
<h3>Weitere Themen / Siehe auch:</h3>
<p>Die vorigen/n&auml;chsten Themen werden entsprechen den Voreinstellungen (siehe Optionen) erzeugt. Mit &quot;siehe auch&quot; k&ouml;nnen weitere Topics ausgew&auml;hlt werden. Dabei achtet ein Algorithmus darauf, dass kein Link doppelt vorkommt.</p>

',
description = 'Topics ist ein WebsiteBaker-Modul, das fuer viele Artikel ausgelegt ist. Im Grunde so aehnlich wie News, aber viel flexibler. ',
keywords = 'websitebaker,modul,topics,artikel,verzeichnis,flexibel';
";


} else {

$firsttopic .= "
title = 'Welcome to Topics',
short_description = 'This is your first Topic',


content_short = '<p>This is the teaser</p>',

content_long = '<p>and this is the long content</p>',
description = '',
keywords = '';
";

}
?>