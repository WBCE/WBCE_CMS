<?php
/**
 *
 * @category        admin
 * @package         settings
 * @author          WebsiteBaker Project
 * @copyright       2009-2012, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: save.php 1577 2012-01-16 18:05:33Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/admin/settings/save.php $
 * @lastmodified    $Date: 2012-01-16 19:05:33 +0100 (Mo, 16. Jan 2012) $
 *
 */

// prevent this file from being accessed directly in the browser (would set all entries in DB settings table to '')
if(!isset($_POST['default_language']) || $_POST['default_language'] == '') die(header('Location: index.php'));

// Find out if the user was view advanced options or not
$advanced = ($_POST['advanced'] == 'yes') ? '?advanced=yes' : '';

// Print admin header
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');

// suppress to print the header, so no new FTAN will be set
if($advanced == '')
{
	$admin = new admin('Settings', 'settings_basic',false);
} else {
	$admin = new admin('Settings', 'settings_advanced',false);
}

// Create a javascript back link
$js_back = ADMIN_URL.'/settings/index.php'.$advanced;
if( !$admin->checkFTAN() )
{
	$admin->print_header();
	$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'],$js_back );
}
// After check print the header
$admin->print_header();

// Ensure that the specified default email is formally valid
if(isset($_POST['server_email']))
{
	$_POST['server_email'] = strip_tags($_POST['server_email']);
    // $pattern = '/^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9]([-a-z0-9_]?[a-z0-9])*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z]{2})|([1]?\d{1,2}|2[0-4]{1}\d{1}|25[0-5]{1})(\.([1]?\d{1,2}|2[0-4]{1}\d{1}|25[0-5]{1})){3})(:[0-9]{1,5})?\r/im';
    $pattern = '/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.(([0-9]{1,3})|([a-zA-Z]{2,6}))$/';
    if(false == preg_match($pattern, $_POST['server_email']))
    {
		$admin->print_error($MESSAGE['USERS_INVALID_EMAIL'].
			'<br /><strong>Email: '.htmlentities($_POST['server_email']).'</strong>', $js_back);
	}
}

if(isset($_POST['wbmailer_routine']) && ($_POST['wbmailer_routine']=='smtp')) {

	$checkSmtpHost = (isset($_POST['wbmailer_smtp_host']) && ($_POST['wbmailer_smtp_host']=='') ? false : true);
	$checkSmtpUser = (isset($_POST['wbmailer_smtp_username']) && ($_POST['wbmailer_smtp_username']=='') ? false : true);
	$checkSmtpPassword = (isset($_POST['wbmailer_smtp_password']) && ($_POST['wbmailer_smtp_password']=='') ? false : true);
	if(!$checkSmtpHost || !$checkSmtpUser || !$checkSmtpPassword) {
		$admin->print_error($TEXT['REQUIRED'].' '.$TEXT['WBMAILER_SMTP_AUTH'].
			'<br /><strong>'.$MESSAGE['GENERIC_FILL_IN_ALL'].'</strong>', $js_back);
	}

}

// Work-out file mode
if($advanced == '')
{
	// Check if should be set to 777 or left alone
	if(isset($_POST['world_writeable']) && $_POST['world_writeable'] == 'true')
    {
		$file_mode = '0777';
		$dir_mode = '0777';
	} else {
		$file_mode = STRING_FILE_MODE;
		$dir_mode = STRING_DIR_MODE;
	}
} else {
	$file_mode = STRING_FILE_MODE;
	$dir_mode = STRING_DIR_MODE;
	if($admin->get_user_id()=='1')
	{
		// Work-out the octal value for file mode
		$u = 0;
		if(isset($_POST['file_u_r']) && $_POST['file_u_r'] == 'true') {
			$u = $u+4;
		}
		if(isset($_POST['file_u_w']) && $_POST['file_u_w'] == 'true') {
			$u = $u+2;
		}
		if(isset($_POST['file_u_e']) && $_POST['file_u_e'] == 'true') {
			$u = $u+1;
		}
		$g = 0;
		if(isset($_POST['file_g_r']) && $_POST['file_g_r'] == 'true') {
			$g = $g+4;
		}
		if(isset($_POST['file_g_w']) && $_POST['file_g_w'] == 'true') {
			$g = $g+2;
		}
		if(isset($_POST['file_g_e']) && $_POST['file_g_e'] == 'true') {
			$g = $g+1;
		}
		$o = 0;
		if(isset($_POST['file_o_r']) && $_POST['file_o_r'] == 'true') {
			$o = $o+4;
		}
		if(isset($_POST['file_o_w']) && $_POST['file_o_w'] == 'true') {
			$o = $o+2;
		}
		if(isset($_POST['file_o_e']) && $_POST['file_o_e'] == 'true') {
			$o = $o+1;
		}
		$file_mode = "0".$u.$g.$o;
		// Work-out the octal value for dir mode
		$u = 0;
		if(isset($_POST['dir_u_r']) && $_POST['dir_u_r'] == 'true') {
			$u = $u+4;
		}
		if(isset($_POST['dir_u_w']) && $_POST['dir_u_w'] == 'true') {
			$u = $u+2;
		}
		if(isset($_POST['dir_u_e']) && $_POST['dir_u_e'] == 'true') {
			$u = $u+1;
		}
		$g = 0;
		if(isset($_POST['dir_g_r']) && $_POST['dir_g_r'] == 'true') {
			$g = $g+4;
		}
		if(isset($_POST['dir_g_w']) && $_POST['dir_g_w'] == 'true') {
			$g = $g+2;
		}
		if(isset($_POST['dir_g_e']) && $_POST['dir_g_e'] == 'true') {
			$g = $g+1;
		}
		$o = 0;
		if(isset($_POST['dir_o_r']) && $_POST['dir_o_r'] == 'true') {
			$o = $o+4;
		}
		if(isset($_POST['dir_o_w']) && $_POST['dir_o_w'] == 'true') {
			$o = $o+2;
		}
		if(isset($_POST['dir_o_e']) && $_POST['dir_o_e'] == 'true') {
			$o = $o+1;
		}
		$dir_mode = "0".$u.$g.$o;
	}
}

$allow_tags_in_fields = array('website_header', 'website_footer');
$allow_empty_values = array('website_header','website_footer','sec_anchor','pages_directory','page_spacer');
$disallow_in_fields = array('pages_directory', 'media_directory','wb_version');

// Query current settings in the db, then loop through them and update the db with the new value
$settings = array();
$old_settings = array();
// Query current settings in the db, then loop through them to get old values
$sql = 'SELECT `name`, `value` FROM `'.TABLE_PREFIX.'settings` '
     . 'ORDER BY `name`';
if($res_settings = $database->query($sql)) {
	$passed = false;
	while($setting = $res_settings->fetchRow()) :
		$old_settings[$setting['name']] = $setting['value'];
		$setting_name = $setting['name'];
		$value = $admin->get_post($setting_name);
		$value = isset($_POST[$setting_name]) ? $value : $old_settings[$setting_name] ;
		switch ($setting_name) :
			case 'default_timezone':
				$value=$value*60*60;
				$passed = true;
				break;
			case 'string_dir_mode':
				$value=$dir_mode;
				$passed = true;
				break;
			case 'string_file_mode':
				$value=$file_mode;
	 			$passed = true;
			break;
			case 'pages_directory':
				break;
			case 'wbmailer_smtp_auth':
				// $value = isset($_POST[$setting_name]) ? $_POST[$setting_name] : '' ;
				$value = true ;
	 			$passed = true;
				break;
			default :
			    $passed = in_array($setting_name, $allow_empty_values);
				break;
		endswitch;

	    if (!in_array($setting_name, $allow_tags_in_fields)) {
	        $value = strip_tags($value);
	    }

	    if (!in_array($value, $disallow_in_fields) && (isset($_POST[$setting_name]) || $passed == true)) {
	        $value = trim($admin->add_slashes($value));
	        $sql = 'UPDATE `'.TABLE_PREFIX.'settings` '
	             . 'SET `value`=\''.$value.'\' '
	             . 'WHERE `name`!=\'wb_version\' AND `name`=\''.$setting_name.'\'';
	        if (!$database->query($sql)) {
				$admin->print_error($database->get_error, $js_back );
				break;
	        }
		}
	endwhile;
}

// Query current search settings in the db, then loop through them and update the db with the new value
$sql = 'SELECT `name`, `value` FROM `'.TABLE_PREFIX.'search` '
     . 'WHERE `extra`=\'\'';
if (!($res_search = $database->query($sql))) {
	$admin->print_error($database->is_error(), $js_back );
}
while($search_setting = $res_search->fetchRow()) :
	$old_value = $search_setting['value'];
	$setting_name = $search_setting['name'];
	$post_name = 'search_'.$search_setting['name'];

    // hold old value if post is empty
    // check search template
    $value = (($admin->get_post($post_name) == '') && ($setting_name != 'template')) 
	         ? $old_value 
	         : $admin->get_post($post_name);
    if (isset($value)) {
		$value = $admin->add_slashes($value);
        $sql = 'UPDATE `'.TABLE_PREFIX.'search` '
             . 'SET `value`=\''.$value.'\' '
             . 'WHERE `name`=\''.$setting_name.'\' AND `extra`=\'\'';
		if(!($database->query($sql))) {
			$admin->print_error($database->get_error, $js_back );
			break;
		}
		// $sql_info = mysql_info($database->db_handle); //->> nicht mehr erforderlich
    }
endwhile;

$admin->print_success($MESSAGE['SETTINGS']['SAVED'], $js_back );
$admin->print_footer();

