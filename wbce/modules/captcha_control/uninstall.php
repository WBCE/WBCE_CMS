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

// Current format
Settings::delete('enabled_captcha');
Settings::delete('enabled_asp');
Settings::delete('captcha_type');
Settings::delete('captcha_altcha');

// Legacy keys — left over from older versions or incomplete migrations
foreach ([
    'captcha_altcha_hmac_key', 'captcha_altcha_max', 'captcha_altcha_ttl',
    'captcha_cfg',
    'asp_session_min_age', 'asp_view_min_age', 'asp_input_min_age', 'ct_text',
] as $key) {
    if (Settings::exists($key)) {
        Settings::delete($key);
    }
}
