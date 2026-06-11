<?php
/**
 * WBCE CMS — captcha backward-compatibility shim
 *
 * The real implementation lives in modules/captcha_control/Captcha.php,
 * registered via modules/captcha_control/initialize.php which runs before
 * any module or page code.
 *
 * This file is kept because several modules (miniform, tool_account_settings)
 * do:
 *   require_once WB_PATH . '/include/captcha/captcha.php';
 * Removing this file would cause a Fatal Error in those modules.
 *
 * All old image-based captcha code has been removed.
 * call_captcha() is defined here only as a safety guard; it is normally
 * already defined by modules/captcha_control/initialize.php.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license   GNU GPL2 (or any later version)
 */

defined('WB_PATH') or exit('No direct access allowed');

if (!function_exists('call_captcha')) {
    /**
     * Render the captcha widget.
     * Delegates to Captcha::render() — see modules/captcha_control/Captcha.php.
     */
    function call_captcha(
        string  $action        = 'all',
        string  $style         = '',
        string  $sec_id        = '',
        ?string $type_override = null
    ): void {
        Captcha::render($action, $style, $sec_id, $type_override);
    }
}
