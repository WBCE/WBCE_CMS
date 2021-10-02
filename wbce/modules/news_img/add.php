<?php
/**
 *
 * @category        modules
 * @package         news_img
 * @author          WBCE Community
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2010, Website Baker Org. e.V.
 * @copyright       2019-, WBCE Community
 * @link            https://www.wbce.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WBCE
 *
 */

if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }

require_once __DIR__.'/functions.inc.php';


$header = ''."\n";
$post_loop = '<div class="mod_nwi_group">
    <div class="mod_nwi_teaserpic">
        <a href="[LINK]">[IMAGE]</a>
    </div>
    <div class="mod_nwi_teasertext">
        <a href="[LINK]"><h3>[TITLE]</h3></a>
        <div class="mod_nwi_metadata">[TEXT_POSTED_BY] [DISPLAY_NAME] [TEXT_ON] [PUBLISHED_DATE] [TEXT_AT] [PUBLISHED_TIME] [TEXT_O_CLOCK] </div>
            <div class="mod_nwi_shorttext">
                [SHORT]
            </div>
            <div class="mod_nwi_bottom">
                <div class="mod_nwi_tags">[TAGS]</div>
                <div class="mod_nwi_readmore" style="visibility:[SHOW_READ_MORE];"><a href="[LINK]">[TEXT_READ_MORE]</a></div>
            </div>
        </div>
    </div>
    <div class="mod_nwi_spacer"><hr /></div>';
$footer = '<table class="mod_nwi_table" style="visibility:[DISPLAY_PREVIOUS_NEXT_LINKS]">
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

$resize_preview = '125x125';
$iniset = ini_get('upload_max_filesize');
$iniset = mod_nwi_return_bytes($iniset);

if (file_exists(__DIR__.'/views/default/config.php')) {
	require_once __DIR__.'/views/default/config.php';
} 

if (file_exists(__DIR__.'/views/default/config.private.php')) {
	require_once __DIR__.'/views/default/config.private.php';
} 


$database->query(
    "INSERT INTO `".TABLE_PREFIX."mod_news_img_settings` ".
    "(`section_id` ,`header` ,`post_loop` ,`footer` ,`block2` ,`post_header` ,`post_content` ,`image_loop` ,`post_footer` ,`gallery` ,`imgthumbsize`,`resize_preview` ,`imgmaxwidth`,`imgmaxheight`,`imgmaxsize`) VALUES ".
    "('$section_id','$header','$post_loop','$footer','$block2','$post_header','$post_content','$image_loop','$post_footer','fotorama','100x100'     ,'$resize_preview','900'        ,'900'         ,'$iniset')"
);
