<?php

// $Id: tool.php 591 2009-03-01 19:42:05Z BerndJM $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2008, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/



// prevent this file from being accessed directly
defined('WB_PATH') OR die(header('Location: ../../index.php'));


require_once(WB_PATH . '/framework/module.functions.php');


// include jscalendar-setup
$jscal_use_time = true; // whether to use a clock, too
require_once(WB_PATH."/include/jscalendar/wb-setup.php");
require_once(WB_PATH."/include/jscalendar/jscalendar-functions.php");

/**
 * Include Website Baker template parser and configure it
 */
// include template class and initiate object (set template folder: "./htt")
require_once(WB_PATH . '/include/phplib/template.inc');
$tpl = new Template(dirname(__FILE__) . '/htt');

// configure handling of unknown {variables} (remove:=default, keep, comment)
$tpl->set_unknowns('remove');

// configure debug mode (0:= default, 1:=variable assignments, 2:=calls to get variable, 4:=show internals)
$tpl->debug = 0;

if (!isset($_POST['search'])) { 
	// Show usersearch form
	
	// set template file (assign file "backend_view.htt" to variable/handle "page")
	$tpl->set_file('page', 'usersearch_form.htt');
	
	// set blocks (NOTE: always start with the inner most block first)
	// parameters: file variable/handle of file containing the block, block name in the file, new variable/handle for the block)
	$tpl->set_block('page', 'groupsearch_block','groupsearch_block_handle');	

	// System variables 
	// Think to compare WB_URL and ADMIN_URL	
	$tpl->set_var('WB_URL', WB_URL);
	$tpl->set_var('ADMIN_URL', ADMIN_URL);
	$tpl->set_var('FORM_VALID_URL', $_SERVER['REQUEST_URI']);
	$tpl->set_var('JSCAL_IFFORMAT', $jscal_ifformat);
	$tpl->set_var('JSCAL_FIRSTDAY', $jscal_firstday);
	$tpl->set_var('JSCAL_TODAY', $jscal_today);	
	$tpl->set_var('CALENDAR', $MOD_USER_SEARCH['CALENDAR']);
	$tpl->set_var('ERASE_DATE', $MOD_USER_SEARCH['ERASE_DATE']);
	
	// try $_PHP_SELF if REQUEST_URI don't work

	$tpl->set_var('SUBMIT_ALERT', $MOD_USER_SEARCH['SUBMIT_ALERT']);
	$tpl->set_var('SUBMIT_TERM_ALERT', $MOD_USER_SEARCH['SUBMIT_TERM_ALERT']);
	$tpl->set_var('HEADING', $MOD_USER_SEARCH['HEADING']);
	$tpl->set_var('HOWTO', $MOD_USER_SEARCH['HOWTO']);
	$tpl->set_var('SEARCH_ITEM', $MOD_USER_SEARCH['SEARCH_ITEM']);
	$tpl->set_var('SEARCH_HELP', $MOD_USER_SEARCH['SEARCH_HELP']);
	$tpl->set_var('USE_WILDCARD', $MOD_USER_SEARCH['USE_WILDCARD']);	
	$tpl->set_var('SEARCH_IN', $MOD_USER_SEARCH['SEARCH_IN']);
	$tpl->set_var('USER_NAME', $MOD_USER_SEARCH['USER_NAME']);
	$tpl->set_var('REAL_NAME', $MOD_USER_SEARCH['REAL_NAME']);
	$tpl->set_var('EMAIL', $MOD_USER_SEARCH['EMAIL']);
	$tpl->set_var('LAST_LOGIN', $MOD_USER_SEARCH['LAST_LOGIN']);
	$tpl->set_var('REF_DATE', $MOD_USER_SEARCH['REF_DATE']);
	$tpl->set_var('USE_CALENDAR', $MOD_USER_SEARCH['USE_CALENDAR']);
	$tpl->set_var('REF_DATE_LAST_LOGIN', $MOD_USER_SEARCH['REF_DATE_LAST_LOGIN']);
	$tpl->set_var('REF_DATE_AFTER', $MOD_USER_SEARCH['REF_DATE_AFTER']);
	$tpl->set_var('REF_DATE_BEFORE', $MOD_USER_SEARCH['REF_DATE_BEFORE']);
	$tpl->set_var('SEARCH_GROUPS', $MOD_USER_SEARCH['SEARCH_GROUPS']);
	$tpl->set_var('SEARCH_IN', $MOD_USER_SEARCH['SEARCH_IN']);
	$tpl->set_var('IN_ALL_GROUPS', $MOD_USER_SEARCH['IN_ALL_GROUPS']);
	$tpl->set_var('NO_RESULT', $MOD_USER_SEARCH['NO_RESULT']);
	$tpl->set_var('BUTTON_SEARCH', $MOD_USER_SEARCH['BUTTON_SEARCH']);
	
	// Show all groups

	// access database and obtain groups
	$results = $database->query("SELECT * FROM `".TABLE_PREFIX."groups`"); 
	if ($results && $results->numRows() > 0) {
		while($row = $results->fetchRow()) {
			$tpl->set_var(
			array(	
				'GROUP_ID'			=>$row['group_id'],
				'GROUP_NAME'		=>$row['name']
				)
			);
			// add template values in append mode (add per loop)
			$tpl->parse('groupsearch_block_handle', 'groupsearch_block', true);
		}
	}
} 
else {	
	// Show the result form	
	//initialising the default values
	$wheretosearch = 0;
	$item = "";
	$g_id = "";
	$resultquery = "";
	$displaysearchfield = "";
	
	//xmlstoresearch will contain the query in xml format (to store and load search)
	$xmlstoresearch = "";
	
	// if a search term was entered make the regexp and build the "LIKE" query	
	if ($_POST['begriff']!=""){
		if (isset($_POST['username']))	$wheretosearch += 1; 
		if (isset($_POST['realname']))  $wheretosearch += 2;
		if (isset($_POST['email']))		$wheretosearch += 4;
		
		$item_raw = $admin->add_slashes($admin->get_post('begriff'));
		$item = $database->escapeString($item_raw);
		
		$xmlstoresearch .= "<searchterm>".$item."</searchterm>";
		$item = str_replace("*", '%', $item);
		
	
		switch($wheretosearch) {
			case 1:	
				$resultquery = "(username LIKE '".$item."'"; 
				$xmlstoresearch .= "<username>true</username>";
				$displaysearchfield .= $MOD_USER_SEARCH['USER_NAME'];
				break;
			case 2:	
				$resultquery = "(display_name LIKE '".$item."'"; 
				$xmlstoresearch .= "<realname>true</realname>";
				$displaysearchfield .= $MOD_USER_SEARCH['REAL_NAME'];
				break;
			case 3:	
				$resultquery = "(username LIKE '".$item."' OR display_name LIKE '".$item."'"; 
				$xmlstoresearch .= "<username>true</username><realname>true</realname>";				
				$displaysearchfield .= $MOD_USER_SEARCH['USER_NAME'].", ".$MOD_USER_SEARCH['REAL_NAME'];
				break;
			case 4:	
				$resultquery = "(email LIKE '".$item."'";
				$xmlstoresearch .= "<email>true</email>";
				$displaysearchfield .= $MOD_USER_SEARCH['EMAIL'];								
				break;
			case 5:	
				$resultquery = "(username LIKE '".$item."' OR email LIKE '".$item."'"; 
				$xmlstoresearch .= "<username>true</username><email>true</email>";				
				$displaysearchfield .= $MOD_USER_SEARCH['USER_NAME'].", ".$MOD_USER_SEARCH['EMAIL'];								
				break;
			case 6:	
				$resultquery = "(display_name LIKE '".$item."' OR email LIKE '".$item."'"; 
				$xmlstoresearch .= "<realname>true</realname><email>true</email>";
				$displaysearchfield .= $MOD_USER_SEARCH['REAL_NAME'].", ".$MOD_USER_SEARCH['EMAIL'];								
				break;
			case 7:	
				$resultquery = "(username LIKE '".$item."' OR display_name LIKE '".$item."' OR email LIKE '".$item."'"; 
				$xmlstoresearch .= "<username>true</username><realname>true</realname><email>true</email>";				
				$displaysearchfield .= $MOD_USER_SEARCH['USER_NAME'].", ".$MOD_USER_SEARCH['REAL_NAME'].", ".$MOD_USER_SEARCH['EMAIL'];										break;
			default: 
				$resultquery = "";
		}	
	}
	
	
	// if a date was entered modify the sql query	
	if (isset($_POST['comp_date'])&&($_POST['comp_date']!="")){
		if ($resultquery!="") $resultquery .= ") AND";
		$resultquery .= " (login_when";
		$xmlstoresearch .= "<datelastlogin>";
		if ($_POST['datesearch']=="after") {
			$resultquery .= ">";
			$xmlstoresearch .= "<after>true</after>";				
		}else{ 
			$resultquery .= "<";
			$xmlstoresearch .= "<before>true</before>";
		}
		// convert date to timestamp format to compare
		$resultquery .= $database->escapeString(jscalendar_to_timestamp($_POST['comp_date'],TIMEZONE));
		
		$xmlstoresearch .= "<date>".$database->escapeString(jscalendar_to_timestamp($_POST['comp_date'],TIMEZONE))."</date>";
		$xmlstoresearch .= "</datelastlogin>";
		}
	
	// if a group was choosen modify the sql query
	if (isset($_POST['groups'])&&($_POST['groups']!="-1")) {
		$g_id = $database->escapeString($_POST['groups']);
		if ($resultquery!="") $resultquery .= ") AND";
		$g_id = str_replace(",", '%', $g_id);
		$resultquery .= " (groups_id LIKE '%$g_id%'";
		$xmlstoresearch .= "<idgroup>".$g_id."</idgroup>";
		
		// retrieve the group name
		$group_name = mysql_fetch_assoc(mysql_query ("SELECT name from `".TABLE_PREFIX."groups` WHERE group_id = '$g_id'"));
		$xmlstoresearch .= "<groupname>".$group_name["name"]."</groupname>";
		}
		
	$results = $database->query("SELECT * FROM `".TABLE_PREFIX."users` WHERE ".$resultquery.")");

	// set template file (assign file "backend_view.htt" to variable/handle "page")
	$tpl->set_file('page', 'usersearch_result.htt');
	
	// set blocks (NOTE: always start with the inner most block first)
	// parameters: file variable/handle of file containing the block, block name in the file, new variable/handle for the block)
	$tpl->set_block('page', 'no_result_block','no_result_block_handle');	
	$tpl->set_block('page', 'last_login_block','last_login_block_handle');
	$tpl->set_block('page', 'group_block','group_block_handle');
	$tpl->set_block('page', 'searchterm_block','searchterm_block_handle');

	$tpl->set_block('page', 'result_list_block','result_list_block_handle');	
	$tpl->set_block('page', 'result_table_block','result_table_block_handle');
	
	if ($database->is_error()) { 
		// echo '<p style="color: #CC0000;">'.$database->get_error().'</p>';
	} 
	else {	
		$anzahl = $results->numRows();
		
	$tpl->set_var('HEADING_RESULT', $MOD_USER_SEARCH['HEADING_RESULT']);
	$tpl->set_var('HOWTO_RESULT', $MOD_USER_SEARCH['HOWTO_RESULT']);		
	$tpl->set_var('SEARCH_DETAIL_RESULT', $MOD_USER_SEARCH['SEARCH_DETAIL_RESULT']);

		if ($wheretosearch != 0) {
			// display if a search term was entered
			$tpl->set_var('SEARCH_ITEM_RESULT', $MOD_USER_SEARCH['SEARCH_ITEM_RESULT']);
			$tpl->set_var('BEGRIFF',htmlspecialchars($admin->get_post('begriff'),ENT_QUOTES, "UTF-8"));
			$tpl->set_var('SEARCH_FIELD_RESULT', $MOD_USER_SEARCH['SEARCH_FIELD_RESULT']);
			$tpl->set_var('DISPLAYSEARCHFIELD', $displaysearchfield);
			$tpl->parse('searchterm_block_handle', 'searchterm_block');
			}
		else {	
			// clear the block if not required
			$tpl->set_var('searchterm_block_handle', '');
			}	
	
		if ($g_id != "") {
			// display if a group was choosen
			$tpl->set_var('GROUP_RESULT', $MOD_USER_SEARCH['GROUP_RESULT']);			
			$tpl->set_var('GROUP_NAME', $group_name["name"]);
			$tpl->parse('group_block_handle', 'group_block');
		}
		else {	
			// clear the block if not required
			$tpl->set_var('group_block_handle', '');
			}
		 
		if (isset($_POST['comp_date'])&&($_POST['comp_date']!="")) {
			// display if a date of last login was entered
			$tpl->set_var('LAST_LOGIN', $MOD_USER_SEARCH['LAST_LOGIN']);			
			if ($_POST['datesearch']=="after") $tpl->set_var('BEFORE_OR_AFTER', $MOD_USER_SEARCH['DATE_AFTER_RESULT']);
			else $tpl->set_var('BEFORE_OR_AFTER', $MOD_USER_SEARCH['DATE_BEFORE_RESULT']);
			$tpl->set_var('DATE_RESULT', $_POST['comp_date']);
			$tpl->parse('last_login_block_handle', 'last_login_block');		
		}
		else {	
			// clear the block if not required
			$tpl->set_var('last_login_block', '');
			}
					
		$tpl->set_var('SHOW_RESULT', $MOD_USER_SEARCH['SHOW_RESULT']);

		
		if ($anzahl == 0) {
			// if the search return no values
			$tpl->set_var('NO_RESULT', $MOD_USER_SEARCH['NO_RESULT']);
			$tpl->parse('no_result_block_handle', 'no_result_block');		
			} 
		else 
			{
			// clear the block if not required				
			$tpl->set_var('no_result_block_handle', '');

			$tpl->set_var('USER_NAME', $MOD_USER_SEARCH['USER_NAME']);
			$tpl->set_var('REAL_NAME', $MOD_USER_SEARCH['REAL_NAME']);
			$tpl->set_var('EMAIL', $MOD_USER_SEARCH['EMAIL']);
			$tpl->set_var('LAST_LOGIN', $MOD_USER_SEARCH['LAST_LOGIN_D']);						
			$tpl->set_var('LAST_IP', $MOD_USER_SEARCH['LAST_IP']);
			$rowcnt = 1;
			
			while($d = $results->fetchRow()) {
				
				if ($d['login_when'] == 0) {
					$lastlogin = '-';
					} else {
					$lastlogin = gmdate(DATE_FORMAT." ".TIME_FORMAT, $d['login_when'] + TIMEZONE);
					}
				
				$bgcol = ($rowcnt++ % 2 == 0) ? '#EEE' : '#FFF';
				
				$tpl->set_var('WB_URL', WB_URL);
				$tpl->set_var('ADMIN_URL', ADMIN_URL);
				$tpl->set_var('RESULT_EDIT_USER', $MOD_USER_SEARCH['EDIT_USER']);
				$tpl->set_var('RESULT_ADMIN_DISABLED', $MOD_USER_SEARCH['ADMIN_DISABLED']);
				$tpl->set_var('RESULT_BGCOL', $bgcol);				
				$tpl->set_var('RESULT_USER_ID', $d['user_id']);	
				$tpl->set_var('IDKEY_USER_ID', $admin->getIDKEY($d['user_id']));			
				$tpl->set_var('RESULT_USERNAME', $d['username']);
				$tpl->set_var('RESULT_DISPLAYNAME', $d['display_name']);				
				$tpl->set_var('RESULT_EMAIL', $d['email']);
				$tpl->set_var('RESULT_LASTLOGIN', $lastlogin);
				$tpl->set_var('RESULT_DAYS_INACTIVE', ($d['login_when'] == 0) ? '-' : round((time() - (int) $d['login_when']) / (3600 * 24)));
				$tpl->set_var('RESULT_LAST_IP', $d['login_ip']);


				// add template values in append mode (add per loop)
				$tpl->parse('result_list_block_handle', 'result_list_block', true);					
		
				}
				
			$tpl->set_var('HINT_EDIT', $MOD_USER_SEARCH['HINT_EDIT']);									
			// parse the final template block
			$tpl->parse('result_table_block_handle', 'result_table_block');

			}
			$tpl->set_var('NEW_SEARCH', $MOD_USER_SEARCH['NEW_SEARCH']);	
		}
}
// ouput the final template

$tpl->pparse('output', 'page');

