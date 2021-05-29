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

//no direct file access
if (count(get_included_files())==1) {
    header("Location: ../index.php", true, 301);
}

// Default settings
Settings::Set("enabled_captcha", true);
Settings::Set("enabled_asp", true);
Settings::Set("captcha_type", "calc_text");
Settings::Set("asp_session_min_age", "20");
Settings::Set("asp_view_min_age", "10");
Settings::Set("asp_input_min_age", "5");
Settings::Set("ct_text", "");
