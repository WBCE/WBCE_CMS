<?php

defined('ACCOUNT_TOOL_PATH') or define('ACCOUNT_TOOL_PATH', WB_PATH .'/modules/tool_account_settings');

require_once ACCOUNT_TOOL_PATH .'/functions.php';

// Make sure the login is enabled
if(!FRONTEND_LOGIN) {
    header('Location: '.WB_URL.((INTRO_PAGE) ? PAGES_DIRECTORY : '').'/index.php');
    exit(0);
}


