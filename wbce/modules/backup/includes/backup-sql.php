<?php
/**
 *
 * @category        modules
 * @package         backup
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2010, Website Baker Org. e.V.
 * @license         http://www.gnu.org/licenses/gpl.html
 *
 */

// no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));


require_once($modulePath."/info.php");

// if you are lucky this will work but server settings may possibly prevent this
if (ini_get("max_execution_time") < 120)
    // need to suppress an error message here , as it starts unwanted output here
    // and that leads to display on screen instead of downloading file
    @set_time_limit(30);
    @set_time_limit(45);
    @set_time_limit(60);
    @set_time_limit(120); // increase timeout to 2 minutes

// BACKUP FILES MODE
if ($_POST['tables']=='FILES') {

    // set path and filename for ZIP file
    $filepath = WB_PATH;
    $filename = 'WebsiteBackup_'.gmdate('Y-m-d', time()+TIMEZONE).'-'.time().'.zip';
    $v_remove = $filepath;

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
    
} 

// BACKUP DATABASE MODE
else {
    //Filename to use
    $filename = $_SERVER['HTTP_HOST'].'-backup-'.gmdate('Y-m-d', time()+TIMEZONE).'.sql';

    // Create output header
    $output = "".
    "#\n".
    "# WBCE ".WBCE_VERSION." Database Backup\n".
    "# ".WB_URL."\n".
    "# ".gmdate(DATE_FORMAT, time()+TIMEZONE).", ".gmdate(TIME_FORMAT, time()+TIMEZONE)."\n".
    "# modul version: ".$module_version."\n".
    "# ".
    "\n";

    //	Get table names

    //	Use this one for ALL tables in DB
    $query  = "SHOW TABLES";

    //Or use this to get ONLY wb tables
    if ($_POST['tables']=='WB') {
        $prefix=str_replace('_','\_',TABLE_PREFIX);
        $query = "SHOW TABLES LIKE '".$prefix."%'";
    }

    // Now get table names
    $result = $database->query($query);
    if ($database->is_error()) die ($database->get_error());

    //	Loop through tables
    while($row = $result->fetchRow()) {

        // Drop existing tables?
        if (isset($_POST['add_drop_table']) ) {
            $output .= "\r\n# Drop table ".$row[0]." if exists\r\nDROP TABLE IF EXISTS `".$row[0]."`;";
        }

        //show sql query to rebuild the query
        $sql = 'SHOW CREATE TABLE `'.$row[0].'`';

        $query2 = $database->query($sql);
        if ($database->is_error()) die ( $database->get_error() );

        // Start creating sql-backup
        $sql_backup ="\r\n# Create table ".$row[0]."\r\n\r\n";

        $out = $query2->fetchRow();

        $sql_backup .= $out['Create Table'].";\r\n\r\n";
        $sql_backup .= "# Dump data for ".$row[0]."\r\n\r\n";

        // Select everything
        $out = $database->query('SELECT * FROM `'.$row[0].'`');
        $sql_code = '';

        // Loop through all collumns
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

    // At last: output file
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


