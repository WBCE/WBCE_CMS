<?php
/**
 *
 * @category        modules
 * @package         backup
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2010, Website Baker Org. e.V.
 * @link            http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 4.3.4 and higher - See notes below for file backups.
 * @version         $Id$
 * @filesource      $HeadURL$
 * @lastmodified    $Date$
 *
 * 2.8.0.6  Fixed a small problem with tables that use - in their name
 * 
 * 
 * 
 * 2.8.0.3  File Backup now always available (by mjm4842 on September 4, 2010)
 *          Replaced use of exec(ZIP) with a call to the PclZip library which
 *           is included with WebsiteBaker. Not only is it faster, but
 *           compatible with more systems. As a result, the file back option
 *           is now always available on all systems.
 *          For security purposes, in the event that the execution of the script
 *           times out before it finishes, the filename now includes unix
 *           timestamp number to minimize the possibility of someone guessing
 *           the file name.
 *          The http headers have been modified to be compatible with IE 6+.
 *          Upgrade from all previous versions now works properly.
 *
 * 2.8.0.2b File Backup fixes (by mjm4842 on August 2, 2010)
 *          Fixed undeclared variables to eliminate related Notices.
 *          Now suppresses exec Warning message if exec is disabled in php.ini.
 *          Added tool tip for radio button showing reason if disabled.
 *          If PHP script timeout is less than 2 minutes, increased time limit.
 *
 * 2.8.0.2a Add new file backup option (by mjm4842 on 1 August 2010)
 *          Note: Only works if PHP exec command is enabled and ZIP command
 *           is executable.
 *
 * 2.8.0    Add "drop table if exists" - option to the interface and backup-sql.
 *          Minor cosmetic code-changes.
 *          Add modul-version to the sql-dump.
 *
 * 2.7.x    QuickFix: 26. March 2010
 */

/**
 *	Check if user clicked on the backup button
 *
 */
if( !isset($_POST['backup']) ) die ( header('Location: ../../') );

/**
 *	Include config
 *
 */
require_once('../../config.php');

/**
 *	Create new admin object
 *
 */
require_once('../../framework/class.admin.php');
$admin = new admin('Admintools', 'admintools', false, false);

if ( $admin->get_permission('admintools') == false ) die ( header('Location: ../../index.php') );

/**
 *	Testing for calling inside WegsiteBaker or from another installation/hacker
 *
 */
$caller = $_SERVER['HTTP_REFERER'];
$test = ADMIN_URL."/admintools/tool.php";
if ($caller != $test) die ( header('Location: ../../index.php') );

/**
 *	Begin output var
 *
 */
require_once(dirname(__FILE__)."/info.php");

if ($_POST['tables']=='FILES') {
  if (ini_get("max_execution_time") < 120)
    set_time_limit(120); // increase timeout to 2 minutes

  // set path and filename for ZIP file
  $filepath = WB_PATH;
  $filename = 'WebsiteBackup_'.gmdate('Y-m-d', time()+TIMEZONE).'-'.time().'.zip';
  $v_remove = $filepath;

  // Include the PclZip class file
  require_once(WB_PATH.'/include/pclzip/pclzip.lib.php');

  // To support windows and the C: root you need to add the
  // following 2 lines, should be ignored on linux
  if (substr($v_remove, 1,1) == ':' && strlen($v_remove) <= 3) // ex: c:\
    $v_remove = substr($v_remove, 2);

  // make filepath the current directory
  chdir($filepath);

  // create zip file
  $archive = new PclZip($filename);
  $ret = $archive->create($filepath, PCLZIP_OPT_REMOVE_PATH, $v_remove);
  if ($ret == 0)
     die("Error : ".$archive->errorInfo(true));

  // Download to user and delete the evidence.
  DownloadFile($filename); 
  unlink($filename);

} else {
  /**
   *	Filename to use
   */
  $filename = $_SERVER['HTTP_HOST'].'-backup-'.gmdate('Y-m-d', time()+TIMEZONE).'.sql';
  $output = "".
  "#\n".
  "# Website Baker ".WB_VERSION." Database Backup\n".
  "# ".WB_URL."\n".
  "# ".gmdate(DATE_FORMAT, time()+TIMEZONE).", ".gmdate(TIME_FORMAT, time()+TIMEZONE)."\n".
  "# modul version: ".$module_version."\n".
  "# ".
  "\n";

  /**
   *	Get table names
   *	Use this one for ALL tables in DB
   *
   */
  $query  = "SHOW TABLES";

  if ($_POST['tables']=='WB') {

  	/**
  	 *	Or use this to get ONLY wb tables
  	 *
  	 */
  	$prefix=str_replace('_','\_',TABLE_PREFIX);
  	$query = "SHOW TABLES LIKE '".$prefix."%'";
  }

  $result = $database->query($query);

  if ($database->is_error()) die ($database->get_error());

  /**
   *	Loop through tables
   *
   */
  while($row = $result->fetchRow()) {

  	/**
  	 *	Drop existing tables?
  	 *
  	 */
  	if (isset($_POST['add_drop_table']) ) {
  		$output .= "\r\n# Drop table ".$row[0]." if exists\r\nDROP TABLE IF EXISTS `".$row[0]."`;";
  	}

  	/**
  	 *	show sql query to rebuild the query
  	 *
  	 */
  	$sql = 'SHOW CREATE TABLE `'.$row[0].'`';

  	$query2 = $database->query($sql);

  	if ($database->is_error()) die ( $database->get_error() );

  	/**
  	 *	Start creating sql-backup
  	 *
  	 */
  	$sql_backup ="\r\n# Create table ".$row[0]."\r\n\r\n";

  	$out = $query2->fetchRow();

  	$sql_backup .= $out['Create Table'].";\r\n\r\n";
  	$sql_backup .= "# Dump data for ".$row[0]."\r\n\r\n";

  	/**
  	 *	Select everything
  	 *
  	 */
  	$out = $database->query('SELECT * FROM `'.$row[0].'`');
  	$sql_code = '';

  	/**
  	 *	Loop through all collumns
  	 *
  	 */
  	while($code = $out->fetchRow( MYSQL_ASSOC )) {
  		$sql_code .= "INSERT INTO `".$row[0]."` SET ";

  		foreach($code as $insert => $value) {
  			$sql_code .= "`".$insert ."`='".addslashes($value)."',";
  		}
  		$sql_code = substr($sql_code, 0, -1);
  		$sql_code.= ";\r\n";
  	}
  	$output .= $sql_backup.$sql_code;
  }
  /**
   *	At last: output file
   *
   */
  header('Content-Type: text/html');
  header('Content-Disposition: attachment; filename='.$filename);
  echo $output;
}

function DownloadFile($file) { // $file: including path
  if(file_exists($file)) {
    header('Content-Description: File Transfer');
    header("Expires: -1");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private");
    header("Cache-Control: no-cache");
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: '.filesize($file));
    header("Pragma: public");
    readfile($file);
  }
}

?>