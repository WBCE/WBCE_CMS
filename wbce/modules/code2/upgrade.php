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
if(!defined('WB_PATH')) die(header('Location: ../../index.php'));

$query = "show fields from `".TABLE_PREFIX."mod_code2`";

$result = $database->query ( $query );

if ($database->is_error() ) {

	$admin->print_error( $database->get_error() );

} else {
	
	$alter = 1;
	
	while ( !false == $data = $result->fetchRow( MYSQL_ASSOC ) ) {
		if ($data['Field'] == "whatis") {
			$alter = 0;
			break;
		}
	}

	if ( $alter != 0 ) {

		$thisQuery = "ALTER TABLE `".TABLE_PREFIX."mod_code2` ADD `whatis` INT NOT NULL DEFAULT 0";
		$r = $database->query($thisQuery);

		if ( $database->is_error() ) {

			$admin->print_error( $database->get_error() );

		} else {

			$admin->print_success( "Update Table for modul 'code2' with success." );
		}
	}
}

?>
