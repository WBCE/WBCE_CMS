<?php
/**
 * WebsiteBaker CMS Functions
 *
 * functions.php
 * 
 * 
 * @platform    CMS WebsiteBaker 2.8.x
 * @package     WB CMS Functions by Stefek
 * @author      Christian M. Stefan (Stefek)
 * @copyright   Christian M. Stefan
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */

// prevent this file from being accessed directly
if(!defined('WB_PATH')) exit("Cannot access this file directly ".__FILE__);

if(!function_exists('pagesArray')){
	
	/**
	 * Get the full array of all WebsiteBaker CMS Pages at once.
	 *
	 * <p>This function will return the Array of all WB Pages and
	 * you can then decide how to process this array further
	 * usually it will be processed by the template engine TWIG
	 * but any other TE or even plain PHP may be used</p>
	 *
	 * constants used:
	 *	WB internal constants: TABLE_PREFIX, PAGE_TRASH, PAGE_EXTENSION, PAGES_DIRECTORY, MANAGE_SECTIONS
	 *  other constants: REWRITE_URL (optional; for optional use of rewrite_url)
	 *
	 * @author    Christian M. Stefan (Stefek)
	 * @license   http://www.gnu.org/licenses/gpl-2.0.html
	 * @global    $database
	 * @global    $admin
	 * @param     bool ( set wheather or not description and keywords should be used)
	 * @return    array
	 **/
	function pagesArray($bProcessSeo = FALSE) {
		global $database, $admin;
		// prepare SQL Query, build the query string first
		$sUseTrash = (PAGE_TRASH != 'inline') ? " WHERE `visibility` <> 'deleted'" : '';
		$sProcessSeo = ($bProcessSeo == TRUE) ? ', p.`description`, p.`keywords` ' : ' ';	
		$sProcessRewriteUrl = '';
		$bRewriteUrlExists = FALSE;
		if(defined("REWRITE_URL") == TRUE && REWRITE_URL != ''){
			$oCheckDbTable = $database->query("SHOW COLUMNS FROM `".TABLE_PREFIX."pages` LIKE '".REWRITE_URL."'");
			$bRewriteUrlExists = $oCheckDbTable->numRows() > 0 ? TRUE : FALSE;
			$sProcessRewriteUrl = ($bRewriteUrlExists == TRUE) ? ' p.`'.REWRITE_URL.'`,' : '';	
		}
		$sQuery = 'SELECT  s.`module`, MAX(s.`publ_start` + s.`publ_end`) published, p.`link`, '
		     .        '(SELECT MAX(`position`) FROM `'. TABLE_PREFIX .'pages` WHERE `parent`=p.`parent`) siblings, '
		     .        'p.`position`, p.`page_id`, p.`parent`, p.`level`, p.`language`, p.`admin_groups`, '
		     .    (($bRewriteUrlExists == TRUE ) ? 'p.`'.REWRITE_URL.'`, ' : '' ) .''
		     .        'p.`admin_users`, p.`viewing_groups`, p.`viewing_users`, p.`visibility`, '
		     .        'p.`menu_title`, p.`page_title`, p.`page_trail`'.$sProcessSeo.''		            
		     . 'FROM `'. TABLE_PREFIX .'pages` p '
		     .    'INNER JOIN `'. TABLE_PREFIX .'sections` s '
		     .    'ON p.`page_id`=s.`page_id` '
		     . $sUseTrash.' '
		     . 'GROUP BY p.`page_id` '
		     . 'ORDER BY p.`position` ASC';
			 
		$oPages = $database->query($sQuery);
		
		$aPages = array();
		$refs = array();	
		// create $aPages[] Array
		while($page = $oPages->fetchRow(MYSQL_ASSOC)) {
			$thisref = &$refs[ $page['page_id'] ];
			$thisref['page_id']        = $page['page_id'];
			$thisref['pageIDKEY']      = (method_exists($admin, "getIDKEY")) ? $admin->getIDKEY($page['page_id']) : $page['page_id'];
			$thisref['root_parent']    = isset( $page['root_parent'] ) ? $page['root_parent'] : ''; // is this 'root_parent' needed at all?
			$thisref['language']       = $page['language'];
			$thisref['siblings']       = $page['siblings'];
			$thisref['position']       = $page['position'];
			$thisref['parent']         = $page['parent'];
			$thisref['menu_title']     = $page['menu_title'];
			$thisref['page_title']     = $page['page_title'];
			$thisref['level']          = $page['level'];
			$thisref['visibility']     = $page['visibility'];
			$thisref['admin_groups']   = $page['admin_groups'];
			$thisref['admin_users']    = $page['admin_users'];
			$thisref['position']       = $page['position'];
			if($bProcessSeo == TRUE) {				
				$thisref['description']  = $page['description'];
				$thisref['keywords']     = $page['keywords'];
			}
			if(($bRewriteUrlExists == TRUE)) {				
				$thisref[REWRITE_URL]  = $page[REWRITE_URL];
			}
			$thisref['frontend_link']  = PAGES_DIRECTORY . $page['link'] . PAGE_EXTENSION;
			$thisref['modify_page_url']= '../pages/modify.php?page_id='.$page['page_id'];	

			// Admin Groups (get user permissions)
			$admin_groups = explode(',', str_replace('_', '', $page['admin_groups']));
			$admin_users = explode(',', str_replace('_', '', $page['admin_users']));
			$in_group = FALSE;
			foreach($admin->get_groups_id() as $cur_gid) {
				if (in_array($cur_gid, $admin_groups)) 	$in_group = TRUE;
			}			
			// check modify permissions
			$thisref['can_modify'] = FALSE;
			if(($in_group) || is_numeric(array_search($admin->get_user_id(), $admin_users))){				
				if($page['visibility'] == 'deleted'){
					$thisref['can_modify'] = (PAGE_TRASH == 'inline') ? TRUE : FALSE;	
				} elseif ($page['visibility'] != 'deleted') {	
					$thisref['can_modify'] = TRUE; 
				} 
			} else { 
				if ($page['visibility'] == 'private') {	
					continue; 
				} else { 
					$thisref['can_modify'] = FALSE; 
				}
			}
			$thisref['canManageSections'] = (MANAGE_SECTIONS == 'enabled' && $admin->get_permission('pages_modify') == TRUE && $thisref['can_modify'] == TRUE) ? TRUE : FALSE;
			$thisref['page_movable']       = ($admin->get_permission('pages_settings') == TRUE && $thisref['can_modify'] == TRUE) ? TRUE : FALSE;
			$thisref['page_movable']       = ($thisref['page_movable'] && $thisref['siblings'] != 1) ? TRUE : FALSE;
			$thisref['canMoveUP']          = ($thisref['page_movable'] && $thisref['position'] != 1) ? TRUE : FALSE;
			$thisref['canMoveDOWN']        = ($thisref['page_movable'] && $thisref['position'] != $thisref['siblings']) ? TRUE : FALSE;	
			$thisref['canDeleteAndModify'] = ($admin->get_permission('pages_delete') == TRUE && $thisref['can_modify'] == TRUE) ? TRUE : FALSE;
			$thisref['canAddChild']        = ($admin->get_permission('pages_add')) == (TRUE && $thisref['can_modify'] == TRUE) && ($thisref['visibility'] != 'deleted') ? TRUE : FALSE;
			$thisref['canModifyPage']      = ($admin->get_permission('pages_modify') == TRUE && $thisref['can_modify'] == TRUE) ? TRUE : FALSE;
			$thisref['canModifySettings']  = ($admin->get_permission('pages_settings') == TRUE && $thisref['can_modify'] == TRUE) ? TRUE : FALSE;	
			$thisref['modifySettingsURL']  = '../pages/settings.php?page_id='.$page['page_id'];
			$thisref['restoreURL']         = '../pages/restore.php?page_id='.$page['page_id'];
			
			# sectionICON
			$thisref['sectionICON'] = "noclock_16.png";
			if($page['published'] != 0){
				$thisref['sectionICON'] = ($admin->page_is_active($thisref)) ? "clock_16.png" : "clock_red_16.png";
			}		
			if($page['module'] == 'menu_link') {
				$thisref['sectionICON'] =  "menu_link_16.png";
				$thisref['menu_link'] =  TRUE;
			}	
			if ($thisref['canManageSections'] == TRUE){ 
				$thisref['sectionsURL'] = '../pages/sections.php?page_id='.$thisref['page_id'];
			}		
			
			if ($page['parent'] == 0) {	
				$aPages[ $page['page_id'] ] = &$thisref;				
			} else {
				$refs[ $page['parent'] ]['children'][ $page['page_id'] ] = &$thisref;
			}
			unset($page);
		}
		return $aPages;
	}
}
if(!function_exists('debug_dump')){
	/**
	 * This is a simple function to show var_dump or print_r output
	 * in a predefined wrapper
	 *
	 * @author    Christian M. Stefan (Stefek)
	 * @license   http://www.gnu.org/licenses/gpl-2.0.html
	 * @param     mixed (string | array)
	 * @param     string
	 * @param     bool
	 *
	 * @return    string
	 **/
	function debug_dump($mVar = '', $sHeading='', $bShowWithVarDump = false){
		echo '<pre style="background: lightyellow; padding:6px; margin:4px; border: 1px dotted red;">';
			if('' != $sHeading){
				echo '<b style="color: blue;">'.$sHeading.':</b><hr />';
			}
			if($bShowWithVarDump){
				if(is_array($mVar)){
					var_dump($mVar);
				}elseif(!is_array($mVar) && '' != $mVar){
					var_dump($mVar);
				}else{		
					echo '<i>~ (empty) ~</i>';
				}
			}else{
				if(is_array($mVar)){
					print_r($mVar);
				}elseif(!is_array($mVar) && '' != $mVar){
					echo($mVar);
				}else{		
					echo '<i>~ (empty) ~</i>';
				}
			}
		echo '</pre>';	
	}
}


if (!function_exists('get_language_array')) 
{	
	/**
	 * This experimental function will return a multilingual array
	 *
	 * @author    Christian M. Stefan (Stefek)
	 * @license   http://www.gnu.org/licenses/gpl-2.0.html
	 *
	 * @return    array
	 **/
	 
	 // (EXPERIMENTAL) THIS FUNCTION AIN'T USED AS OF THIS VERSION AND MAY NEVER BE.
	function get_language_array() 
	{
		$aLanguage = array();
		$sLoc = dirname(__FILE__) . '/languages';
		if(is_readable($sLoc . '/EN.ini'))
			$aLanguage = parse_ini_file($sLoc . '/EN.ini');
		if(is_readable($sLoc . '/EN_custom.ini'))
			$aLanguage = array_merge($aLanguage, parse_ini_file($sLoc . '/EN_custom.ini'));
		if(LANGUAGE != 'EN'){
			if(is_readable($sLoc . '/'.LANGUAGE.'.ini'))
				$aLanguage = array_merge($aLanguage, parse_ini_file($sLoc . '/'.LANGUAGE.'.ini'));
			if(is_readable($sLoc . '/'.LANGUAGE.'_custom.ini'))
				$aLanguage = array_merge($aLanguage, parse_ini_file($sLoc . '/'.LANGUAGE.'_custom.ini'));
		}	
		return $aLanguage;
	}
}