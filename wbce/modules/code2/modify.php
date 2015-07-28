<?php

/**
 *
 *	@module       code2
 *	@version		  2.1.11
 *	@authors		  Ryan Djurovich, Chio Maisriml, Thorsten Hornik, Dietrich Roland Pehlke, Martin Hecht 
 *	@copyright		Ryan Djurovich, Chio Maisriml, Thorsten Hornik, Dietrich Roland Pehlke, Martin Hecht
 *  @license	    GNU General Public License
 *	@platform		  WebsiteBaker 2.8.x
 *	@requirements	PHP 5.2.x and higher
 *
 */

/**
 *	prevent this file from being accessed directly
 */
if(!defined('WB_PATH')) exit('Direct access to this file is not allowed');

/**
 *	Load Language file
 */
$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );

// Setup template object
$template = new Template(WB_PATH.'/modules/code2');
$template->set_file('page', 'htt/modify.htt');
$template->set_block('page', 'main_block', 'main');

// Get page content
$query = "SELECT `content`, `whatis` FROM `".TABLE_PREFIX."mod_code2` WHERE `section_id`= '".$section_id."'";
$get_content = $database->query($query);
$content = $get_content->fetchRow( MYSQL_ASSOC );
$whatis = (int)$content['whatis'];

$mode = ($whatis >= 10) ? (int)($whatis / 10) : 0;
$whatis = $whatis % 10;

$groups = $admin->get_groups_id();

if ( ( $whatis == 4) AND (!in_array(1, $groups)) ) {
	$content = $content['content'];
	echo '<div class="code2_admin">'.$content.'</div>';
} else {

	/**
	 *	Building the hash-id and store it inside the $_SESSION array.
	 */
	$hash_id = md5( microtime()."just another day of hell" );
	
	$_SESSION['code2_hash'][$section_id] = $hash_id;
	
	/**
	 *	FTAN Addition ...
	 *
	 *
	 */
	if (true === method_exists($admin, 'getFTAN') ) {
		$tan = $admin->getFTAN();
	} else {

		$hash = md5( microtime() );
		$offset = hexdec( $hash[0] );
		$str = substr($hash, $offset, 16);
		$name = substr($hash, 0, ( $offset * -1) );
		$tan = "<input type='hidden' name='".$name."' value='".$str."' />";
	
		if (!isset($_SESSION['old_tan'])) $_SESSION['old_tan'] = array();
		$_SESSION['old_tan'][$section_id] = $hash;
		
		unset($hash);
		unset($offset);
		unset($str);
		unset($name);
	}
	
	$content = htmlspecialchars($content['content']);
	$whatis_types = array('PHP', 'HTML', 'Javascript', 'Internal');
	if (in_array(1, $groups)) $whatis_types[]="Admin";
	$whatisarray = array();
	foreach($whatis_types as $item) $whatisarray[] = $MOD_CODE2[strtoupper($item)];
	
	$whatisselect = '';
	for($i=0; $i < count($whatisarray); $i++) {
   		$select = ($whatis == $i) ? " selected='selected'" : "";
   		$whatisselect .= '<option value="'.$i.'"'.$select.'>'.$whatisarray[$i].'</option>'."\n";
  	}
    
    $modes_names = array('smart', 'full');
    $modes = array();
    foreach($modes_names as $item) $modes[] = $MOD_CODE2[strtoupper($item)];
    $mode_options = "";
    $counter = 0;
    foreach($modes as $item) {
    	$mode_options .= "<option value='".$counter."' ".(($counter==$mode)?" selected='selected'":"").">".$item."</option>";
		$counter++;
	}
	// Insert vars
	$template->set_var(array(
			'PAGE_ID' => $page_id,
			'SECTION_ID' => $section_id,
			'WB_URL' => WB_URL,
			'CONTENT' => $content,
			'WHATIS' => $whatis,
			'WHATISSELECT' => $whatisselect,
			'TEXT_SAVE' => $TEXT['SAVE'],
			'TEXT_CANCEL' => $TEXT['CANCEL'],
			'MODE'	=> $mode_options,
			'MODE_' => $mode,
			'LANGUAGE' => LANGUAGE,
			'MODES'	=> $MOD_CODE2['MODE'],
			'CODE2_HASH' => $hash_id,
			'FTAN'	=> $tan
		)
	);

	// Parse template object
	$template->parse('main', 'main_block', false);
	$template->pparse('output', 'page', false, false);
	
	unset($tan);
}
?>
