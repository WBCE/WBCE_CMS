<?php
/**
 *
 * @category        framewotk
 * @package         backend admin
 * @author          Ryan Djurovich, WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: class.admin.php 1625 2012-02-29 00:50:57Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/framework/class.admin.php $
 * @lastmodified    $Date: 2012-02-29 01:50:57 +0100 (Mi, 29. Feb 2012) $
 *
 */
/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
	require_once(dirname(__FILE__).'/globalExceptionHandler.php');
	throw new IllegalFileException();
}
/* -------------------------------------------------------- */
require_once(WB_PATH.'/framework/class.wb.php');

// Get WB version
require_once(ADMIN_PATH.'/interface/version.php');

// Include EditArea wrapper functions
// require_once(WB_PATH . '/include/editarea/wb_wrapper_edit_area.php');
//require_once(WB_PATH . '/framework/SecureForm.php');


class admin extends wb {
	// Authenticate user then auto print the header
	public function __construct($section_name= '##skip##', $section_permission = 'start', $auto_header = true, $auto_auth = true)
	{
		parent::__construct(SecureForm::BACKEND);
	if( $section_name != '##skip##' )
	{
		global $database, $MESSAGE;
		// Specify the current applications name
		$this->section_name = $section_name;
		$this->section_permission = $section_permission;
		// Authenticate the user for this application
		if($auto_auth == true)
		{
			// First check if the user is logged-in
			if($this->is_authenticated() == false)
			{
				header('Location: '.ADMIN_URL.'/login/index.php');
				exit(0);
			}

			// Now check if they are allowed in this section
			if($this->get_permission($section_permission) == false) {
				die($MESSAGE['ADMIN']['INSUFFICIENT_PRIVELLIGES']);
			}
		}

		// Check if the backend language is also the selected language. If not, send headers again.
		$sql  = 'SELECT `language` FROM `'.TABLE_PREFIX.'users` ';
		$sql .= 'WHERE `user_id`='.(int)$this->get_user_id();
		$get_user_language = @$database->query($sql);
		$user_language = ($get_user_language) ? $get_user_language->fetchRow() : '';
		// prevent infinite loop if language file is not XX.php (e.g. DE_du.php)
		$user_language = substr($user_language[0],0,2);
		// obtain the admin folder (e.g. /admin)
		$admin_folder = str_replace(WB_PATH, '', ADMIN_PATH);
		if((LANGUAGE != $user_language) && file_exists(WB_PATH .'/languages/' .$user_language .'.php')
			&& strpos($_SERVER['PHP_SELF'],$admin_folder.'/') !== false) {
			// check if page_id is set
			$page_id_url = (isset($_GET['page_id'])) ? '&page_id=' .(int) $_GET['page_id'] : '';
			$section_id_url = (isset($_GET['section_id'])) ? '&section_id=' .(int) $_GET['section_id'] : '';
			if(isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != '') { // check if there is an query-string
				header('Location: '.$_SERVER['PHP_SELF'] .'?lang='.$user_language .$page_id_url .$section_id_url.'&'.$_SERVER['QUERY_STRING']);
			} else {
				header('Location: '.$_SERVER['PHP_SELF'] .'?lang='.$user_language .$page_id_url .$section_id_url);
			}
			exit();
		}

		// Auto header code
		if($auto_header == true) {
			$this->print_header();
		}
	}
	}

	// Print the admin header
	function print_header($body_tags = '') {
		// Get vars from the language file
		global $MENU, $MESSAGE, $TEXT, $database;
		// Connect to database and get website title
		// $GLOBALS['FTAN'] = $this->getFTAN();
		$this->createFTAN();
		$sql = 'SELECT `value` FROM `'.TABLE_PREFIX.'settings` '
		     . 'WHERE `name`=\'website_title\'';
		$get_title = $database->query($sql);
		$title = $get_title->fetchRow();
		// Setup template object, parse vars to it, then parse it
		$header_template = new Template(dirname($this->correct_theme_source('header.htt')));
		$header_template->set_file('page', 'header.htt');
		$header_template->set_block('page', 'header_block', 'header');
		if(defined('DEFAULT_CHARSET')) {
			$charset=DEFAULT_CHARSET;
		} else {
			$charset='utf-8';
		}

		// work out the URL for the 'View menu' link in the WB backend
		// if the page_id is set, show this page otherwise show the root directory of WB
		$view_url = WB_URL;
		if(isset($_GET['page_id'])) {
			// extract page link from the database
			$sql = 'SELECT `link` FROM `'.TABLE_PREFIX.'pages` '
			     . 'WHERE `page_id`='.intval($_GET['page_id']);
			$result = @$database->query($sql);
			$row = @$result->fetchRow();
			if($row) $view_url .= PAGES_DIRECTORY .$row['link']. PAGE_EXTENSION;
		}

		$header_template->set_var(	array(
							'SECTION_NAME'        => $MENU[strtoupper($this->section_name)],
							'BODY_TAGS'           => $body_tags,
							'WEBSITE_TITLE'       => ($title['value']),
							'TEXT_ADMINISTRATION' => $TEXT['ADMINISTRATION'],
							'CURRENT_USER'        => $MESSAGE['START_CURRENT_USER'],
							'DISPLAY_NAME'        => $this->get_display_name(),
							'CHARSET'             => $charset,
							'LANGUAGE'            => strtolower(LANGUAGE),
							'VERSION'             => VERSION,
							'SP'                  => (defined('SP') ? SP : ''),
							'REVISION'            => REVISION,
							'SERVER_ADDR'         => ($this->get_user_id() == 1
 							                          ? (!isset($_SERVER['SERVER_ADDR']) 
 							                             ? '129.0.0.1' 
 							                             : $_SERVER['SERVER_ADDR'])
							                          : ''),
							'WB_URL'              => WB_URL,
							'ADMIN_URL'           => ADMIN_URL,
							'THEME_URL'           => THEME_URL,
							'TITLE_START'         => $MENU['START'],
							'TITLE_VIEW'          => $MENU['VIEW'],
							'TITLE_HELP'          => $MENU['HELP'],
							'TITLE_LOGOUT'        =>  $MENU['LOGOUT'],
							'URL_VIEW'            => $view_url,
							'URL_HELP'            => 'http://www.websitebaker2.org/',
							'BACKEND_MODULE_CSS'  => $this->register_backend_modfiles('css'),	// adds backend.css
							'BACKEND_MODULE_JS'   => $this->register_backend_modfiles('js')		// adds backend.js
						)
					);

		// Create the menu
		$menu = array(
					array(ADMIN_URL.'/pages/index.php',       '', $MENU['PAGES'],       'pages',       1),
					array(ADMIN_URL.'/media/index.php',       '', $MENU['MEDIA'],       'media',       1),
					array(ADMIN_URL.'/addons/index.php',      '', $MENU['ADDONS'],      'addons',      1),
					array(ADMIN_URL.'/preferences/index.php', '', $MENU['PREFERENCES'], 'preferences', 0),
					array(ADMIN_URL.'/settings/index.php',    '', $MENU['SETTINGS'],    'settings',    1),
					array(ADMIN_URL.'/admintools/index.php',  '', $MENU['ADMINTOOLS'],  'admintools',  1),
					array(ADMIN_URL.'/access/index.php',      '', $MENU['ACCESS'],      'access',      1)
					);
		$header_template->set_block('header_block', 'linkBlock', 'link');
		foreach($menu AS $menu_item) {
			$link = $menu_item[0];
			$target = ($menu_item[1] == '') ? '_self' : $menu_item[1];
			$title = $menu_item[2];
			$permission_title = $menu_item[3];
			$required = $menu_item[4];
			$replace_old = array(ADMIN_URL, WB_URL, '/', 'index.php');
			if($required == false OR $this->get_link_permission($permission_title)) {
				$header_template->set_var('LINK', $link);
				$header_template->set_var('TARGET', $target);
				// If link is the current section apply a class name
				if($permission_title == strtolower($this->section_name)) {
					$header_template->set_var('CLASS', $menu_item[3] . ' current');
				} else {
					$header_template->set_var('CLASS', $menu_item[3]);
				}
				$header_template->set_var('TITLE', $title);
				// Print link
				$header_template->parse('link', 'linkBlock', true);
			}
		}
		$header_template->parse('header', 'header_block', false);
		$header_template->pparse('output', 'page');
	}
	
	// Print the admin footer
		function print_footer($activateJsAdmin = false) {
		// include the required file for Javascript admin
		if($activateJsAdmin != false) {
			if(file_exists(WB_PATH.'/modules/jsadmin/jsadmin_backend_include.php')){
				@include_once(WB_PATH.'/modules/jsadmin/jsadmin_backend_include.php');
			}
		}

		// Setup template object, parse vars to it, then parse it
		$footer_template = new Template(dirname($this->correct_theme_source('footer.htt')));
		$footer_template->set_file('page', 'footer.htt');
		$footer_template->set_block('page', 'footer_block', 'header');
		$footer_template->set_var(array(
						'BACKEND_BODY_MODULE_JS' => $this->register_backend_modfiles_body('js'),
						'WB_URL' => WB_URL,
						'ADMIN_URL' => ADMIN_URL,
						'THEME_URL' => THEME_URL,
			 ) );
		$footer_template->parse('header', 'footer_block', false);
		$footer_template->pparse('output', 'page');
	}
	
	// Return a system permission
	function get_permission($name, $type = 'system') {
		// Append to permission type
		$type .= '_permissions';
		// Check if we have a section to check for
		if($name == 'start') {
			return true;
		} else {
			// Set system permissions var
			$system_permissions = $this->get_session('SYSTEM_PERMISSIONS');
			// Set module permissions var
			$module_permissions = $this->get_session('MODULE_PERMISSIONS');
			// Set template permissions var
			$template_permissions = $this->get_session('TEMPLATE_PERMISSIONS');
			// Return true if system perm = 1
			if (isset($$type) && is_array($$type) && is_numeric(array_search($name, $$type))) {
				if($type == 'system_permissions') {
					return true;
				} else {
					return false;
				}
			} else {
				if($type == 'system_permissions') {
					return false;
				} else {
					return true;
				}
			}
		}
	}
/*
	function get_user_details($user_id) {
		global $database;
		$sql  = 'SELECT * FROM `'.TABLE_PREFIX.'users` ';
		$sql .= 'WHERE `user_id`='.(int)$user_id.' LIMIT 1';
		if(($resUser = $database->query($sql))){
			if(!($recUser = $resUser->fetchRow())) {
				$recUser['display_name'] = 'Unknown';
				$recUser['username'] = 'unknown';
			}
		}
		return $recUser;
	}
*/
 function get_user_details($user_id) {
  global $database;
  $retval = array('username'=>'unknown','display_name'=>'Unknown','email'=>'');
  $sql  = 'SELECT `username`,`display_name`,`email` ';
  $sql .= 'FROM `'.TABLE_PREFIX.'users` ';
  $sql .= 'WHERE `user_id`='.(int)$user_id;
  if( ($resUsers = $database->query($sql)) ) {
   if( ($recUser = $resUsers->fetchRow()) ) {
    $retval = $recUser;
   }
  }
  return $retval;
 }

    //
	function get_section_details( $section_id, $backLink = 'index.php' ) {
	global $database, $TEXT;
		$sql  = 'SELECT * FROM `'.TABLE_PREFIX.'sections` ';
		$sql .= 'WHERE `section_id`='.intval($section_id);
		if(($resSection = $database->query($sql))){
			if(!($recSection = $resSection->fetchRow())) {
				$this->print_header();
				$this->print_error($TEXT['SECTION'].' '.$TEXT['NOT_FOUND'], $backLink, true);
			}
			} else {
				$this->print_header();
				$this->print_error($database->get_error(), $backLink, true);
			}
		return $recSection;
	}

	function get_page_details( $page_id, $backLink = 'index.php' ) {
		global $database, $TEXT;
		$sql  = 'SELECT * FROM `'.TABLE_PREFIX.'pages` ';
		$sql .= 'WHERE `page_id`='.intval($page_id);
		if(($resPages = $database->query($sql))){
			if(!($recPage = $resPages->fetchRow())) {
			$this->print_header();
			$this->print_error($TEXT['PAGE'].' '.$TEXT['NOT_FOUND'], $backLink, true);
			}
		} else {
			$this->print_header();
			$this->print_error($database->get_error(), $backLink, true);
		}
		return $recPage;
	}

	function get_page_permission($page,$action='admin') {
		if($action != 'viewing') { $action = 'admin'; }
		$action_groups = $action.'_groups';
		$action_users  = $action.'_users';
		$groups = $users = '0';
		if(is_array($page)) {
			$groups = $page[$action_groups];
			$users  = $page[$action_users];
		} else {
			global $database;
			$sql  = 'SELECT `'.$action_groups.'`,`'.$action_users.'` ';
			$sql .= 'FROM `'.TABLE_PREFIX.'pages` ';
			$sql .= 'WHERE `page_id`='.(int)$page;
			if( ($res = $database->query($sql)) ) {
				if( ($rec = $res->fetchRow()) ) {
					$groups = $rec[$action_groups];
					$users  = $rec[$action_users];
				}
			}
		}
		return ($this->ami_group_member($groups) || $this->is_group_match($this->get_user_id(), $users));
	}

	// Returns a system permission for a menu link
	function get_link_permission($title) {
		$title = str_replace('_blank', '', $title);
		$title = strtolower($title);
		// Set system permissions var
		$system_permissions = $this->get_session('SYSTEM_PERMISSIONS');
		// Set module permissions var
		$module_permissions = $this->get_session('MODULE_PERMISSIONS');
		if($title == 'start') {
			return true;
		} else {
			// Return true if system perm = 1
			if(is_numeric(array_search($title, $system_permissions))) {
				return true;
			} else {
				return false;
			}
		}
	}

	// Function to add optional module Javascript or CSS stylesheets into the <body> section of the backend
	function register_backend_modfiles_body($file_id="js")
		{
		// sanity check of parameter passed to the function
		$file_id = strtolower($file_id);
		if($file_id !== "javascript" && $file_id !== "js")
		{
			return;
		}
		global $database;
        $body_links = "";
		// define default baselink and filename for optional module javascript and stylesheet files
		if($file_id == "js") {
			$base_link = '<script src="'.WB_URL.'/modules/{MODULE_DIRECTORY}/backend_body.js" type="text/javascript"></script>';
			$base_file = "backend_body.js";
		}
		// check if backend_body.js files needs to be included to the <body></body> section of the backend
		if(isset($_GET['tool']))
			{
			// check if displayed page contains a installed admin tool
			$sql  = 'SELECT * FROM `'.TABLE_PREFIX.'addons` ';
			$sql .= 'WHERE `type`=\'module\' AND `function`=\'tool\' AND `directory`=\''.addslashes($_GET['tool']).'\'';
			$result = $database->query($sql);
			if($result->numRows())
				{
				// check if admin tool directory contains a backend_body.js file to include
				$tool = $result->fetchRow();
				if(file_exists(WB_PATH ."/modules/" .$tool['directory'] ."/$base_file"))
				{
					// return link to the backend_body.js file
					return str_replace("{MODULE_DIRECTORY}", $tool['directory'], $base_link);
				}
			}
		} elseif(isset($_GET['page_id']) or isset($_POST['page_id']))
		{
			// check if displayed page in the backend contains a page module
			if (isset($_GET['page_id']))
			{
				$page_id = (int) addslashes($_GET['page_id']);
			} else {
				$page_id = (int) addslashes($_POST['page_id']);
			}
			// gather information for all models embedded on actual page
			$sql = 'SELECT `module` FROM `'.TABLE_PREFIX.'sections` WHERE `page_id`='.(int)$page_id;
			$query_modules = $database->query($sql);
			while($row = $query_modules->fetchRow()) {
				// check if page module directory contains a backend_body.js file
				if(file_exists(WB_PATH ."/modules/" .$row['module'] ."/$base_file")) {
					// create link with backend_body.js source for the current module
					$tmp_link = str_replace("{MODULE_DIRECTORY}", $row['module'], $base_link);
					// ensure that backend_body.js is only added once per module type
					if(strpos($body_links, $tmp_link) === false) {
						$body_links .= $tmp_link ."\n";
					}
				}
			}
			// write out links with all external module javascript/CSS files, remove last line feed
			return rtrim($body_links);
		}
	}


	// Function to add optional module Javascript or CSS stylesheets into the <head> section of the backend
	function register_backend_modfiles($file_id="css") {
		// sanity check of parameter passed to the function
		$file_id = strtolower($file_id);
		if($file_id !== "css" && $file_id !== "javascript" && $file_id !== "js") {
			return;
		}

		global $database;
		// define default baselink and filename for optional module javascript and stylesheet files
		$head_links = "";
		if($file_id == "css") {
      	$base_link = '<link href="'.WB_URL.'/modules/{MODULE_DIRECTORY}/backend.css"';
			$base_link.= ' rel="stylesheet" type="text/css" media="screen" />';
			$base_file = "backend.css";
		} else {
			$base_link = '<script src="'.WB_URL.'/modules/{MODULE_DIRECTORY}/backend.js" type="text/javascript"></script>';
			$base_file = "backend.js";
		}

		// check if backend.js or backend.css files needs to be included to the <head></head> section of the backend
		if(isset($_GET['tool'])) {
			// check if displayed page contains a installed admin tool
			$sql  = 'SELECT * FROM `'.TABLE_PREFIX.'addons` ';
			$sql .= 'WHERE `type`=\'module\' AND `function`=\'tool\' AND `directory`=\''.addslashes($_GET['tool']).'\'';
			$result = $database->query($sql);
			if($result->numRows()) {
				// check if admin tool directory contains a backend.js or backend.css file to include
				$tool = $result->fetchRow();
				if(file_exists(WB_PATH ."/modules/" .$tool['directory'] ."/$base_file")) {
        			// return link to the backend.js or backend.css file
					return str_replace("{MODULE_DIRECTORY}", $tool['directory'], $base_link);
				}
			}
		} elseif(isset($_GET['page_id']) || isset($_POST['page_id'])) {
			// check if displayed page in the backend contains a page module
			if (isset($_GET['page_id'])) {
				$page_id = (int)$_GET['page_id'];
			} else {
				$page_id = (int)$_POST['page_id'];
			}

    		// gather information for all models embedded on actual page
			$sql = 'SELECT `module` FROM `'.TABLE_PREFIX.'sections` WHERE `page_id`='.(int)$page_id;
			$query_modules = $database->query($sql);

    		while($row = $query_modules->fetchRow()) {
				// check if page module directory contains a backend.js or backend.css file
      		if(file_exists(WB_PATH ."/modules/" .$row['module'] ."/$base_file")) {
					// create link with backend.js or backend.css source for the current module
					$tmp_link = str_replace("{MODULE_DIRECTORY}", $row['module'], $base_link);
        			// ensure that backend.js or backend.css is only added once per module type
        			if(strpos($head_links, $tmp_link) === false) {
						$head_links .= $tmp_link ."\n";
					}
				}
    		}
    		// write out links with all external module javascript/CSS files, remove last line feed
			return rtrim($head_links);
		}
	}
}

?>
