<?php
/**
 *
 * @category        frontend
 * @package         search
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2011, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: search.php 1442 2011-04-15 19:44:20Z Luisehahne $
 * @filesource		$HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/search/search.php $
 * @lastmodified    $Date: 2011-04-15 21:44:20 +0200 (Fr, 15. Apr 2011) $
 *
 */

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { die("Cannot access this file directly"); }

// Check if search is enabled
if(SHOW_SEARCH != true) {
	echo $TEXT['SEARCH'].' '.$TEXT['DISABLED'];
	return;
}

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Get search settings
$table=TABLE_PREFIX.'search';
$query = $database->query("SELECT value FROM $table WHERE name = 'header' LIMIT 1");
$fetch_header = $query->fetchRow();
$query = $database->query("SELECT value FROM $table WHERE name = 'footer' LIMIT 1");
$fetch_footer = $query->fetchRow();
$query = $database->query("SELECT value FROM $table WHERE name = 'results_header' LIMIT 1");
$fetch_results_header = $query->fetchRow();
$query = $database->query("SELECT value FROM $table WHERE name = 'results_footer' LIMIT 1");
$fetch_results_footer = $query->fetchRow();
$query = $database->query("SELECT value FROM $table WHERE name = 'results_loop' LIMIT 1");
$fetch_results_loop = $query->fetchRow();
$query = $database->query("SELECT value FROM $table WHERE name = 'no_results' LIMIT 1");
$fetch_no_results = $query->fetchRow();
$query = $database->query("SELECT value FROM $table WHERE name = 'module_order' LIMIT 1");
if($query->numRows() > 0) { $res = $query->fetchRow(); } else { $res['value']='faqbaker,manual,wysiwyg'; }
$search_module_order = $res['value'];
$query = $database->query("SELECT value FROM $table WHERE name = 'max_excerpt' LIMIT 1");
if($query->numRows() > 0) { $res = $query->fetchRow(); } else { $res['value'] = '15'; }
$search_max_excerpt = (int)($res['value']);
if(!is_numeric($search_max_excerpt)) { $search_max_excerpt = 15; }
$query = $database->query("SELECT value FROM $table WHERE name = 'cfg_show_description' LIMIT 1");
if($query->numRows() > 0) { $res = $query->fetchRow(); } else { $res['value'] = 'true'; }
if($res['value'] == 'false') { $cfg_show_description = false; } else { $cfg_show_description = true; }
$query = $database->query("SELECT value FROM $table WHERE name = 'cfg_search_description' LIMIT 1");
if($query->numRows() > 0) { $res = $query->fetchRow(); } else { $res['value'] = 'true'; }
if($res['value'] == 'false') { $cfg_search_description = false; } else { $cfg_search_description = true; }
$query = $database->query("SELECT value FROM $table WHERE name = 'cfg_search_keywords' LIMIT 1");
if($query->numRows() > 0) { $res = $query->fetchRow(); } else { $res['value'] = 'true'; }
if($res['value'] == 'false') { $cfg_search_keywords = false; } else { $cfg_search_keywords = true; }
$query = $database->query("SELECT value FROM $table WHERE name = 'cfg_enable_old_search' LIMIT 1");
if($query->numRows() > 0) { $res = $query->fetchRow(); } else { $res['value'] = 'true'; }
if($res['value'] == 'false') { $cfg_enable_old_search = false; } else { $cfg_enable_old_search = true; }
$query = $database->query("SELECT value FROM $table WHERE name = 'cfg_enable_flush' LIMIT 1");
if($query->numRows() > 0) { $res = $query->fetchRow(); } else { $res['value'] = 'false'; }
if($res['value'] == 'false') { $cfg_enable_flush = false; } else { $cfg_enable_flush = true; }
$query = $database->query("SELECT value FROM $table WHERE name = 'time_limit' LIMIT 1"); // time-limit per module
if($query->numRows() > 0) { $res = $query->fetchRow(); } else { $res['value'] = '0'; }
$search_time_limit = (int)($res['value']);
if($search_time_limit < 1) $search_time_limit = 0;

// search-module-extension: get helper-functions
require_once(WB_PATH.'/search/search_modext.php');
// search-module-extension: Get "search.php" for each module, if present
// looks in modules/module/ and modules/module_searchext/
$search_funcs = array();$search_funcs['__before'] = array();$search_funcs['__after'] = array();
$query = $database->query("SELECT DISTINCT directory FROM ".TABLE_PREFIX."addons WHERE type = 'module' AND directory NOT LIKE '%_searchext'");
if($query->numRows() > 0) {
	while($module = $query->fetchRow()) {
		$file = WB_PATH.'/modules/'.$module['directory'].'/search.php';
		if(!file_exists($file)) {
			$file = WB_PATH.'/modules/'.$module['directory'].'_searchext/search.php';
			if(!file_exists($file)) {
				$file='';
			}
		}
		if($file!='') {
			include_once($file);
			if(function_exists($module['directory']."_search")) {
				$search_funcs[$module['directory']] = $module['directory']."_search";
			}
			if(function_exists($module['directory']."_search_before")) {
				$search_funcs['__before'][] = $module['directory']."_search_before";
			}
			if(function_exists($module['directory']."_search_after")) {
				$search_funcs['__after'][] = $module['directory']."_search_after";
			}
		}
	}
}

// Get list of usernames and display names
$query = $database->query("SELECT user_id,username,display_name FROM ".TABLE_PREFIX."users");
$users = array('0' => array('display_name' => $TEXT['UNKNOWN'], 'username' => strtolower($TEXT['UNKNOWN'])));
if($query->numRows() > 0) {
	while($user = $query->fetchRow()) {
		$users[$user['user_id']] = array('display_name' => $user['display_name'], 'username' => $user['username']);
	}
}

// Get search language, used for special umlaut handling (DE: ß=ss, ...)
$search_lang = '';
if(isset($_REQUEST['search_lang'])) {
	$search_lang = $_REQUEST['search_lang'];
	if(!preg_match('~^[A-Z]{2}$~', $search_lang))
		$search_lang = LANGUAGE;
} else {
	$search_lang = LANGUAGE;
}

// Get the path to search into. Normally left blank
// ATTN: since wb2.7.1 the path is evaluated as SQL: LIKE "/path%" - which will find "/path.php", "/path/info.php", ...; But not "/de/path.php"
// Add a '%' in front of each path to get SQL: LIKE "%/path%"
/* possible values:
 * - a single path: "/en/" - search only pages whose link contains 'path' ("/en/machinery/bender-x09")
 * - a single path not to search into: "-/help" - search all, exclude /help...
 * - a bunch of alternative pathes: "/en/,%/machinery/,/docs/" - alternatives paths, seperated by comma
 * - a bunch of paths to exclude: "-/about,%/info,/jp/,/light" - search all, exclude these.
 * These different styles can't be mixed.
 */
// ATTN: in wb2.7.0 "/en/" matched all links with "/en/" somewhere in the link: "/info/en/intro.php", "/en/info.php", ...
// since wb2.7.1 "/en/" matches only links _starting_  with "/en/": "/en/intro/info.php"
// use "%/en/" (or "%/en/, %/info", ...) to get the old behavior
$search_path_SQL = '';
$search_path = '';
if(isset($_REQUEST['search_path'])) {
	$search_path = addslashes(htmlspecialchars(strip_tags($wb->strip_slashes($_REQUEST['search_path'])), ENT_QUOTES));
	if(!preg_match('~^%?[-a-zA-Z0-9_,/ ]+$~', $search_path))
		$search_path = '';
	if($search_path != '') {
		$search_path_SQL = 'AND ( ';
		$not = '';
		$op = 'OR';
		if($search_path[0] == '-') {
			$not = 'NOT';
			$op = 'AND';
			$paths = explode(',', substr($search_path, 1) );
		} else {
			$paths = explode(',',$search_path);
		}
		$i=0;
		foreach($paths as $p) {
			if($i++ > 0) {
				$search_path_SQL .= ' $op';
			}
			$search_path_SQL .= " link $not LIKE '".$p."%'";			
		}
		$search_path_SQL .= ' )';
	}
}

// use page_languages?
if(PAGE_LANGUAGES) {
	$table = TABLE_PREFIX."pages";
	$search_language_SQL_t = "AND $table.`language` = '".LANGUAGE."'";
	$search_language_SQL = "AND `language` = '".LANGUAGE."'";
} else {
	$search_language_SQL_t = '';
	$search_language_SQL = '';
}

// Get the search type
$match = '';
if(isset($_REQUEST['match'])) {
	if($_REQUEST['match']=='any') $match = 'any';
	elseif($_REQUEST['match']=='all') $match = 'all';
	elseif($_REQUEST['match']=='exact') $match = 'exact';
	else $match = 'all';
} else {
	$match = 'all';
}

// Get search string
$search_normal_string = '';
$search_entities_string = ''; // for SQL's LIKE
$search_display_string = ''; // for displaying
$search_url_string = ''; // for $_GET -- ATTN: unquoted! Will become urldecoded later
$string = '';
if(isset($_REQUEST['string'])) {
	if($match!='exact') { // $string will be cleaned below 
		$string=str_replace(',', '', $_REQUEST['string']);
	} else {
		$string=$_REQUEST['string'];
	}
    // redo possible magic quotes
    $string = $wb->strip_slashes($string);
    $string = preg_replace('/\s+/', ' ', $string);
    $string = trim($string);
	// remove some bad chars
	$string = str_replace ( array('[[',']]'),'', $string);
	$string = preg_replace('/(^|\s+)[|.]+(?=\s+|$)/', '', $string);
	$search_display_string = htmlspecialchars($string);
	// convert string to utf-8
	$string = entities_to_umlauts($string, 'UTF-8');
	$search_url_string = $string;
	$search_entities_string = addslashes(htmlentities($string, ENT_COMPAT, 'UTF-8'));
	// mySQL needs four backslashes to match one in LIKE comparisons)
	$search_entities_string = str_replace('\\\\', '\\\\\\\\', $search_entities_string);
	$string = preg_quote($string);
	// quote ' " and /  -we need quoted / for regex
	$search_normal_string = str_replace(array('\'','"','/'), array('\\\'','\"','\/'), $string);
}
// make arrays from the search_..._strings above
if($match == 'exact')
	$search_url_array[] = $search_url_string;
else
	$search_url_array = explode(' ', $search_url_string);
$search_normal_array = array();
$search_entities_array = array();
if($match == 'exact') {
	$search_normal_array[]=$search_normal_string;
	$search_entities_array[]=$search_entities_string;
} else {
	$exploded_string = explode(' ', $search_normal_string);
	// Make sure there is no blank values in the array
	foreach($exploded_string AS $each_exploded_string) {
		if($each_exploded_string != '') {
			$search_normal_array[] = $each_exploded_string;
		}
	}
	$exploded_string = explode(' ', $search_entities_string);
	// Make sure there is no blank values in the array
	foreach($exploded_string AS $each_exploded_string) {
		if($each_exploded_string != '') {
			$search_entities_array[] = $each_exploded_string;
		}
	}
}
// make an extra copy of search_normal_array for use in regex
$search_words = array();
require_once(WB_PATH.'/search/search_convert.php');
global $search_table_umlauts_local;
require_once(WB_PATH.'/search/search_convert_ul.php');
global $search_table_ul_umlauts;
foreach($search_normal_array AS $str) {
	$str = strtr($str, $search_table_umlauts_local);
	$str = strtr($str, $search_table_ul_umlauts);
	$search_words[] = $str;
}

// Work-out what to do (match all words, any words, or do exact match), and do relevant with query settings
$all_checked = '';
$any_checked = '';
$exact_checked = '';
if ($match == 'any') {
	$any_checked = ' checked="checked"';
	$logical_operator = ' OR';
} elseif($match == 'all') {
	$all_checked = ' checked="checked"';
	$logical_operator = ' AND';
} else {
	$exact_checked = ' checked="checked"';
}

// Replace vars in search settings with values
$vars = array('[SEARCH_STRING]', '[WB_URL]', '[PAGE_EXTENSION]', '[TEXT_RESULTS_FOR]');
$values = array($search_display_string, WB_URL, PAGE_EXTENSION, $TEXT['RESULTS_FOR']);
$search_footer = str_replace($vars, $values, ($fetch_footer['value']));
$search_results_header = str_replace($vars, $values, ($fetch_results_header['value']));
$search_results_footer = str_replace($vars, $values, ($fetch_results_footer['value']));

// Do extra vars/values replacement
$vars = array('[SEARCH_STRING]', '[WB_URL]', '[PAGE_EXTENSION]', '[TEXT_SEARCH]', '[TEXT_ALL_WORDS]', '[TEXT_ANY_WORDS]', '[TEXT_EXACT_MATCH]', '[TEXT_MATCH]', '[TEXT_MATCHING]', '[ALL_CHECKED]', '[ANY_CHECKED]', '[EXACT_CHECKED]', '[REFERRER_ID]', '[SEARCH_PATH]');
$values = array($search_display_string, WB_URL, PAGE_EXTENSION, $TEXT['SEARCH'], $TEXT['ALL_WORDS'], $TEXT['ANY_WORDS'], $TEXT['EXACT_MATCH'], $TEXT['MATCH'], $TEXT['MATCHING'], $all_checked, $any_checked, $exact_checked, REFERRER_ID, $search_path);
$search_header = str_replace($vars, $values, ($fetch_header['value']));
$vars = array('[TEXT_NO_RESULTS]');
$values = array($TEXT['NO_RESULTS']);
$search_no_results = str_replace($vars, $values, ($fetch_no_results['value']));

/*
 * Start of output
 */

// Show search header
echo $search_header;
// Show search results_header
echo $search_results_header;

// Work-out if the user has already entered their details or not
if($search_normal_string != '') {

	// Get modules
	$table = TABLE_PREFIX."sections";
	$get_modules = $database->query("SELECT DISTINCT module FROM $table WHERE module != '' ");
	$modules = array();
	if($get_modules->numRows() > 0) {
		while($module = $get_modules->fetchRow()) {
			$modules[] = $module['module'];
		}
	}
	// sort module search-order
	// get the modules from $search_module_order first ...
	$sorted_modules = array();
	$m = count($modules);
	$search_modules = explode(',', $search_module_order);
	foreach($search_modules AS $item) {
		$item = trim($item);
		for($i=0; $i < $m; $i++) {
			if(isset($modules[$i]) && $modules[$i] == $item) {
				$sorted_modules[] = $modules[$i];
				unset($modules[$i]);
				break;
			}
		}
	}
	// ... then add the rest
	foreach($modules AS $item) {
		$sorted_modules[] = $item;
	}


	// Use the module's search-extensions.
	// This is somewhat slower than the orginial method.
	// call $search_funcs['__before'] first
	$search_func_vars = array(
		'database' => $database, // database-handle
		'page_id' => 0,
		'section_id' => 0,
		'page_title' => '',
		'page_menu_title' => '',
		'page_description' => '',
		'page_keywords' => '',
		'page_link' => '',
		'page_modified_when' => 0,
		'page_modified_by' => 0,
		'users' => $users, // array of known user-id/user-name
		'search_words' => $search_words, // array of strings, prepared for regex
		'search_match' => $match, // match-type
		'search_url_array' => $search_url_array, // array of strings from the original search-string. ATTN: strings are not quoted!
		'search_entities_array' => $search_entities_array, // entities
		'results_loop_string' => $fetch_results_loop['value'],
		'default_max_excerpt' => $search_max_excerpt,
		'time_limit' => $search_time_limit, // time-limit in secs
		'search_path' => $search_path // see docu
	);
	foreach($search_funcs['__before'] as $func) {
		$uf_res = call_user_func($func, $search_func_vars);
	}
	// now call module-based $search_funcs[]
	$seen_pages = array(); // seen pages per module.
	$pages_listed = array(); // seen pages.
	if($search_max_excerpt!=0) { // skip this search if $search_max_excerpt==0
		foreach($sorted_modules AS $module_name) {
			$start_time = time();	// get start-time to check time-limit; not very accurate, but ok
			$seen_pages[$module_name] = array();
			if(!isset($search_funcs[$module_name])) {
				continue; // there is no search_func for this module
			}
			// get each section for $module_name
			$table_s = TABLE_PREFIX."sections";	
			$table_p = TABLE_PREFIX."pages";
			$sections_query = $database->query("
				SELECT s.section_id, s.page_id, s.module, s.publ_start, s.publ_end,
							 p.page_title, p.menu_title, p.link, p.description, p.keywords, p.modified_when, p.modified_by,
							 p.visibility, p.viewing_groups, p.viewing_users
				FROM $table_s AS s INNER JOIN $table_p AS p ON s.page_id = p.page_id
				WHERE s.module = '$module_name' AND p.visibility NOT IN ('none','deleted') AND p.searching = '1' $search_path_SQL $search_language_SQL
				ORDER BY s.page_id, s.position ASC
			");
			if($sections_query->numRows() > 0) {
				while($res = $sections_query->fetchRow()) {
					// check if time-limit is exceeded for this module
					if($search_time_limit > 0 && (time()-$start_time > $search_time_limit)) {
						break;
					}
					// Only show this section if it is not "out of publication-date"
					$now = time();
					if( !( $now<$res['publ_end'] && ($now>$res['publ_start'] || $res['publ_start']==0) ||
						$now>$res['publ_start'] && $res['publ_end']==0) ) {
						continue;
					}
					$search_func_vars = array(
						'database' => $database,
						'page_id' => $res['page_id'],
						'section_id' => $res['section_id'],
						'page_title' => $res['page_title'],
						'page_menu_title' => $res['menu_title'],
						'page_description' => ($cfg_show_description?$res['description']:""),
						'page_keywords' => $res['keywords'],
						'page_link' => $res['link'],
						'page_modified_when' => $res['modified_when'],
						'page_modified_by' => $res['modified_by'],
						'users' => $users,
						'search_words' => $search_words, // needed for preg_match
						'search_match' => $match,
						'search_url_array' => $search_url_array, // needed for url-string only
						'search_entities_array' => $search_entities_array, // entities
						'results_loop_string' => $fetch_results_loop['value'],
						'default_max_excerpt' => $search_max_excerpt,
						'enable_flush' => $cfg_enable_flush,
						'time_limit' => $search_time_limit // time-limit in secs
					);
					// Only show this page if we are allowed to see it
					if($admin->page_is_visible($res) == false) {
						if($res['visibility'] == 'registered') { // don't show excerpt
							$search_func_vars['default_max_excerpt'] = 0;
							$search_func_vars['page_description'] = $TEXT['REGISTERED'];
						} else { // private
							continue;
						}
					}
					$uf_res = call_user_func($search_funcs[$module_name], $search_func_vars);
					if($uf_res) {
						$pages_listed[$res['page_id']] = true;
						$seen_pages[$module_name][$res['page_id']] = true;
					} else {
						$seen_pages[$module_name][$res['page_id']] = true;
					}
				}
			}
		}
	}
	// now call $search_funcs['__after']
	$search_func_vars = array(
		'database' => $database, // database-handle
		'page_id' => 0,
		'section_id' => 0,
		'page_title' => '',
		'page_menu_title' => '',
		'page_description' => '',
		'page_keywords' => '',
		'page_link' => '',
		'page_modified_when' => 0,
		'page_modified_by' => 0,
		'users' => $users, // array of known user-id/user-name
		'search_words' => $search_words, // array of strings, prepared for regex
		'search_match' => $match, // match-type
		'search_url_array' => $search_url_array, // array of strings from the original search-string. ATTN: strings are not quoted!
		'search_entities_array' => $search_entities_array, // entities
		'results_loop_string' => $fetch_results_loop['value'],
		'default_max_excerpt' => $search_max_excerpt,
		'time_limit' => $search_time_limit, // time-limit in secs
		'search_path' => $search_path // see docu
	);
	foreach($search_funcs['__after'] as $func) {
		$uf_res = call_user_func($func, $search_func_vars);
	}


	// Search page details only, such as description, keywords, etc, but only of unseen pages.
	$max_excerpt_num = 0; // we don't want excerpt here
	$divider = ".";
	$table = TABLE_PREFIX."pages";
	$query_pages = $database->query("
		SELECT page_id, page_title, menu_title, link, description, keywords, modified_when, modified_by,
		       visibility, viewing_groups, viewing_users
		FROM $table
		WHERE visibility NOT IN ('none','deleted') AND searching = '1' $search_path_SQL $search_language_SQL
	");
	if($query_pages->numRows() > 0) {
		while($page = $query_pages->fetchRow()) {
			if (isset($pages_listed[$page['page_id']])) {
				continue;
			}
			$func_vars = array(
				'database' => $database,
				'page_id' => $page['page_id'],
				'page_title' => $page['page_title'],
				'page_menu_title' => $page['menu_title'],
				'page_description' => ($cfg_show_description?$page['description']:""),
				'page_keywords' => $page['keywords'],
				'page_link' => $page['link'],
				'page_modified_when' => $page['modified_when'],
				'page_modified_by' => $page['modified_by'],
				'users' => $users,
				'search_words' => $search_words, // needed for preg_match_all
				'search_match' => $match,
				'search_url_array' => $search_url_array, // needed for url-string only
				'search_entities_array' => $search_entities_array, // entities
				'results_loop_string' => $fetch_results_loop['value'],
				'default_max_excerpt' => $max_excerpt_num,
				'enable_flush' => $cfg_enable_flush
			);
			// Only show this page if we are allowed to see it
			if($admin->page_is_visible($page) == false) {
				if($page['visibility'] != 'registered') {
					continue;
				} else { // page: registered, user: access denied
					$func_vars['page_description'] = $TEXT['REGISTERED'];
				}
			}
			if($admin->page_is_active($page) == false) {
				continue;
			}
			$text = $func_vars['page_title'].$divider
				.$func_vars['page_menu_title'].$divider
				.($cfg_search_description?$func_vars['page_description']:"").$divider
				.($cfg_search_keywords?$func_vars['page_keywords']:"").$divider;
			$mod_vars = array(
				'page_link' => $func_vars['page_link'],
				'page_link_target' => "",
				'page_title' => $func_vars['page_title'],
				'page_description' => $func_vars['page_description'],
				'page_modified_when' => $func_vars['page_modified_when'],
				'page_modified_by' => $func_vars['page_modified_by'],
				'text' => $text,
				'max_excerpt_num' => $func_vars['default_max_excerpt']
			);
			if(print_excerpt2($mod_vars, $func_vars)) {
				$pages_listed[$page['page_id']] = true;
			}
		}
	}

	// Now use the old method for pages not displayed by the new method above
	// in case someone has old modules without search.php.

	// Get modules
	$table_search = TABLE_PREFIX."search";
	$table_sections = TABLE_PREFIX."sections";
	$get_modules = $database->query("
		SELECT DISTINCT s.value, s.extra
		FROM $table_search AS s INNER JOIN $table_sections AS sec
			ON s.value = sec.module
		WHERE s.name = 'module'
	");
	$modules = array();
	if($get_modules->numRows() > 0) {
		while($module = $get_modules->fetchRow()) {
			$modules[] = $module; // $modules in an array of arrays
		}
	}
	// sort module search-order
	// get the modules from $search_module_order first ...
	$sorted_modules = array();
	$m = count($modules);
	$search_modules = explode(',', $search_module_order);
	foreach($search_modules AS $item) {
		$item = trim($item);
		for($i=0; $i < $m; $i++) {
			if(isset($modules[$i]) && $modules[$i]['value'] == $item) {
				$sorted_modules[] = $modules[$i];
				unset($modules[$i]);
				break;
			}
		}
	}
	// ... then add the rest
	foreach($modules AS $item) {
		$sorted_modules[] = $item;
	}

	if($cfg_enable_old_search) { // this is the old (wb <= 2.6.7) search-function
		$search_path_SQL = str_replace(' link ', ' '.TABLE_PREFIX.'pages.link ', $search_path_SQL);
		foreach($sorted_modules AS $module) {
			if(isset($seen_pages[$module['value']]) && count($seen_pages[$module['value']])>0) // skip modules handled by new search-func
				continue;
			$query_start = '';
			$query_body = '';
			$query_end = '';
			$prepared_query = '';
			// Get module name
			$module_name = $module['value'];
			if(!isset($seen_pages[$module_name])) {
				$seen_pages[$module_name]=array();
			}
			// skip module 'code' - it doesn't make sense to search in a code section
			if($module_name=="code")
				continue;
			// Get fields to use for title, link, etc.
			$fields = unserialize($module['extra']);
			// Get query start
			$get_query_start = $database->query("SELECT value FROM ".TABLE_PREFIX."search WHERE name = 'query_start' AND extra = '$module_name' LIMIT 1");
			if($get_query_start->numRows() > 0) {
				// Fetch query start
				$fetch_query_start = $get_query_start->fetchRow();
				// Prepare query start for execution by replacing {TP} with the TABLE_PREFIX
				$query_start = str_replace('[TP]', TABLE_PREFIX, ($fetch_query_start['value']));
			}
			// Get query end
			$get_query_end = $database->query("SELECT value FROM ".TABLE_PREFIX."search WHERE name = 'query_end' AND extra = '$module_name' LIMIT 1");
			if($get_query_end->numRows() > 0) {
				// Fetch query end
				$fetch_query_end = $get_query_end->fetchRow();
				// Set query end
				$query_end = ($fetch_query_end['value']);
			}
			// Get query body
			$get_query_body = $database->query("SELECT value FROM ".TABLE_PREFIX."search WHERE name = 'query_body' AND extra = '$module_name' LIMIT 1");
			if($get_query_body->numRows() > 0) {
				// Fetch query body
				$fetch_query_body = $get_query_body->fetchRow();
				// Prepare query body for execution by replacing {STRING} with the correct one
				$query_body = str_replace(array('[TP]','[O]','[W]'), array(TABLE_PREFIX,'LIKE','%'), ($fetch_query_body['value']));
				// Loop through query body for each string, then combine with start and end
				$prepared_query = $query_start." ( ( ( ";
				$count = 0;
				foreach($search_normal_array AS $string) {
					if($count != 0) {
						$prepared_query .= " ) ".$logical_operator." ( ";
					}
					$prepared_query .= str_replace('[STRING]', $string, $query_body);
					$count = $count+1;
				}
				$count=0;
				$prepared_query .= ' ) ) OR ( ( ';
				foreach($search_entities_array AS $string) {
					if($count != 0) {
						$prepared_query .= " ) ".$logical_operator." ( ";
					}
					$prepared_query .= str_replace('[STRING]', $string, $query_body);
					$count = $count+1;
				}
				$prepared_query .= " ) ) ) ".$query_end;
				// Execute query
				$page_query = $database->query($prepared_query." ".$search_path_SQL." ".$search_language_SQL_t);
				if(!$page_query) continue; // on error, skip the rest of the current loop iteration
				// Loop through queried items
				if($page_query->numRows() > 0) {
					while($page = $page_query->fetchRow()) {
						// Only show this page if it hasn't already been listed
						if(isset($seen_pages[$module_name][$page['page_id']]) || isset($pages_listed[$page['page_id']])) {
							continue;
						}
						
						// don't list pages with visibility == none|deleted and check if user is allowed to see the page
						$p_table = TABLE_PREFIX."pages";
						$viewquery = $database->query("
							SELECT visibility, viewing_groups, viewing_users
							FROM $p_table
							WHERE page_id='{$page['page_id']}'
						");
						$visibility = 'none'; $viewing_groups="" ; $viewing_users="";
						if($viewquery->numRows() > 0) {
							if($res = $viewquery->fetchRow()) {
								$visibility = $res['visibility'];
								$viewing_groups = $res['viewing_groups'];
								$viewing_users = $res['viewing_users'];
								if($visibility == 'deleted' || $visibility == 'none') {
									continue;
								}
								if($visibility == 'private') {
									if($admin->page_is_visible(array(
										'page_id'=>$page[$fields['page_id']],
										'visibility' =>$visibility,
										'viewing_groups'=>$viewing_groups,
										'viewing_users'=>$viewing_users
									)) == false) {
										continue;
									}
								}
								if($admin->page_is_active(array('page_id'=>$page[$fields['page_id']]))==false) {
									continue;
								}
							}
						}
	
						// Get page link
						$link = page_link($page['link']);
						// Add search string for highlighting
						if ($match!='exact') {
							$sstring = implode(" ", $search_normal_array);
							$link = $link."?searchresult=1&amp;sstring=".urlencode($sstring);
						} else {
							$sstring = str_replace(" ", "_",$search_normal_array[0]);
							$link = $link."?searchresult=2&amp;sstring=".urlencode($sstring);
						}
						// Set vars to be replaced by values
						if(!isset($page['description'])) { $page['description'] = ""; }
						if(!isset($page['modified_when'])) { $page['modified_when'] = 0; }
						if(!isset($page['modified_by'])) { $page['modified_by'] = 0; }
						$vars = array('[LINK]', '[TITLE]', '[DESCRIPTION]', '[USERNAME]','[DISPLAY_NAME]','[DATE]','[TIME]','[TEXT_LAST_UPDATED_BY]','[TEXT_ON]','[EXCERPT]');
						if($page['modified_when'] > 0) {
							$date = gmdate(DATE_FORMAT, $page['modified_when']+TIMEZONE);
							$time = gmdate(TIME_FORMAT, $page['modified_when']+TIMEZONE);
						} else {
							$date = $TEXT['UNKNOWN'].' '.$TEXT['DATE'];
							$time = $TEXT['UNKNOWN'].' '.$TEXT['TIME'];
						}
						$excerpt="";
						if($cfg_show_description == 0) {
							$page['description'] = "";
						}
						$values = array($link, $page['page_title'], $page['description'], $users[$page['modified_by']]['username'], $users[$page['modified_by']]['display_name'], $date, $time, $TEXT['LAST_UPDATED_BY'], strtolower($TEXT['ON']), $excerpt);
						// Show loop code with vars replaced by values
						echo str_replace($vars, $values, ($fetch_results_loop['value']));
						// Say that this page has been listed
						$seen_pages[$module_name][$page['page_id']] = true;
						$pages_listed[$page['page_id']] = true;
					}
				}
			}
		}
	}

	// Say no items found if we should
	if(count($pages_listed) == 0) {
		echo $search_no_results;
	}
} else {
	echo $search_no_results;
}

// Show search results_footer
echo $search_results_footer;
// Show search footer
echo $search_footer;

?>
