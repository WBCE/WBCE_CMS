<?php

$header = '<div class="mod_nwi_grid_wrapper mod_nwi_grid_2columns">';
$post_loop = '<section class="mod_nwi_grid_box">
    <div class="mod_nwi_teaserpic mod_nwi_grid_box">
        <a href="[LINK]">[IMAGE]</a>
    </div>
    <div class="mod_nwi_teasertext mod_nwi_grid_box">
        <a href="[LINK]"><h3>[TITLE]</h3></a>
        <div class="mod_nwi_metadata">[TEXT_POSTED_BY] [DISPLAY_NAME] [TEXT_ON] [PUBLISHED_DATE] [TEXT_AT] [PUBLISHED_TIME] [TEXT_O_CLOCK]</div>
        <div class="mod_nwi_shorttext">
            [SHORT]
        </div>
        <div class="mod_nwi_bottom">
            <div class="mod_nwi_tags">[TAGS]</div>
            <div class="mod_nwi_readmore" style="visibility:[SHOW_READ_MORE];">
                <a href="[LINK]">[TEXT_READ_MORE]</a>
            </div>
        </div>
    </div>
</section>';
$footer = '</div>
<div class="mod_nwi_spacer"></div>
<table class="mod_nwi_table" style="visibility:[DISPLAY_PREVIOUS_NEXT_LINKS]">
<tr>
    <td class="mod_nwi_table_left">[PREVIOUS_PAGE_LINK]</td>
    <td class="mod_nwi_table_center">[OF]</td>
    <td class="mod_nwi_table_right">[NEXT_PAGE_LINK]</td>
</tr>
</table>';

$block2 = '';
$post_header = addslashes('<h2>[TITLE]</h2>
<div class="mod_nwi_metadata">[TEXT_POSTED_BY] [DISPLAY_NAME] [TEXT_ON] [PUBLISHED_DATE] [TEXT_AT] [PUBLISHED_TIME] [TEXT_O_CLOCK] | [TEXT_LAST_CHANGED] [MODI_DATE] [TEXT_AT] [MODI_TIME] [TEXT_O_CLOCK]</div>');
$post_content = '<div class="mod_nwi_content_short">
  [IMAGE]
  [CONTENT_SHORT]
</div>
<div class="mod_nwi_content_long">[CONTENT_LONG]</div>
<div class="fotorama" data-keyboard="true" data-navposition="top" data-nav="thumbs">
[IMAGES]
</div>
';
$image_loop = '<img src="[IMAGE]" alt="[DESCRIPTION]" title="[DESCRIPTION]" data-caption="[DESCRIPTION]" />';
$post_footer = ' <div class="mod_nwi_spacer"></div>
<table class="mod_nwi_table" style="visibility: [DISPLAY_PREVIOUS_NEXT_LINKS]">
<tr>
    <td class="mod_nwi_table_left">[PREVIOUS_PAGE_LINK]</td>
    <td class="mod_nwi_table_center"><a href="[BACK]">[TEXT_BACK]</a></td>
    <td class="mod_nwi_table_right">[NEXT_PAGE_LINK]</td>
</tr>
</table>
<div class="mod_nwi_tags">[TAGS]</div>';