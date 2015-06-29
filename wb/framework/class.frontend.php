<?php
/**
 *
 * @category        framework
 * @package         frontend
 * @author          Ryan Djurovich, WebsiteBaker Project
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: class.frontend.php 1598 2012-02-06 15:32:03Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/framework/class.frontend.php $
 * @lastmodified    $Date: 2012-02-06 16:32:03 +0100 (Mo, 06. Feb 2012) $
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
//require_once(WB_PATH.'/framework/SecureForm.php');

class frontend extends wb {
	// defaults
	public $default_link,$default_page_id;
	// when multiple blocks are used, show home page blocks on
	// pages where no content is defined (search, login, ...)
	public $default_block_content=true;

	// page details
	// page database row
	public $page;
	public $page_id,$page_title,$menu_title,$parent,$root_parent,$level,$position,$visibility;
	public $page_description,$page_keywords,$page_link;
	public $page_trail=array();
	
	public $page_access_denied;
	public $page_no_active_sections;
	
	// website settings
	public $website_title,$website_description,$website_keywords,$website_header,$website_footer;

	// ugly database stuff
	public $extra_where_sql, $sql_where_language;
	
	public function __construct() {
		parent::__construct(SecureForm::FRONTEND);
	}

	public function page_select() {
		global $page_id,$no_intro;
		global $database;
		// We have no page id and are supposed to show the intro page
		if((INTRO_PAGE AND !isset($no_intro)) AND (!isset($page_id) OR !is_numeric($page_id))) {
			// Since we have no page id check if we should go to intro page or default page
			// Get intro page content
			$filename = WB_PATH.PAGES_DIRECTORY.'/intro'.PAGE_EXTENSION;
			if(file_exists($filename)) {
				$handle = @fopen($filename, "r");
				$content = @fread($handle, filesize($filename));
				@fclose($handle);
				$this->preprocess($content);
				header("Location: ".WB_URL.PAGES_DIRECTORY."/intro".PAGE_EXTENSION."");   // send intro.php as header to allow parsing of php statements
				echo ($content);
				return false;
			}
		}
		// Check if we should add page language sql code
		if(PAGE_LANGUAGES) {
			$this->sql_where_language = ' AND `language`=\''.LANGUAGE.'\'';
		}
		// Get default page
		// Check for a page id
		$table_p = TABLE_PREFIX.'pages';
		$table_s = TABLE_PREFIX.'sections';
		$now = time();
		$sql  = 'SELECT `p`.`page_id`, `link` ';
		$sql .= 'FROM `'.$table_p.'` AS `p` INNER JOIN `'.$table_s.'` USING(`page_id`) ';
		$sql .= 'WHERE `parent`=0 AND `visibility`=\'public\' ';
		$sql .=     'AND (('.$now.'>=`publ_start` OR `publ_start`=0) ';
		$sql .=     'AND ('.$now.'<=`publ_end` OR `publ_end`=0)) ';
		if(trim($this->sql_where_language) != '') {
			$sql .= trim($this->sql_where_language).' ';
		}
		$sql .= 'ORDER BY `p`.`position` ASC';
		$get_default = $database->query($sql);
		$default_num_rows = $get_default->numRows();
		if(!isset($page_id) OR !is_numeric($page_id)){
			// Go to or show default page
			if($default_num_rows > 0) {
				$fetch_default = $get_default->fetchRow();
				$this->default_link = $fetch_default['link'];
				$this->default_page_id = $fetch_default['page_id'];
				// Check if we should redirect or include page inline
				if(HOMEPAGE_REDIRECTION) {
					// Redirect to page
					header("Location: ".$this->page_link($this->default_link));
					exit();
				} else {
					// Include page inline
					$this->page_id = $this->default_page_id;
				}
			} else {
		   		// No pages have been added, so print under construction page
				$this->print_under_construction();
				exit();
			}
		} else {
			$this->page_id=$page_id;
		}
		// Get default page link
		if(!isset($fetch_default)) {
		  	$fetch_default = $get_default->fetchRow();
	 		$this->default_link = $fetch_default['link'];
			$this->default_page_id = $fetch_default['page_id'];
		}
		return true;
	}

	public function get_page_details() {
		global $database;
	    if($this->page_id != 0) {
			// Query page details
			$sql = 'SELECT * FROM `'.TABLE_PREFIX.'pages` WHERE `page_id`='.(int)$this->page_id;
			$get_page = $database->query($sql);
			// Make sure page was found in database
			if($get_page->numRows() == 0) {
				// Print page not found message
				exit("Page not found");
			}
			// Fetch page details
			$this->page = $get_page->fetchRow();
			// Check if the page language is also the selected language. If not, send headers again.
			if ($this->page['language']!=LANGUAGE) {
				if(isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != '') { // check if there is an query-string
					header('Location: '.$this->page_link($this->page['link']).'?'.$_SERVER['QUERY_STRING'].'&lang='.$this->page['language']);
				} else {
					header('Location: '.$this->page_link($this->page['link']).'?lang='.$this->page['language']);
				}
				exit();
			}
			// Begin code to set details as either variables of constants
			// Page ID
			if(!defined('PAGE_ID')) {define('PAGE_ID', $this->page['page_id']);}
			// Page Title
			if(!defined('PAGE_TITLE')) {define('PAGE_TITLE', $this->page['page_title']);}
			$this->page_title=PAGE_TITLE;
			// Menu Title
			$menu_title = $this->page['menu_title'];
			if($menu_title != '') {
				if(!defined('MENU_TITLE')) {define('MENU_TITLE', $menu_title);}
			} else {
				if(!defined('MENU_TITLE')) {define('MENU_TITLE', PAGE_TITLE);}
			}
			$this->menu_title = MENU_TITLE;
			// Page parent
			if(!defined('PARENT')) {define('PARENT', $this->page['parent']);}
			$this->parent=$this->page['parent'];
			// Page root parent
			if(!defined('ROOT_PARENT')) {define('ROOT_PARENT', $this->page['root_parent']);}
			$this->root_parent=$this->page['root_parent'];
			// Page level
			if(!defined('LEVEL')) {define('LEVEL', $this->page['level']);}
			$this->level=$this->page['level'];
			// Page position
			$this->level=$this->page['position'];
			// Page visibility
			if(!defined('VISIBILITY')) {define('VISIBILITY', $this->page['visibility']);}
			$this->visibility=$this->page['visibility'];
			// Page trail
			foreach(explode(',', $this->page['page_trail']) AS $pid) {
				$this->page_trail[$pid]=$pid;
			}
			// Page description
			$this->page_description=$this->page['description'];
			if($this->page_description != '') {
				define('PAGE_DESCRIPTION', $this->page_description);
			} else {
				define('PAGE_DESCRIPTION', WEBSITE_DESCRIPTION);
			}
			// Page keywords
			$this->page_keywords=$this->page['keywords'];
			// Page link
			$this->link=$this->page_link($this->page['link']);
			$_SESSION['PAGE_ID'] = $this->page_id;
			$_SESSION['HTTP_REFERER'] = $this->link;

		// End code to set details as either variables of constants
		}

		// Figure out what template to use
		if(!defined('TEMPLATE')) {
			if(isset($this->page['template']) AND $this->page['template'] != '') {
				if(file_exists(WB_PATH.'/templates/'.$this->page['template'].'/index.php')) {
					define('TEMPLATE', $this->page['template']);
				} else {
					define('TEMPLATE', DEFAULT_TEMPLATE);
				}
			} else {
				define('TEMPLATE', DEFAULT_TEMPLATE);
			}
		}
		// Set the template dir
		define('TEMPLATE_DIR', WB_URL.'/templates/'.TEMPLATE);

		// Check if user is allowed to view this page
		if($this->page && $this->page_is_visible($this->page) == false) {
			if(VISIBILITY == 'deleted' OR VISIBILITY == 'none') {
				// User isnt allowed on this page so tell them
				$this->page_access_denied=true;
			} elseif(VISIBILITY == 'private' OR VISIBILITY == 'registered') {
				// Check if the user is authenticated
				if($this->is_authenticated() == false) {
					// User needs to login first
					header("Location: ".WB_URL."/account/login.php?redirect=".$this->link);
					exit(0);
				} else {
					// User isnt allowed on this page so tell them
					$this->page_access_denied=true;
				}
				
			}
		}
		// check if there is at least one active section
		if($this->page && $this->page_is_active($this->page) == false) {
			$this->page_no_active_sections=true;
		}
	}

	public function get_website_settings()
    {
		global $database;

		// set visibility SQL code
		// never show no-vis, hidden or deleted pages
		$this->extra_where_sql = '`visibility`!=\'none\' AND `visibility`!=\'hidden\' AND `visibility`!=\'deleted\'';
		// Set extra private sql code
		if($this->is_authenticated()==false) {
			// if user is not authenticated, don't show private pages either
			$this->extra_where_sql .= ' AND `visibility`!=\'private\'';
			// and 'registered' without frontend login doesn't make much sense!
			if (FRONTEND_LOGIN==false) {
				$this->extra_where_sql .= ' AND `visibility`!=\'registered\'';
			}
		}
		$this->extra_where_sql .= $this->sql_where_language;

		// Work-out if any possible in-line search boxes should be shown
		if(SEARCH == 'public') {
			define('SHOW_SEARCH', true);
		} elseif(SEARCH == 'private' AND VISIBILITY == 'private') {
			define('SHOW_SEARCH', true);
		} elseif(SEARCH == 'private' AND $this->is_authenticated() == true) {
			define('SHOW_SEARCH', true);
		} elseif(SEARCH == 'registered' AND $this->is_authenticated() == true) {
			define('SHOW_SEARCH', true);	
		} else {
			define('SHOW_SEARCH', false);
		}
		// Work-out if menu should be shown
		if(!defined('SHOW_MENU')) {
			define('SHOW_MENU', true);
		}
		// Work-out if login menu constants should be set
		if(FRONTEND_LOGIN) {
			// Set login menu constants
			define('LOGIN_URL', WB_URL.'/account/login.php');
			define('LOGOUT_URL', WB_URL.'/account/logout.php');
			define('FORGOT_URL', WB_URL.'/account/forgot.php');
			define('PREFERENCES_URL', WB_URL.'/account/preferences.php');
			define('SIGNUP_URL', WB_URL.'/account/signup.php');
		}
	}

/*
 * replace all "[wblink{page_id}]" with real links
 * @param string &$content : reference to global $content
 * @return void
 * @history 100216 17:00:00 optimise errorhandling, speed, SQL-strict
 */
	public function preprocess(&$content)
	{
		global $database;
		$replace_list = array();
		$pattern = '/\[wblink([0-9]+)\]/isU';
		if(preg_match_all($pattern,$content,$ids))
		{
			foreach($ids[1] as $key => $page_id)
			{
				$replace_list[$page_id] = $ids[0][$key];
			}
			foreach($replace_list as $page_id => $tag)
			{
				$sql = 'SELECT `link` FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.(int)$page_id;
				$link = $database->get_one($sql);
				if(!is_null($link))
				{
					$link = $this->page_link($link);
					$content = str_replace($tag, $link, $content);
				}
			}
		}
	}

/*
	function preprocess(&$content) {
		global $database;
		// Replace [wblink--PAGE_ID--] with real link
		$pattern = '/\[wblink(.+?)\]/s';
		preg_match_all($pattern,$content,$ids);
		foreach($ids[1] AS $page_id) {
			$pattern = '/\[wblink'.$page_id.'\]/s';
			// Get page link
			$get_link = $database->query("SELECT link FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id' LIMIT 1");
			$fetch_link = $get_link->fetchRow();
			$link = $this->page_link($fetch_link['link']);
			$content = preg_replace($pattern,$link,$content);
		}
	}
*/
	public function menu() {
		global $wb;
	   if (!isset($wb->menu_number)) {
	   	$wb->menu_number = 1;
	   }
	   if (!isset($wb->menu_start_level)) {
	   	$wb->menu_start_level = 0;
	   }
	   if (!isset($wb->menu_recurse)) {
	   	$wb->menu_recurse = -1;
	   }
	   if (!isset($wb->menu_collapse)) {
	   	$wb->menu_collapse = true;
	   }
	   if (!isset($wb->menu_item_template)) {
	   	$wb->menu_item_template = '<li><span[class]>[a] [menu_title] [/a]</span>';
	   }
	   if (!isset($wb->menu_item_footer)) {
	   	$wb->menu_item_footer = '</li>';
	   }
	   if (!isset($wb->menu_header)) {
	   	$wb->menu_header = '<ul>';
	   }
	   if (!isset($wb->menu_footer)) {
	   	$wb->menu_footer = '</ul>';
	   }
	   if (!isset($wb->menu_default_class)) {
	   	$wb->menu_default_class = ' class="menu_default"';
	   }
	   if (!isset($wb->menu_current_class)) {
	   	$wb->menu_current_class = ' class="menu_current"';
	   }
	   if (!isset($wb->menu_parent)) {
	   	$wb->menu_parent = 0;
	   }
	   $wb->show_menu();
	}
	
	public function show_menu() {
		global $database;
		if ($this->menu_start_level>0) {
			$key_array=array_keys($this->page_trail);
			if (isset($key_array[$this->menu_start_level-1])) {
				$real_start=$key_array[$this->menu_start_level-1];
				$this->menu_parent=$real_start;
				$this->menu_start_level=0;
			} else {
				return;
			}
		}
		if ($this->menu_recurse==0)
	       return;
		// Check if we should add menu number check to query
		if($this->menu_parent == 0) {
			$menu_number = '`menu`='.intval($this->menu_number);
		} else {
			$menu_number = '1';
		}
		// Query pages
		$sql  = 'SELECT `page_id`,`menu_title`,`page_title`,`link`,`target`,`level`,';
		$sql .=        '`visibility`,viewing_groups,viewing_users ';
		$sql .= 'FROM `'.TABLE_PREFIX.'pages` ';
		$sql .= 'WHERE `parent`='.(int)$this->menu_parent.' AND '.$menu_number.' AND '.$this->extra_where_sql.' ';
		$sql .= 'ORDER BY `position` ASC';
		$query_menu = $database->query($sql);
		// Check if there are any pages to show
		if($query_menu->numRows() > 0) {
			// Print menu header
			echo "\n".$this->menu_header;
			// Loop through pages
			while($page = $query_menu->fetchRow()) {
				// check whether to show this menu-link
				if($this->page_is_active($page)==false && $page['link']!=$this->default_link && !INTRO_PAGE) {
					continue; // no active sections
				}
				if($this->page_is_visible($page)==false) {
					if($page['visibility'] != 'registered') // special case: page_to_visible() check wheter to show the page contents, but the menu should be visible allways
						continue;
				}
				// Create vars
				$vars = array('[class]','[a]', '[/a]', '[menu_title]', '[page_title]');
				// Work-out class
				if($page['page_id'] == PAGE_ID) {
					$class = $this->menu_current_class;
				} else {
					$class = $this->menu_default_class;
				}
				// Check if link is same as first page link, and if so change to WB URL
				if($page['link'] == $this->default_link AND !INTRO_PAGE) {
					$link = WB_URL;
				} else {
					$link = $this->page_link($page['link']);
				}
				// Create values
				$values = array($class,'<a href="'.$link.'" target="'.$page['target'].'" '.$class.'>', '</a>', $page['menu_title'], $page['page_title']);
				// Replace vars with value and print
				echo "\n".str_replace($vars, $values, $this->menu_item_template);
				// Generate sub-menu
				if($this->menu_collapse==false OR ($this->menu_collapse==true AND isset($this->page_trail[$page['page_id']]))) {
					$this->menu_recurse--;
					$this->menu_parent=$page['page_id'];
					$this->show_menu();
				}
				echo "\n".$this->menu_item_footer;
			}
			// Print menu footer
			echo "\n".$this->menu_footer;
		}
	}


	// Function to show the "Under Construction" page
	public function print_under_construction() {
		global $MESSAGE;
		require_once(WB_PATH.'/languages/'.DEFAULT_LANGUAGE.'.php');
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<head><title>'.$MESSAGE['GENERIC']['WEBSITE_UNDER_CONSTRUCTION'].'</title>
		<style type="text/css"><!-- body{ font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px; background-image: url("'.THEME_URL.'/images/background.png");background-repeat: repeat-x; background-color: #A8BCCB; text-align: center; }
		h1 { margin: 0; padding: 0; font-size: 18px; color: #000; text-transform: uppercase;
}--></style></head><body>
		<br /><h1>'.$MESSAGE['GENERIC']['WEBSITE_UNDER_CONSTRUCTION'].'</h1><br />
		'.$MESSAGE['GENERIC']['PLEASE_CHECK_BACK_SOON'].'</body></html>';
	}
}

?>