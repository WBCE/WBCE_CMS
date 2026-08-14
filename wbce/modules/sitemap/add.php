<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

// Insert an extra row into the database
$database->insertRow('{TP}mod_sitemap', [
	'page_id'      => $page_id,
	'section_id'   => $section_id,
	'static'       => 1,
	'header'       => '',
	'sitemaploop'  => '<li><a href="[LINK]" target="[TARGET]">[PAGE_TITLE]</a></li>',
	'footer'       => '',
	'level_header' => '<ul>',
	'level_footer' => '</ul>',
	'startatroot'  => 1,
	'depth'        => 0,
	'show_hidden'  => 0,
]);

?>