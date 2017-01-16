<?php

$MOD_TOPICS_INFO = array();
//Frontend
$MOD_TOPICS_INFO['SECTIONSETTINGS'] = '<p><strong>Title / Description:</strong></p>
<p>Placeholders (list-page: [SECTION_TITLE] and [SECTION_DESCRIPTION]</p>
<p>Date -&gt; Auto-Archive: After fullfilling the condition, the topics are moved to another section. This section should have the same picture directory.</p>';
  
  
$MOD_TOPICS_INFO['PICTURES'] = '<p>The picture directory ist also used as group detection. Depending on other setting topics only can be moved betzween sections with the same picture directory</p>
<p>Upload: default values for pictures. &quot;0&quot; = no change / keep ratio.
  0/0 at zoom: no downsizing. </p>
<p>Zoom Class: If set, in Placeholders {PICTURE} a link to the zoom-file is generated, with this class (useful for Fancy Box).</p>';

$MOD_TOPICS_INFO['TOPICSPAGE'] = '<p>Overview Page</p>
<p>Typical placeholders: <br>
  {TITLE}, {THUMB}: Contains a link to the singele topic, if this has content.<br> 
[TOPIC_SHORT]: Short text<br>
{PREV_NEXT_PAGES} Links to next pages<br>
(more placeholders: see help)</p>';  
  
$MOD_TOPICS_INFO['TOPIC'] = '<p>Topic (single view)</p>
<p>Typical placeholders: <br>
[TITLE], {PICTURE}, [TOPIC_SHORT], {SEE_ALSO}, 
{SEE_PREVNEXT}<br>
  [EDITLINK]: Link to edit.<br>
[BACK]: Note: only the URL<br>
(more placeholders: see help)
</p>
<p>Block2: The template must be prepared to show a secont Topics block.</p>';
  
  


$MOD_TOPICS_INFO['PNSA_STRING'] = '<p><strong>See also, Previous/Next</strong></p>
<p>These contents are called by {SEE_ALSO} and
{SEE_PREVNEXT}.<br>
Here you can define the form. </p>
<p><strong>Tip:</strong> The same settings are use both in frontend and backend. Instead of external CSS you might use specifications like style=&quot;...&quot; direct within the tags.</p>
<p>Typical placeholders: <br>
{TITLE}, [PICTURE], [SHORT_DESCRIPTION]</p>';


$MOD_TOPICS_INFO['COMMENTS'] = '<p><strong>Comments</strong></p>
<p>Note: Some of the values a only default values</p>';
$MOD_TOPICS_INFO['VARIOUS'] = '<p>Various</p>';

$MOD_TOPICS_INFO['TOPICMASTER'] = '<p>Topic Master</p>';

/* $MOD_TOPICS_INFO['STARTINFO'] = '<p>Wissenswertes:</p>
<ul>
  <li>Topics erzeugt nur Seiten, wenn der Langtext ein Minimum an Inhalt hat.
    Ohne Seiten gibt es nat&uuml;rlich auch keine Links zu diesen, and auch keine
    Access-Files.</li>
  <li>Neue Topics sind Anfangs auf sichtbar f&uuml;r &quot;Angemeldete Besucher&quot; gestellt.
    Zum Ver&ouml;ffentlichen auf &quot;&Ouml;ffentlich&quot; &auml;ndern - sonst sind sie eben nicht sichtbar.</li>
</ul>'; */




?>