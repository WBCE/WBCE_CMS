<?php

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

//Default Options
$use_timebased_publishing = 1;
$sort_topics = 1;
$topics_per_page = 0;

$header = '
<!-- Header -->
';
$topics_loop = addslashes('<div class="mod_topic_loop mod_topic_active[ACTIVE] mod_topic_comments[COMMENTSCLASS]">
{THUMB}
<h3 class="mt_title">{TITLE}</h3>
[TOPIC_SHORT] 
[READ_MORE][EDITLINK]<div style="clear:both"></div></div>
');
$footer = addslashes('{PREV_NEXT_PAGES}
');


$topic_header = addslashes('<div class="mod_topic_page">
<h1 class="tp_headline">[TITLE]</h1>
<p class="tp_author">[USER_DISPLAY_NAME] on [PUBL_DATE]</p>
[USER_MODIFIEDINFO]
<div class="tp_picture">{PICTURE}</div><div class="tp_topicshort">[TOPIC_SHORT]</div> 
<div style="clear:both"></div>
');
$topic_footer = addslashes('{SEE_ALSO}
{SEE_PREVNEXT}
<p class="topics-back"><a href="[BACK]">Zur&uuml;ck</a></p>
[EDITLINK]
</div>
<hr/>
');
$topic_block2 = addslashes('<!--see help how to use a second block -->
<div class="topic_block2">
{PICTURE}
[TOPIC_SHORT]
{SEE_ALSO}
{SEE_PREVNEXT}
</div>
');

$pnsa_string = addslashes('<p style="margin-top:5px; clear:left;">
<a href="[LINK]"><img src="[PICTURE_DIR]/thumbs/[PICTURE]" height="30" width="30" border="0" alt="[TITLE]" style="float:left; margin:0 6px 10px 0;" /></a>
<a href="[LINK]"><strong>[TITLE]</strong></a><br/>
<span class="pnsa_link">[SHORT_DESCRIPTION]</span></p>
');
$pnsa_max=4;


$comments_header = addslashes('<h2>Kommentare</h2>
');
$comments_loop = addslashes('<blockquote class="mod_topic_commentbox">
<p class="comment_date">[DATE]</p>
<p class="comment_name">{NAME}</p>
<p class="comment_text">[COMMENT]</p>
</blockquote>
');
$comments_footer = '';

?>