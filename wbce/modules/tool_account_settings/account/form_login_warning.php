<?php
/**
 * WBCE CMS — Frontend login warning: page content renderer
 *
 * Included via PAGE_CONTENT by login_warning.php.
 * Renders login_warning.twig within the current site template.
 */

defined('WB_PATH') or die("Cannot access this file directly");

$oAccounts = new Accounts();
$oAccounts->useTwigTemplate('login_warning.twig', [
    'WB_URL' => WB_URL,
]);
