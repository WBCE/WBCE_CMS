<?php
/**
 * WBCE Update-Assistent - Maintenance Mode Helper
 *
 * @category    module
 * @package     wbce_updater
 * @version     1.0.5
 * @author      WBCE Community
 * @copyright   2026 WBCE Community
 * @license     MIT License
 */
defined('WB_PATH') or die("This file can't be accessed directly!");

/**
 * Activates WBCE maintenance mode via the Settings class.
 *
 * @param  array  $errors  Reference to the caller's error array — warnings are appended here.
 * @param  array  $LANG    Language strings array from the caller.
 * @return array           ['activated' => bool, 'already_active' => bool]
 */
function wbce_updater_enable_maintenance(array &$errors, array $LANG): array {
    $result = ['activated' => false, 'already_active' => false];

    try {
        require_once WB_PATH . '/framework/class.settings.php';

        $currentStatus = (string)Settings::Get('wb_maintainance_mode');

        if ($currentStatus) {
            $result['already_active'] = true;
            $result['activated']      = true;
            return $result;
        }

        $template_paths = [
            WB_PATH . '/templates/systemplates/maintainance.tpl.php',
            WB_PATH . '/templates/' . DEFAULT_TEMPLATE . '/systemplates/maintainance.tpl.php',
        ];

        $template_exists = false;
        foreach ($template_paths as $tpl_path) {
            if (file_exists($tpl_path)) {
                $template_exists = true;
                break;
            }
        }

        if (!$template_exists) {
            $errors[] = $LANG['WARNING_NO_MAINTENANCE_TEMPLATE'];
        }

        Settings::Set('wb_maintainance_mode', '1');

        if ((string)Settings::Get('wb_maintainance_mode')) {
            $result['activated'] = true;
        } else {
            throw new Exception('Maintenance mode setting could not be verified');
        }

    } catch (Exception $e) {
        $errors[] = $LANG['WARNING_MAINTENANCE_FAILED'] . ': ' . $e->getMessage();
    }

    return $result;
}
