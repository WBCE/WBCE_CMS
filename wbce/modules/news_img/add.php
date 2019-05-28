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

if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

require_once __DIR__.'/functions.inc.php';

$header = ''."\n";
$post_loop = '<div class="mod_nwi_group">
    <div class="mod_nwi_teaserpic">
        <a href="[LINK]">[IMAGE]</a>
    </div>
    <div class="mod_nwi_teasertext">
        <a href="[LINK]"><h3>[TITLE]</h3></a>
        <div class="mod_nwi_metadata">[DISPLAY_NAME] | [PUBLISHED_DATE]</div>
            <div class="mod_nwi_shorttext">
                [SHORT]
            </div>
            <span style="visibility:[SHOW_READ_MORE];"><a href="[LINK]">[TEXT_READ_MORE]</a></span>
        </div>
    </div>
    <div class="mod_nwi_spacer"><hr /></div>';
$footer = '<table class="page-header" style="display: [DISPLAY_PREVIOUS_NEXT_LINKS]">
<tr>
    <td class="page-left">[PREVIOUS_PAGE_LINK]</td>
    <td class="page-center">[OF]</td>
    <td class="page-right">[NEXT_PAGE_LINK]</td>
</tr>
</table>';
$block2 = '';
$post_header = addslashes('<h2>[TITLE]</h2>');
$post_content = '[CONTENT]<br />
<div class="fotorama" data-keyboard="true" data-navposition="top" data-nav="thumbs">
[IMAGES]
</div>
';
$image_loop = '<img src="[IMAGE]" data-caption="[DESCRIPTION]" />';
$post_footer = '<div class="div_link">
<a href="[BACK]">[TEXT_BACK]</a>
</div>';

$resize_preview = '125x125';
$iniset = ini_get('upload_max_filesize');
$iniset = mod_nwi_return_bytes($iniset);

$database->query(
    "INSERT INTO `".TABLE_PREFIX."mod_news_img_settings` ".
    "(`section_id` ,`page_id` ,`header` ,`post_loop` ,`footer` ,`block2` ,`post_header` ,`post_content` ,`image_loop` ,`post_footer` ,`gallery` ,`imgthumbsize`,`resize_preview` ,`imgmaxwidth`,`imgmaxheight`,`imgmaxsize`) VALUES ".
    "('$section_id','$page_id','$header','$post_loop','$footer','$block2','$post_header','$post_content','$image_loop','$post_footer','fotorama','100x100'     ,'$resize_preview','900'        ,'900'         ,'$iniset')"
);
