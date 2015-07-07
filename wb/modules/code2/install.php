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

$table = TABLE_PREFIX."mod_code2";

$all_jobs = array();

/**
 *	Delete the table
 */
$query = "DROP TABLE IF EXISTS `".$table."`";

$all_jobs[] = $query;

/**
 *	Creating the table new
 */
$query  = "CREATE TABLE `".$table."` (";
$query .= "`section_id`	INT NOT NULL DEFAULT '0',";
$query .= "`page_id`	INT NOT NULL DEFAULT '0',";
$query .= "`whatis`		INT NOT NULL DEFAULT '0',";
$query .= "`content`	TEXT NOT NULL,";
$query .= " PRIMARY KEY ( `section_id` ) )";

$all_jobs[] = $query;

/**
 *	Preparing the db-connector
 */
$use_job_numbers = false;

$c_vars = get_class_vars ('database');
if ( true === in_array("log_querys", $c_vars) ) {
	$database->log_querys = true;
	$database->log_path = WB_PATH."/logs/";
	$database->log_filename = "code2_install.log";
	
	$use_job_numbers = true;
	$counter = 103000;
}

/**
 *	Processing the jobs/querys all in once
 */
foreach( $all_jobs as $q ) {
	
	$use_job_numbers === false ? $database->query($q) : $database->query($q, $counter++);
	
	if ( $database->is_error() ) 
		$admin->print_error($database->get_error(), $js_back);

}

?>
