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

$module_directory = 'wysiwyg';
$module_name = 'WYSIWYG';
$module_function = 'page';
$module_version = '2.10.1';
$module_platform = '1.4.0';
$module_author = 'Ryan Djurovich';
$module_license = 'GNU General Public License';
$module_description = 'This module allows you to edit the contents of a page using a graphical editor';
$module_home = 'https://www.wbce.org';

/**
 * v2.10.1 - 2026/10/11
 *         - security hardening (no schema change):
 *         ! save.php now verifies the posted section_id actually belongs to the
 *           posted page_id. modules/admin.php only checks admin rights on page_id
 *           and that the section exists, so previously an editor of page A could
 *           POST a section_id from page B and overwrite that page's content
 *           (cross-page write). Mismatch -> PAGES_INSUFFICIENT_PERMISSIONS.
 *         ! defense-in-depth (int) casts on raw-interpolated ids in add.php
 *           (page_id/section_id), delete.php (section_id) and search.php
 *           (section_id); these were already int in practice via the core.
 */