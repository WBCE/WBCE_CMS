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

// Print admin header
require '../../config.php';
require_once WB_PATH . '/framework/class.admin.php';

$admin = new admin('Preferences', 'start', false); // suppress print_header, prevent setting new FTAN

function save_preferences(&$admin, &$database)
{
    global $MESSAGE;
    $sRetVal = '';
    $aErrMsg = array();
    $iMinPassLength = 6;
    // first check form-tan
    if (!$admin->checkFTAN()) {
        $aErrMsg[] = $MESSAGE['GENERIC_SECURITY_ACCESS'];
    }

    // Get entered values and validate all
    // remove any dangerouse chars from display_name
    $display_name = $admin->add_slashes(strip_tags(trim($admin->get_post('display_name'))));
    $display_name = ($display_name == '' ? $admin->get_display_name() : $display_name);

    // check that display_name is unique in whoole system (prevents from User-faking)
    $sql = "SELECT COUNT(*) FROM `{TP}users` WHERE `user_id` <> %d AND `display_name` LIKE '%s'";
    if ($database->get_one(sprintf($sql, $admin->get_user_id(), $display_name)) > 0) {
        $aErrMsg[] = $MESSAGE['USERS_USERNAME_TAKEN'];
    }

    // language must be 2 upercase letters only
    $language = strtoupper($admin->get_post('language'));
    $language = (preg_match('/^[A-Z]{2}$/', $language) ? $language : DEFAULT_LANGUAGE);

    // timezone must be between -12 and +13 or system_default
    $timezone = $admin->get_post('timezone');
    $timezone = (is_numeric($timezone) ? $timezone * 3600 : '');

    // date_format must be a key from /interface/date_formats
    $date_format = $admin->get_post('date_format');
    $date_format_key = str_replace(' ', '|', $date_format);
    $user_time = true;
    include ADMIN_PATH . '/interface/date_formats.php';
    $date_format = (array_key_exists($date_format_key, $DATE_FORMATS) ? $date_format : 'system_default');
    $date_format = ($date_format == 'system_default' ? '' : $date_format);
    unset($DATE_FORMATS);

    // time_format must be a key from /interface/time_formats
    $time_format = $admin->get_post('time_format');
    $time_format_key = str_replace(' ', '|', $time_format);
    $user_time = true;
    include ADMIN_PATH . '/interface/time_formats.php';
    $time_format = (array_key_exists($time_format_key, $TIME_FORMATS) ? $time_format : 'system_default');
    $time_format = ($time_format == 'system_default' ? '' : $time_format);
    unset($TIME_FORMATS);

    // Email should be validatet by core
    $email = trim($admin->get_post('email') == null ? '' : $admin->get_post('email'));
    if (!$admin->validate_email($email)) {
        $email = '';
        $aErrMsg[] = $MESSAGE['USERS_INVALID_EMAIL'];
    } else {
        if ($email != '') {
            // check that email is unique in whoole system
            $email = $admin->add_slashes($email);
            $sql = "SELECT COUNT(*) FROM `{TP}users` WHERE `user_id` <> %d AND `email` LIKE '%s'";
            if ($database->get_one(sprintf($sql, (int)$admin->get_user_id(), $email)) > 0) {
                $aErrMsg[] = $MESSAGE['USERS_EMAIL_TAKEN'];
            }
        }
    }
    // Receive password vars and calculate needed action
    $sCurrentPassword = $admin->get_post('current_password');
    $sCurrentPassword = (is_null($sCurrentPassword) ? '' : $sCurrentPassword);
    $sNewPassword = $admin->get_post('new_password_1');
    $sRePassword = $admin->get_post('new_password_2');
    $iUserID = (int)$admin->get_user_id();

    // Check existing password
    if ($admin->doCheckPassword($iUserID, $sCurrentPassword) === false) {
        // access denied
        $aErrMsg[] = $MESSAGE['PREFERENCES_CURRENT_PASSWORD_INCORRECT'];
    } else {
        // Validate new password
        $sPwHashNew = '';
        if ($sNewPassword != '') {
            $checkPassword = $admin->checkPasswordPattern($sNewPassword, $sRePassword);
            if (is_array($checkPassword)) {
                $aErrMsg[] = $checkPassword[0];
            } else {
                $sPwHashNew = $checkPassword;
            }
        }
		
		
		
		
        // If no validation errors, try to update the database, otherwise return errormessages
        if (sizeof($aErrMsg) == 0) {
            $aUpdate = array(
                'user_id' => $iUserID,
                'display_name' => remove_special_characters($database->escapeString($display_name)),
                'language' => $database->escapeString($language),
                'timezone' => $database->escapeString($timezone),
                'date_format' => $database->escapeString($date_format),
                'time_format' => $database->escapeString($time_format)
            );
            if ($sPwHashNew != '') {
                $aUpdate['password'] = $database->escapeString($sPwHashNew);
            }
            if ($email != '') {
                $aUpdate['email'] = $database->escapeString($email);
            }

            // Update record in Database
            if ($database->updateRow('{TP}users', 'user_id', $aUpdate)) {
                // Database update successfull, take over values into the session
                $_SESSION['DISPLAY_NAME'] = $display_name;
                $_SESSION['LANGUAGE'] = $language;
                $_SESSION['TIMEZONE'] = $timezone;
                $_SESSION['EMAIL'] = $email;
                // Update date format
                if ($date_format != '') {
                    $_SESSION['DATE_FORMAT'] = $date_format;
                    if (isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) {
                        unset($_SESSION['USE_DEFAULT_DATE_FORMAT']);
                    }
                } else {
                    $_SESSION['USE_DEFAULT_DATE_FORMAT'] = true;
                    if (isset($_SESSION['DATE_FORMAT'])) {
                        unset($_SESSION['DATE_FORMAT']);
                    }
                }
                // Update time format
                if ($time_format != '') {
                    $_SESSION['TIME_FORMAT'] = $time_format;
                    if (isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) {
                        unset($_SESSION['USE_DEFAULT_TIME_FORMAT']);
                    }
                } else {
                    $_SESSION['USE_DEFAULT_TIME_FORMAT'] = true;
                    if (isset($_SESSION['TIME_FORMAT'])) {
                        unset($_SESSION['TIME_FORMAT']);
                    }
                }
                // Update timezone
                if ($timezone !== '') {
                    $_SESSION['TIMEZONE'] = $timezone;
                    if (isset($_SESSION['USE_DEFAULT_TIMEZONE'])) {
                        unset($_SESSION['USE_DEFAULT_TIMEZONE']);
                    }
                } else {
                    $_SESSION['USE_DEFAULT_TIMEZONE'] = true;
                    if (isset($_SESSION['TIMEZONE'])) {
                        unset($_SESSION['TIMEZONE']);
                    }
                }
            } else {
                ob_start();
                //debug_dump($aUpdate);
                $aErrMsg[] = ob_get_clean() . ' invalid database UPDATE call in ' . __FILE__ . '::' . __FUNCTION__ . ' before line ' . __LINE__;
            }
        }
    }
    if (is_countable($aErrMsg)) {
        $sRetVal = implode('<br />', $aErrMsg);
    }
    return $sRetVal;
}

$mRetVal = save_preferences($admin, $database);

$admin->print_header();
if ($mRetVal == '') {
    $admin->print_success($MESSAGE['PREFERENCES_DETAILS_SAVED']);
} else {
    $admin->print_error($mRetVal);
}
$admin->print_footer();
