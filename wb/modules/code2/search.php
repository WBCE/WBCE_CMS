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

function code2_search($func_vars) {
	extract($func_vars, EXTR_PREFIX_ALL, 'func');
	
	// how many lines of excerpt we want to have at most
	$max_excerpt_num = $func_default_max_excerpt;
	$divider = ".";
	$result = false;
	
	$table = TABLE_PREFIX."mod_code2";
	$query = $func_database->query("
		SELECT `content`
		FROM `$table`
		WHERE `section_id`=$func_section_id AND `whatis`=1
	");

	if($query->numRows() > 0) {
		if($res = $query->fetchRow()) {
			$mod_vars = array(
				'page_link' => $func_page_link,
				'page_link_target' => "#wb_section_$func_section_id",
				'page_title' => $func_page_title,
				'page_description' => $func_page_description,
				'page_modified_when' => $func_page_modified_when,
				'page_modified_by' => $func_page_modified_by,
				'text' => $res['content'].$divider,
				'max_excerpt_num' => $max_excerpt_num
			);
			if(print_excerpt2($mod_vars, $func_vars)) {
				$result = true;
			}
		}
	}
	return $result;
}

?>
