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

// Default settings if no corresponding values ​​exist in the database.
Settings::Set("enabled_captcha", true, false);
Settings::Set("enabled_asp", true, false);
Settings::Set("captcha_type", "calc_text", false);
Settings::Set("asp_session_min_age", "20", false);
Settings::Set("asp_view_min_age", "10", false);
Settings::Set("asp_input_min_age", "5", false);
Settings::Set("ct_text", "", false);
