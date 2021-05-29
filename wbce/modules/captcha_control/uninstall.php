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

// no direct file access
if (count(get_included_files())==1) {
    header("Location: ../index.php", true, 301);
}

// Default settings
Settings::Del("enabled_captcha");
Settings::Del("enabled_asp");
Settings::Del("captcha_type");
Settings::Del("asp_session_min_age");
Settings::Del("asp_view_min_age");
Settings::Del("asp_input_min_age");
Settings::Del("ct_text");

$table = TABLE_PREFIX .'mod_captcha_control';
$database->query("DROP TABLE `$table`");
