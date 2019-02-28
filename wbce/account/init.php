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

// Prevent this file from being accessed directly.
defined('WB_PATH') or die();

// Define the ACCOUNT_TOOL_PATH constant for better connection to Account Admin Tool
defined('ACCOUNT_TOOL_PATH') or define('ACCOUNT_TOOL_PATH', WB_PATH .'/modules/tool_account_settings');

require_once ACCOUNT_TOOL_PATH .'/functions.php';

// Check if FRONTEND_LOGIN is enabled
if(!FRONTEND_LOGIN) {
    header('Location: '.WB_URL.((INTRO_PAGE) ? PAGES_DIRECTORY : '').'/index.php');
    exit(0);
}
// Create new wb object
$wb = new Frontend();
$wb->get_website_settings();