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
// suppress to print the header, so no new FTAN will be set
$admin = new admin('Access', 'users_modify', false);

/**
 * Execute a prepared statement with an IN() list of integer IDs.
 *
 * @param mysqli $db
 * @param string $sqlBase SQL with a single `%s` placeholder where the IN list should be inserted,
 *                        e.g. "SELECT group_id FROM ".TABLE_PREFIX."groups WHERE group_id IN (%s)".
 * @param array<int> $ids  List of integer IDs (already validated).
 * @param string $types    Types string for the bound params (default: 'i' for each ID).
 * @return mysqli_result|false
 */
function db_exec_in_clause_int(mysqli $db, $sqlBase, array $ids, $types = null)
{
    if (empty($ids)) {
        return false;
    }

    // All IDs must be integers to avoid injection
    $clean = [];
    foreach ($ids as $id) {
        if (!is_numeric($id)) {
            return false;
        }
        $clean[] = (int)$id;
    }

    $placeholders = implode(',', array_fill(0, count($clean), '?'));
    $sql = sprintf($sqlBase, $placeholders);

    if ($types === null) {
        $types = str_repeat('i', count($clean));
    }

    $stmt = $db->prepare($sql);
    if ($stmt === false) {
        return false;
    }

    // bind_param requires arguments by reference
    $params = [];
    $params[] = &$types;
    foreach ($clean as $k => $v) {
        $params[] = &$clean[$k];
    }

    call_user_func_array([$stmt, 'bind_param'], $params);

    if (!$stmt->execute()) {
        $stmt->close();
        return false;
    }

    return $stmt->get_result();
}


// Create a javascript back link
$js_back = ADMIN_URL . '/users/index.php';

if (!$admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $js_back);
}

// Check if user id is a valid number and doesnt equal 1
if (!isset($_POST['user_id']) or !is_numeric($_POST['user_id']) or $_POST['user_id'] == 1) {
    header("Location: index.php");
    exit(0);
} else {
    $user_id = $_POST['user_id'];
}


$activeUserGroupArray = explode(",", $_SESSION['GROUPS_ID']);

//only allow administrators to assign users to any group; usual users may assign themselves and other users only to groups where they already belong to
if ((!in_array('1',$activeUserGroupArray) && in_array('1',$_POST['groups'])) 
	|| (!in_array('1',$activeUserGroupArray) && array_diff($activeUserGroupArray,$_POST['groups']))) {
    unset($_POST['group_id']);
	$admin->print_header();
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], $js_back);
}

// Gather details entered
//$groups_id = (isset($_POST['groups'])) ? implode(",", $admin->add_slashes($_POST['groups'])) : '';

$groups = isset($_POST['groups']) && is_array($_POST['groups']) ? $_POST['groups'] : [];
$groups_id = [];
foreach ($groups as $g) {
    if (is_numeric($g)) {
        $groups_id[] = (int)$g;
    }
}
$groups_id = array_values(array_unique($groups_id));

$active = $admin->add_slashes($_POST['active'][0]);
$usernameTmp = $admin->get_post_escaped('username_fieldname');
$username = strtolower($admin->get_post_escaped($usernameTmp));
$sNewPassword = $admin->get_post('password');
$sRePassword = $admin->get_post('password2');
$display_name = remove_special_characters($admin->get_post_escaped('display_name'));
$email = $admin->get_post_escaped('email');
$home_folder = $admin->get_post_escaped('home_folder');





$admin->print_header();
// Check values
if ($groups_id == "") {
    $admin->print_error($MESSAGE['USERS_NO_GROUP'], $js_back);
}
if (!preg_match('/^[a-z]{1}[a-z0-9_-]{2,}$/i', $username)) {
    $admin->print_error($MESSAGE['USERS_NAME_INVALID_CHARS'] . ' / ' .
        $MESSAGE['USERS_USERNAME_TOO_SHORT'], $js_back);
}

// choose group_id from groups_id - workaround for still remaining calls to group_id (to be cleaned-up)
//$gid_tmp = explode(',', $groups_id);
$gid_tmp = $groups_id;
if (in_array('1', $gid_tmp)) {
    $group_id = '1';
} // if user is in administrator-group, get this group
else {
    $group_id = $gid_tmp[0];
} // else just get the first one
unset($gid_tmp);


if ($email != "") {
    if ($admin->validate_email($email) == false) {
        $admin->print_error($MESSAGE['USERS_INVALID_EMAIL'], $js_back);
    }
} else { // e-mail must be present
    $admin->print_error($MESSAGE['SIGNUP_NO_EMAIL'], $js_back);
}

// Check if the email already exists
$sSql = "SELECT `user_id` FROM `{TP}users` WHERE `email` = '" . $email . "' AND `user_id` <> " . $user_id;
if ($database->get_one($sSql)) {
    $admin->print_error($MESSAGE['USERS_EMAIL_TAKEN'], $js_back);
}

// Validate the Password
$sEncodedPassword = '';
if ($sNewPassword != '') {
    $checkPassword = $admin->checkPasswordPattern($sNewPassword, $sRePassword);
    if (is_array($checkPassword)) {
        $admin->print_error(explode('<br />', $checkPassword[0]), $js_back);
    } else {
        $sEncodedPassword = $checkPassword;
    }
}

$aUpdate = array(
    'user_id' => $user_id,
    'groups_id' => implode(',',$groups_id),
    'group_id' => $group_id,
    'active' => $active,
    'display_name' => $display_name,
    'home_folder' => $home_folder,
    'email' => $email
);

if ($username != 'admin') {
    $aUpdate['username'] = $username;
}

if ($sEncodedPassword != "") {
    $aUpdate['password'] = $sEncodedPassword;
}

// Update User record in the Database
$database->updateRow('{TP}users', 'user_id', $aUpdate);

if ($database->is_error()) {
    $admin->print_error($database->get_error(), $js_back);
} else {
    //  debug_dump($aUpdate);
    $admin->print_success($MESSAGE['USERS_SAVED']);
}
$admin->print_footer(); // Print admin footer
