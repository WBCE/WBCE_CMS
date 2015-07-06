<?php

$MOD_TOPICS_INFO = array();
//Frontend
$MOD_TOPICS_INFO['SECTIONSETTINGS'] = '<p><strong>Titel / Beschreibung:</strong></p>
<p>Der <strong>Titel</strong> sollte kurz und aussagekr&auml;ftig sein. Er wird in Auswahllisten verwendet,
  kann aber auch mit [SECTION_TITLE] ausgegeben werden.</p>
<p><strong>Beschreibung</strong> kann auch HTML enthalten. Optional kann eine section_id (meist
  von einem WYSIWYG-Abschnitt) angegeben werden. Ausgabe mit [SECTION_DESCRIPTION]</p>
<p><strong>Datum</strong> -&gt; Auto-Archiv: Nach Ende der Bedingung werden diese Topics auf eine andere Seite verschoben. Diese sollte das gleiche Bilderverzeichnis haben.</p>';
  
  
$MOD_TOPICS_INFO['PICTURES'] = '<p>Das Bilderverzeichnis wird auch als Gruppenkennung verwendet. Abh&auml;ngig von weiteren Einstellungen k&ouml;nnen zb Topics nur zwischen Seiten mit gleichem Bilderverzeichnis
  verschoben werden.</p>
<p>Upload: Gr&ouml;&szlig;envorgaben f&uuml;r Bilder. &quot;0&quot; = Keine &Auml;nderung bzw nicht beschneiden.
  0/0 bei Zoom: nicht herunterrechnen. </p>
<p>Zoom Class: Wenn angegeben, wird im Platzhalter {PICTURE} automatisch ein Link auf das Zoom-Bild erzeugt, mit dieser Klasse.</p>';

$MOD_TOPICS_INFO['TOPICSPAGE'] = '<p><strong>&Uuml;bersicht-Seite</strong></p>
<p>Typische Platzhalter: </p>
<p>[SECTION_TITLE], [SECTION_DESCRIPTION] siehe &quot;Seiteneinstellungen&quot;</p>
<p>{TITLE}, {THUMB}: Enthalten einen Link zum Thema (Einzelansicht), wenn diese Inhalt hat.<br />
([TITLE], [THUMB] enthalten nur den Feldinhalt und m&uuml;ssen mit &lt;a href=&quot;[LINK]&quot;&gt; erg&auml;nzt werden.</p>
<p>[TOPIC_SHORT]: Kurztext<br/>  {PREV_NEXT_PAGES} ggf. Links zum Bl&auml;ttern</p>
<p>(weitere Platzhalter: siehe Hilfe)</p>';  
  
$MOD_TOPICS_INFO['TOPIC'] = '<p>Thema (Einzelansicht)</p>
<p>Typische Platzhalter: <br />
[TITLE], {PICTURE}, [TOPIC_SHORT], {SEE_ALSO}, 
{SEE_PREVNEXT}<br />
  [EDITLINK]: Vollst&auml;ndiger Link zum Bearbeiten.<br />
[BACK]: Enth&auml;lt nur die URL<br />
(weitere Platzhalter: siehe Hilfe)
</p>
<p>Block2: Das Template muss vorbereitet sein, diesen Inhalt in einem weiteren
  Block darzustellen.</p>';
  
  


$MOD_TOPICS_INFO['PNSA_STRING'] = '<p><strong>Siehe auch, Vorherige/N&auml;chste</strong></p>
<p>Diese Links werden &uuml;ber die Platzhalter {SEE_ALSO} und
{SEE_PREVNEXT} erzeugt.<br />
Hier kann deren genaue Form eingestellt werden. </p>
<p><strong>Tipp:</strong> Die selben Einstellungen werden im Backend angewandt. Statt ausgelagertem
  CSS sollte daher bei Bildgr&ouml;&szlig;en usw. style=&quot;...&quot; direkt in den Tags verwendet
  werden.</p>
<p>Typische Platzhalter: <br />
{TITLE}, [PICTURE], [SHORT_DESCRIPTION]</p>';


$MOD_TOPICS_INFO['COMMENTS'] = '<p>Kommentare</p>';
$MOD_TOPICS_INFO['VARIOUS'] = '<p><strong>Verschiedenes</strong></p>
<p>H&ouml;he der Editoren: Wird 0 angegeben, erfolgt auch keine Ausgabe!</p>
<p>Presets-File: Holt die zuletzt gespeicherten Einstellungen aus der Datenbank.  Der Quelltext kann als neues Preset gespeichert werden.</p>';

$MOD_TOPICS_INFO['TOPICMASTER'] = '<p>Topic Master</p>';

/*$MOD_TOPICS_INFO['STARTINFO'] = '<p>Wissenswertes:</p>
<ul>
  <li>Topics erzeugt nur Seiten, wenn der Langtext ein Minimum an Inhalt hat.
    Ohne Seiten gibt es nat&uuml;rlich auch keine Links zu diesen, und auch keine
    Access-Files.</li>
  <li>Neue Topics sind Anfangs auf sichtbar f&uuml;r &quot;Angemeldete Besucher&quot; gestellt.
    Zum Ver&ouml;ffentlichen auf &quot;&Ouml;ffentlich&quot; &auml;ndern - sonst sind sie eben nicht sichtbar.</li>
</ul>';
*/



?>