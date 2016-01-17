<?PHP
header('Content-type: application/javascript');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0, false');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Pragma: no-cache');

/*
    This Plugin read files of a directory and outputs
    a javascript array. Output is:

    var DropletSelectBox = new Array(
        new Array( empty, empty ),
        new Array( name, link ),
        new Array( name, link )...
    );

    DropletSelectBox will loaded as select options
    to wbdroplets plugin.
*/

require ( dirname(dirname(dirname(dirname(dirname(__DIR__))))).'/config.php');

$wb284  = (file_exists(dirname(dirname(dirname(dirname(dirname(__DIR__))))).'/setup.ini.php')) ? true : false;


// Create new admin object
if(!class_exists('admin', false)){ require(WB_PATH.'/framework/class.admin.php'); }
$admin = new admin('Pages', 'pages_modify', false);


if(!function_exists('cleanup')) {
  function cleanup ($string) {
    global $database;
    // if magic quotes on
    if (get_magic_quotes_gpc())
    {
        $string = stripslashes($string);
    }
    if(isset($database)&&method_exists($database,"escapeString")) {
      return preg_replace("/\r?\n/", "\\n", $database->escapeString($string));
    } elseif (is_object($database->db_handle) && (get_class($database->db_handle) === 'mysqli')){
      return preg_replace("/\r?\n/", "\\n", mysqli_real_escape_string($database->db_handle, $string)); 
    } else {
      return preg_replace("/\r?\n/", "\\n", mysql_real_escape_string($string)); 
    }
  } // end function cleanup
}

/**
 * setPrettyArray()
 * 
 * @param integer $bLinefeed
 * @param integer $iWhiteSpaces
 * @param integer $iTabs
 * @return string 
 */
    function setPrettyArray ( $bLinefeed = 1, $iWhiteSpaces = 0, $iTabs = 0 ){
        $sRetVal  = "";
        if ( $bLinefeed > 0 ) { $sRetVal .= "\n"; }
        if ( $iWhiteSpaces > 0 ) { $sRetVal .= str_repeat(" ",$iWhiteSpaces); }
        if ( $iTabs >  0 ) { $sRetVal .= str_repeat("\t",$iTabs); }
        return $sRetVal;
    }


$DropletSelectBox = "var DropletSelectBox = new Array( new Array( '', '' )";
$description = "var DropletInfoBox = new Array( new Array( '', '' )";
$usage = "var DropletUsageBox = new Array( new Array( '', '' )";

$array = array();
    $sql  = 'SELECT * FROM `'.TABLE_PREFIX.'mod_droplets` ';
    $sql .= 'WHERE `active`=1 ';
    $sql .= 'ORDER BY `name` ASC';
    if($resRec = $database->query($sql))
    {
        while( !false == ($droplet = $resRec->fetchRow() ) )
        {
            $title = cleanup($droplet['name']);
            $desc = cleanup($droplet['description']);
            $comments = cleanup($droplet['comments']);

            $DropletSelectBox .=  ", new Array( '".$title."', '".$droplet['name']."')";
            $description .=  ", new Array( '".$title."', '".$desc."')";
            $usage .=  ", new Array( '".$title."', '".$comments."')";
        }
    }

echo $DropletSelectBox .= " );\n";
echo $description .= " );\n";
echo $usage .= " );\n";
