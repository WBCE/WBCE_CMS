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

// Include config file and admin class file
require '../../config.php';
require_once WB_PATH . '/framework/class.admin.php';

$action = 'cancel';
// Set parameter 'action' as alternative to javascript mechanism
$action = (isset($_POST['modify']) ? 'modify' : $action);
$action = (isset($_GET['modify'])  ? 'modify' : $action);
$action = (isset($_POST['delete']) ? 'delete' : $action);

switch ($action) {
    case 'modify':
        // Print header
        $admin = new admin('Access', 'users_modify');		
        $user_id = $admin->checkIDKEY('user_id', 0, $_SERVER['REQUEST_METHOD']);
        // Check if user id is a valid number and doesn't equal 0
        if ($user_id == 0) {
            $admin->print_error($MESSAGE['GENERIC_FORGOT_OPTIONS']);
        }
        // Get existing values
        $results = $database->query("SELECT * FROM `{TP}users` WHERE `user_id` = " . $user_id);
        $user = $results->fetchRow(MYSQL_ASSOC);

        // Setup template object, parse vars to it, then parse it
        // Create new template object
        $oTemplate = new Template(dirname($admin->correct_theme_source('users_form.htt')));
        $oTemplate->set_file('page', 'users_form.htt');
        $oTemplate->set_block('page', 'main_block', 'main');
        $oTemplate->set_var(
            array(
                'ACTION_URL'   => ADMIN_URL . '/users/save.php',
                'SUBMIT_TITLE' => $TEXT['SAVE'],
                'USER_ID'      => $user['user_id'],
                'USERNAME'     => $user['username'],
                'DISPLAY_NAME' => $user['display_name'],
                'EMAIL'        => $user['email'],
                'ADMIN_URL'    => ADMIN_URL,
                'WB_URL'       => WB_URL,
                'THEME_URL'    => THEME_URL,
            )
        );

        $oTemplate->set_var('FTAN', $admin->getFTAN());
        if ($user['active'] == 1) {
            $oTemplate->set_var('ACTIVE_CHECKED', ' checked="checked"');
            $oTemplate->set_var('PASSWORD_REQ',   ' style="display:none;"');
        } else {
            $oTemplate->set_var('DISABLED_CHECKED', ' checked="checked"');
            $oTemplate->set_var('PASSWORD_REQ', '');
        }
        // Add groups to list
        $oTemplate->set_block('main_block', 'group_list_block', 'group_list');
        $results = $database->query("SELECT group_id, name FROM {TP}groups WHERE group_id != '1' ORDER BY name");
        if ($results->numRows() > 0) {
            $oTemplate->set_var('ID', '');
            $oTemplate->set_var('NAME', $TEXT['PLEASE_SELECT'] . '...');
            $oTemplate->set_var('SELECTED', '');
            $oTemplate->parse('group_list', 'group_list_block', true);
            while ($group = $results->fetchRow()) {
                $oTemplate->set_var('ID', $group['group_id']);
                $oTemplate->set_var('NAME', $group['name']);
                if (in_array($group['group_id'], explode(",", $user['groups_id']))) {
                    $oTemplate->set_var('SELECTED', ' selected="selected"');
                } else {
                    $oTemplate->set_var('SELECTED', '');
                }
                $oTemplate->parse('group_list', 'group_list_block', true);
            }
        }

        // Only allow the user to add a user to the Administrators group if they belong to it
        if (in_array(1, $admin->get_groups_id())) {
            $oTemplate->set_var('ID', '1');
            $users_groups = $admin->get_groups_name();
            $oTemplate->set_var('NAME', $users_groups[1]);

            $in_group = false;
            foreach ($admin->get_groups_id() as $cur_gid) {
                if (in_array($cur_gid, explode(",", $user['groups_id']))) {
                    $in_group = true;
                }
            }

            if ($in_group) {
                $oTemplate->set_var('SELECTED', ' selected="selected"');
            } else {
                $oTemplate->set_var('SELECTED', '');
            }
            $oTemplate->parse('group_list', 'group_list_block', true);
        } else {
            if ($results->numRows() == 0) {
                $oTemplate->set_var('ID', '');
                $oTemplate->set_var('NAME', $TEXT['NONE_FOUND']);
                $oTemplate->set_var('SELECTED', ' selected="selected"');
                $oTemplate->parse('group_list', 'group_list_block', true);
            }
        }

        // Generate username field name
        $username_fieldname = 'username_';
        $salt = "abchefghjkmnpqrstuvwxyz0123456789";
        srand((double) microtime() * 1000000);
        $i = 0;
        while ($i <= 7) {
            $num = rand() % 33;
            $tmp = substr($salt, $num, 1);
            $username_fieldname = $username_fieldname . $tmp;
            $i++;
        }

        // Work-out if home folder should be shown
        if (!HOME_FOLDERS) {
            $oTemplate->set_var('DISPLAY_HOME_FOLDERS', 'display:none;');
        }

        // Include the WB functions file
        require_once WB_PATH . '/framework/functions.php';

        // Add media folders to home folder list
        $oTemplate->set_block('main_block', 'folder_list_block', 'folder_list');
        foreach (directory_list(WB_PATH . MEDIA_DIRECTORY) as $name) {
            $oTemplate->set_var('NAME', str_replace(WB_PATH, '', $name));
            $oTemplate->set_var('FOLDER', str_replace(WB_PATH . MEDIA_DIRECTORY, '', $name));
            if ($user['home_folder'] == str_replace(WB_PATH . MEDIA_DIRECTORY, '', $name)) {
                $oTemplate->set_var('SELECTED', ' selected="selected"');
            } else {
                $oTemplate->set_var('SELECTED', ' ');
            }
            $oTemplate->parse('folder_list', 'folder_list_block', true);
        }
        // Insert language text and messages
        $oTemplate->set_var(
            array(
                'TEXT_RESET'           => $TEXT['RESET'],
                'TEXT_CANCEL'          => $TEXT['CANCEL'],
                'TEXT_ACTIVE'          => $TEXT['ACTIVE'],
                'TEXT_DISABLED'        => $TEXT['DISABLED'],
                'TEXT_PLEASE_SELECT'   => $TEXT['PLEASE_SELECT'],
                'TEXT_USERNAME'        => $TEXT['USERNAME'],
                'TEXT_PASSWORD'        => $TEXT['PASSWORD'],
                'TEXT_RETYPE_PASSWORD' => $TEXT['RETYPE_PASSWORD'],
                'TEXT_DISPLAY_NAME'    => $TEXT['DISPLAY_NAME'],
                'TEXT_EMAIL'           => $TEXT['EMAIL'],
                'TEXT_GROUP'           => $TEXT['GROUP'],
                'TEXT_NONE'            => $TEXT['NONE'],
                'TEXT_HOME_FOLDER'     => $TEXT['HOME_FOLDER'],
                'USERNAME_FIELDNAME'   => $username_fieldname,
                'CHANGING_PASSWORD'    => $MESSAGE['USERS_CHANGING_PASSWORD'],
                'HEADING_MODIFY_USER'  => $HEADING['MODIFY_USER'],
                'INPUT_NEW_PASSWORD'   => $admin->passwordField('password')
            )
        );

        // Parse template object
        $oTemplate->parse('main', 'main_block', false);
        $oTemplate->pparse('output', 'page');
        // Print admin footer
        $admin->print_footer();
        break;

    case 'delete':
        // Print header
        $admin = new admin('Access', 'users_delete');
        $user_id = intval($admin->checkIDKEY('user_id', 0, $_SERVER['REQUEST_METHOD']));
        // Check if user id is a valid number and doesnt equal 1
        if ($user_id == 0) {
            $admin->print_error($MESSAGE['GENERIC_FORGOT_OPTIONS']);
        }
        if (($user_id < 2)) {
            // if($admin_header) { $admin->print_header(); }
            $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
        }
        $sSql = "SELECT `active` FROM `{TP}users` WHERE `user_id` = " . $user_id;
        if (($iDeleteUser = $database->get_one($sSql)) == 1) {
            // Delete the user
            $database->query("UPDATE `{TP}users` SET `active` = 0 WHERE `user_id` = " . $user_id);
        } else {
            $database->query("DELETE FROM `{TP}users` WHERE `user_id` = " . $user_id);
        }

        if ($database->is_error()) {
            $admin->print_error($database->get_error());
        } else {
            $admin->print_success($MESSAGE['USERS_DELETED']);
        }
        // Print admin footer
        $admin->print_footer();
        break;

    default:
        break;
}
