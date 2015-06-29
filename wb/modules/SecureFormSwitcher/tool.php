<?php
/**
 *
 * @category        modules
 * @package         SecureFormSwitcher
 * @author          WebsiteBaker Project, D Woellbrink
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.2
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: tool.php 1538 2011-12-10 15:06:15Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/modules/SecureFormSwitcher/tool.php $
 * @lastmodified    $Date: 2011-12-10 16:06:15 +0100 (Sa, 10. Dez 2011) $
 *
 */

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false)
{
	die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}

// load module language file
$mod_path = (dirname(__FILE__));
require_once( $mod_path.'/language_load.php' );
// callback function for settings name
function converttoupper($val, $key, $vars) {
	$vars[0][$key] = strtoupper($key);
	$vars[1][$vars[0][$key]] = ($val);
}

// create backlinks
$js_back =  ADMIN_URL.'/admintools/tool.php?tool=SecureFormSwitcher';
$backlink =  ADMIN_URL.'/admintools/index.php';
$FileNotFound = '&nbsp;';
// defaults settings
$default_cfg = array(
	'secure_form_module' => '',
	'wb_secform_secret' => '5609bnefg93jmgi99igjefg',
	'wb_secform_secrettime' => '86400',
	'wb_secform_timeout' => '7200',
	'wb_secform_tokenname' => 'formtoken',
	'wb_secform_usefp' => 'true',
	'fingerprint_with_ip_octets' => '2',
);
$setting = $default_cfg;
$MultitabTarget = WB_PATH.'/framework/SecureForm.mtab.php';
// get stored settings to set in mask
$sql  = 'SELECT * FROM `'.TABLE_PREFIX.'settings` ';
$sql .= 'WHERE `name` = \'secure_form_module\'';
$sql .=    'OR `name`=\'fingerprint_with_ip_octets\' ';
$sql .=    'OR `name`=\'wb_secform_usefp\' ';
$sql .=    'OR `name`=\'wb_secform_tokenname\' ';
$sql .=    'OR `name`=\'wb_secform_timeout\' ';
$sql .=    'OR `name`=\'wb_secform_secrettime\' ';
$sql .=    'OR `name`=\'wb_secform_secret\' ';
if($res = $database->query($sql) ) {
	if($res->numRows() > 0) {
		while($rec = $res->fetchRow(MYSQL_ASSOC)) {
	        $setting[$rec['name']] = $rec['value'];
		}
	} else {
		// add missing values
		db_update_key_value('settings', $setting );
	}
}

$action = 'show';
$action = isset($_POST['save_settings']) ? 'save_settings' : $action;
$action = isset($_POST['save_settings_default']) ? 'save_settings_default' : $action;

switch ($action) :
	case 'save_settings':
		$cfg = array(
			'secure_form_module' => (isset($_POST['ftan_switch']) ? $_POST['ftan_switch'] : 'mtab'),
			'wb_secform_secret' => (isset($_POST['wb_secform_secret']) ? $_POST['wb_secform_secret'] : $setting['wb_secform_secret'] ),
			'wb_secform_secrettime' => (isset($_POST['wb_secform_secrettime']) ? $_POST['wb_secform_secrettime'] : $setting['wb_secform_secrettime'] ),
			'wb_secform_timeout' => (isset($_POST['wb_secform_timeout']) ? $_POST['wb_secform_timeout'] : $setting['wb_secform_timeout'] ),
			'wb_secform_tokenname' => (isset($_POST['wb_secform_tokenname']) ? $_POST['wb_secform_tokenname'] : $setting['wb_secform_tokenname'] ),
			'wb_secform_usefp' => (isset($_POST['wb_secform_usefp']) ? $_POST['wb_secform_usefp'] : $setting['wb_secform_usefp'] ),
			'fingerprint_with_ip_octets' => (isset($_POST['fingerprint_with_ip_octets']) ? $_POST['fingerprint_with_ip_octets'] : $setting['fingerprint_with_ip_octets'] ),
		);
		// unset($_POST);
		$_SESSION['CFG'] = $cfg;
		break;
	case 'save_settings_default':
		$cfg = $default_cfg;
		$cfg['secure_form_module'] = $setting['secure_form_module'];
		break;
endswitch;


switch ($action) :
	case 'save_settings':
	case 'save_settings_default':
		if (!$admin->checkFTAN())
		{
			if(!$admin_header) { $admin->print_header(); }
			$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'],$_SERVER['REQUEST_URI']);
		}
		if(file_exists($MultitabTarget)) {
			$val = ( isset($_POST['ftan_switch'])  ? ($_POST['ftan_switch']) : 'mtab');
		} else {
			$cfg['secure_form_module'] = '';
			$FileNotFound = $SFS_TEXT['FILE_FORMTAB_NOT_GOUND'];
		}

		db_update_key_value('settings', $cfg );
		// check if there is a database error, otherwise say successful
		if(!$admin_header) { $admin->print_header(); }
		if($database->is_error()) {
			$admin->print_error($database->get_error(), $js_back);
		} else {
            if(isset($_SESSION['CFG'])) { unset($_SESSION['CFG']);}
			$admin->print_success($MESSAGE['PAGES_SAVED'], $js_back);
		}
		break;
endswitch;

// set template file and assign module and template block
$tpl = new Template(WB_PATH.'/modules/SecureFormSwitcher/htt','keep');
$tpl->set_file('page', 'switchform.htt');
$tpl->debug = false; // false, true
$tpl->set_block('page', 'main_block', 'main');

$checked = ($setting['secure_form_module']!='');

$ftanMode = ($checked ? $SFS_TEXT['SECURE_FORM'] : $SFS_TEXT['SECURE_FORMMTAB']);
$target = ($checked) ? '.'.$setting['secure_form_module'] : '';
$target = WB_PATH.'/framework/SecureForm'.$target.'.php';

$SingleTabStatus = intval($checked==false);
$MultitabStatus = intval($checked==true);
$NotFoundClass = '';
if(!file_exists($MultitabTarget)) {
	$SingleTabStatus = true;
	$MultitabStatus = false;
	$FileNotFound = $SFS_TEXT['FILE_FORMTAB_NOT_GOUND'];
	$NotFoundClass = 'class="warning"';
} else {
}

// convert settings name to upper
array_walk($setting,'converttoupper', array(&$search, &$replace ));

$tpl->set_var($replace);
$tpl->set_var(array(
	'FTAN' => $admin->getFTAN(),
	'SERVER_REQUEST_URI' => $_SERVER['REQUEST_URI'],
	'TEXT_CANCEL' => $TEXT['CANCEL'],
	'BACKLINK' => (isset($_POST['cancel'])) ? $backlink : '#',
	'TEXT_INFO' => $SFS_TEXT['INFO'],
	'TEXT_SUBMIT' => $SFS_TEXT['SUBMIT'],
	'TEXT_MSUBMIT' => $SFS_TEXT['RESET_SETTINGS'],
	'TXT_HEADING' => $SFS_TEXT['SECURE_FORM'.strtoupper($setting['secure_form_module'])],
	'SELECTED' => ( ($SingleTabStatus) ? ' checked="checked"' : ''),
	'SELECTED_TAB' => ( ($MultitabStatus) ? ' checked="checked"' : ''),
	'SUBMIT_TYPE' => ($checked ? 'multitab' : 'singletab'),
	'MSELECTED' => '',
	'MSELECTED_TAB' => '',
	'FTAN_COLOR' => ($checked ? 'grey' : 'norm'),
	'TXT_SUBMIT_FORM' => $SFS_TEXT['SUBMIT_FORM'],
	'TXT_SUBMIT_FORMTAB' => $SFS_TEXT['SUBMIT_FORMTAB'],
	'FILE_FORMTAB_WARNING' => $NotFoundClass,
	'FILE_FORMTAB_NOT_GOUND' => $FileNotFound,
	)
);

$tpl->set_var(array(
		'USEIP_SELECTED' => '',
		'TXT_SECFORM_USEIP' => $SFS_TEXT['WB_SECFORM_USEIP'],
        'TXT_SECFORM_USEIP_TOOLTIP' => $SFS_TEXT['WB_SECFORM_USEIP_TOOLTIP'], // Tooltip
		'TEXT_DEFAULT_SETTINGS' => $HEADING['DEFAULT_SETTINGS'],
		'USEIP_DEFAULT' => $default_cfg['fingerprint_with_ip_octets'],
		'USEFP_CHECKED_TRUE' => (($setting['wb_secform_usefp']=='true') ? ' checked="checked"' : ''),
		'USEFP_CHECKED_FALSE' => (($setting['wb_secform_usefp']=='false') ? ' checked="checked"' : ''),
		'TEXT_DEFAULT_SETTINGS' => $HEADING['DEFAULT_SETTINGS'],
	)
);

$tpl->set_block('main_block', 'useip_mtab_loop', 'mtab_loop');
	for($x=0; $x < 5; $x++) {
		// iu value == default set first option with standardtext
		if(intval($default_cfg['fingerprint_with_ip_octets'])==$x ) {
			$tpl->set_var(array(
					'USEIP_VALUE' => $x,
					'USEIP_DEFAULT_SELECTED' => ((intval($setting['fingerprint_with_ip_octets'])==$x) ? ' selected="selected"' : ''),
					'USEIP_SELECTED' => '',
					)
			);
		} else {
			$tpl->set_var(array(
					'USEIP_VALUE' => $x,
					'USEIP_SELECTED' => ((intval($setting['fingerprint_with_ip_octets'])==$x) && (intval($setting['fingerprint_with_ip_octets'])!=intval($default_cfg['fingerprint_with_ip_octets'])) ? ' selected="selected"' : ''),
				)
			);
		}
		$tpl->parse('mtab_loop','useip_mtab_loop', true);
	}

$tpl->set_block('main_block', 'show_mtab_block', 'show_mtab');
$tpl->set_block('main_block', 'mtab_block', 'mtab');
if($checked) {
	$tpl->set_var(array(
			'TEXT_ENABLED' => $SFS_TEXT['ON_OFF'],
			'TXT_SECFORM_TOKENNAME' => $SFS_TEXT['WB_SECFORM_TOKENNAME'],
            'TXT_SECFORM_TOKENNAME_TOOLTIP' => $SFS_TEXT['WB_SECFORM_TOKENNAME_TOOLTIP'],
			'TXT_SECFORM_TIMEOUT' => $SFS_TEXT['WB_SECFORM_TIMEOUT'],
            'TXT_SECFORM_TIMEOUT_TOOLTIP' => $SFS_TEXT['WB_SECFORM_TIMEOUT_TOOLTIP'],
			'TXT_SECFORM_SECRETTIME' => $SFS_TEXT['WB_SECFORM_SECRETTIME'],
            'TXT_SECFORM_SECRETTIME_TOOLTIP' => $SFS_TEXT['WB_SECFORM_SECRETTIME_TOOLTIP'],
			'TXT_SECFORM_SECRET' => $SFS_TEXT['WB_SECFORM_SECRET'],
            'TXT_SECFORM_SECRET_TOOLTIP' => $SFS_TEXT['WB_SECFORM_SECRET_TOOLTIP'],
			'TXT_SECFORM_USEFP' => $SFS_TEXT['WB_SECFORM_USEFP'],
			'SECFORM_USEFP' => 'true',
            'TXT_SECFORM_USEFP_TOOLTIP' => $SFS_TEXT['WB_SECFORM_USEFP_TOOLTIP'],
		)
	);
	$tpl->parse('mtab','mtab_block', true);
	$tpl->parse('show_mtab','show_mtab_block', true);
} else  {
	$tpl->parse('mtab', '');
	$tpl->parse('show_mtab', '');
}

// Parse template object
$tpl->parse('main', 'main_block', false);
$output = $tpl->finish($tpl->parse('output', 'page'));
unset($tpl);
print $output;

