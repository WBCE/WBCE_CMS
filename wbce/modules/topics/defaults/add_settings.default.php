<?php

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

//Default Options
$use_timebased_publishing = 1;
$sort_topics = 1;
$topics_per_page = 0;

$header = '
<!-- Header -->
<h2>[SECTION_TITLE]</h2>
';
$topics_loop = addslashes('<div class="[CLASSES]">
{THUMB}
<h3 class="mt_title">{TITLE}</h3>
[TOPIC_SHORT]
[READ_MORE][EDITLINK]<div style="clear:both"></div>
</div>
');
$footer = addslashes('{PREV_NEXT_PAGES}
');


$topic_header = addslashes('<div class="[CLASSES]">
<h1 class="tp_headline">[TITLE]</h1>
<p class="tp_author">[USER_DISPLAY_NAME] on [PUBL_DATE]</p>
[USER_MODIFIEDINFO]
<div class="tp_teaser hideOnDesktops">{PICTURE}[TOPIC_SHORT]</div>
<div style="clear:both"></div>
[ADDITIONAL_PICTURES]
');
$topic_footer = addslashes('<div class="hideOnDesktops showOnTablets">
{SEE_ALSO}{SEE_PREVNEXT}
</div>
<p class="topics-back"><a href="[BACK]">Back</a></p>
[EDITLINK]
</div>
');
$topic_block2 = addslashes('<!--see help how to use a second block -->
<div class="sidebar topic_block2 desktop-teaser showOnMobiles">
{PICTURE}
[TOPIC_SHORT]
{SEE_ALSO}{SEE_PREVNEXT}
</div>
');

$pnsa_string_raw = addslashes('<a class="pnsa_block" [HREF]>[THUMB]
<strong>[TITLE]</strong><br />
[SHORT_DESCRIPTION]
<span class="pnsaclear"></span>
</a>
');
$see_also_link_title = '<h4>'.$MOD_TOPICS['SEE_ALSO_FRONTEND'].'</h4>';
$next_link_title = '<h4>'.$MOD_TOPICS['SEE_NEXT_POST'].'</h4>';
$previous_link_title = '<h4>'.$MOD_TOPICS['SEE_PREV_POST'].'</h4>';
$setting_additionalpics_string = '{THUMB}';
$pnsa_string = $see_also_link_title.$serializedelimiter.$next_link_title.$serializedelimiter.$previous_link_title.$serializedelimiter.$pnsa_string_raw.$serializedelimiter.$pnsa_string_raw.$serializedelimiter.$setting_additionalpics_string;
$pnsa_max=4;


$comments_header = addslashes('<h2>Comments</h2>
');
$comments_loop = addslashes('<blockquote class="mod_topic_commentbox">
<p class="comment_date">[DATE]</p>
<p class="comment_name">{NAME}</p>
<p class="comment_text">[COMMENT]</p>
</blockquote>
');
$comments_footer = '';

?>
