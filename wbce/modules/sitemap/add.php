<?php
/**
 * WebsiteBaker Community Edition (WBCE)
 * Way Better Content Editing.
 * Visit http://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

// Insert an extra row into the database
$header = addslashes('');
$level_header = addslashes('<ul>');
$sitemaploop = addslashes('<li><a href="[LINK]" target="[TARGET]">[PAGE_TITLE]</a></li>');
$level_footer = addslashes('</ul>');
$footer = addslashes('');

$database->query("INSERT INTO ".TABLE_PREFIX."mod_sitemap (page_id,section_id,static,header,sitemaploop,footer,level_header,level_footer,startatroot,depth,show_hidden) VALUES ('$page_id','$section_id','1','$header','$sitemaploop','$footer','$level_header','$level_footer', '1', '0','0')");

?>