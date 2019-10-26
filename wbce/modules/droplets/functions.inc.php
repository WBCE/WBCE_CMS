<?php
/**
 *
 * @category        module
 * @package         droplet
 * @author          Bianka (WebBird)
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2010, Website Baker Org. e.V.
 * @link            http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 *
 */

defined('WB_PATH') or exit("Cannot access this file directly"); 
if(LANGUAGE_LOADED) {
    $sLangPath = WB_PATH.'/modules/droplets/languages';
    require_once($sLangPath.'/EN.php');
    if(LANGUAGE != 'EN'){
        if(file_exists($sFile = $sLangPath.'/'.LANGUAGE.'.php')) {
            require_once($sFile);
        }
    }
}


global $HEADING;
global $TEXT;

$twig = getTwig(dirname(__FILE__) . '/templates');
$twig->addGlobal('WB_URL', WB_URL);
$twig->addGlobal('ADMIN_URL', ADMIN_URL);
$twig->addGlobal('THEME_URL', THEME_URL);
$twig->addGlobal('HEADING', $HEADING);
$twig->addGlobal('TEXT', $TEXT);
$twig->addGlobal('DR_TEXT', $DR_TEXT);
$twig->addGlobal('admintool_link', ADMIN_URL . '/admintools/index.php');
$twig->addGlobal('module_edit_link', ADMIN_URL . '/admintools/tool.php?tool=droplets');

include realpath( dirname(__FILE__).'/info.php' );
$twig->addGlobal('module_version',$module_version);


/**
 * copy droplet
 *
 * @param  integer  $droplet_id
 * @return
 **/
function wbce_copy_droplet($droplet_id)
{
    global $database, $admin;
    $tags          = array('<'.'?'.'php', '?'.'>' , '<?');

    // get droplet code
    $query_content = $database->query(sprintf(
        "SELECT * FROM `{TP}mod_droplets` WHERE `id` = '%s'",
        $droplet_id
    ));

    $fetch_content = $query_content->fetchRow(MYSQL_ASSOC);
    $code          = addslashes(str_replace($tags, '', $fetch_content['code']));
    $new_name      = $fetch_content['name'] . "_copy";
    $name          = $new_name;
    $i             = 1;

    // look for doubles
    $found = $database->query(sprintf(
        "SELECT * FROM `{TP}mod_droplets` WHERE `name`='%s'",
         $new_name
    ));
    while( $found->numRows() > 0 )
    {
        $new_name = $name . "_" . $i;
        $found = $database->query(sprintf(
            "SELECT * FROM `{TP}mod_droplets` WHERE `name`='%s'",
            $new_name
        ));
        $i++;
    }

    // add new droplet
    $result = $database->query(sprintf(
        "INSERT INTO `{TP}mod_droplets` VALUES ( NULL, '%s', '%s', '%s', '%s', '%s', 1, 0, 0, 0, '%s' )",
        $new_name, $code, $fetch_content['description'], time(),
        $admin->get_user_id(),  $fetch_content['comments']
    ));

    if( ! $database->is_error() )
    {
        $new_id = $database->get_one("SELECT LAST_INSERT_ID()");
        // Added PCWacht - javascript redirect... since headers are already sent
        $url = ADMIN_URL . '/admintools/tool.php?tool=droplets&do=modify&droplet_id='.$new_id;
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>'; 
        exit;
    }
    else {
        echo "ERROR: ", $database->get_error();
    }
}   // end function wbce_copy_droplet()

/**
 * creates a backup of the droplets given in $list
 *
 * @param  array   $list     - array of droplets
 * @param  string  $filename - filename, defaults to 'backup-droplets'
 * @param  boolean $return_details - wether to return details about added files
 * @return string
 **/
function wbce_backup_droplets($list, $filename='backup-droplets', $return_details=false)
{
    global $DR_TEXT, $MESSAGE;

    if(!$list || !is_array($list) || !count($list)) { return false; }

    // create temporary directory for exported files
    $temp_dir = WB_PATH . '/temp/droplets/';

    //  make sure it's empty
    if(is_dir($temp_dir))
    {
        rm_full_dir($temp_dir,true);
    }
    else
    {
        if(!@mkdir($temp_dir,OCTAL_DIR_MODE))
        {
            $err = error_get_last();
            return '<div class="alertbox error">'.
                   $MESSAGE['MEDIA_DIR_NOT_MADE'].': '.
                   $err['message'].
                   '</div>';
        }
    }

    $details = array();
    $result  = '';

    // write file for each droplet in the list
    foreach(array_values($list) as $droplet)
    {
        $sFile = $temp_dir . $droplet['name'] . '.php';
        $fh    = fopen( $sFile, 'w' );
        if(!is_resource($fh))
        {
            return '<div class="alertbox error">'.
                   $DR_TEXT['PACK_ERROR'].
                   '</div>';
        }
        else
        {
        $details[] = $DR_TEXT['SAVING'] . ': ' . $sFile;
        fwrite( $fh, '//:' . $droplet['description'] . "\n" );
        fwrite( $fh, '//:' . str_replace("\n"," ",$droplet['comments']) . "\n" );
        fwrite( $fh, $droplet['code'] );
        fclose( $fh );
    }
    }

    // add current date to filename
    $filename .= '_' . date( 'Y-m-d-His' );

    // while there's an existing file, add a number to the filename
    if ( file_exists( WB_PATH.'/temp/'.$filename.'.zip' ) ) {
        $n = 1;
        while( file_exists( WB_PATH.'/temp/'.$filename.'_'.$n.'.zip' ) ) {
            $n++;
        }
        $filename .= '_'.$n;
    }

    $details[]  = '<br />' . $DR_TEXT['CREATE_ARCHIVE'] . ': '. $filename;
    $temp_file = WB_PATH.'/modules/droplets/export/'.$filename.'.zip';

    require_once( WB_PATH . '/include/pclzip/pclzip.lib.php' );
    $archive   = new PclZip( $temp_file );
    $file_list = $archive->create( $temp_dir, PCLZIP_OPT_REMOVE_ALL_PATH );
    if ( $file_list == 0 )
    {
        $result  = $DR_TEXT['PACK_ERROR'] . ': '. $archive->errorInfo(true);
    }
    else
    {
        $result  = $DR_TEXT['BACKUP_CREATED'].' - <a href="' . WB_URL . str_replace(WB_PATH,'',$temp_file) . '">Download</a>';
    }

    // remove the temporary folder
    rm_full_dir($temp_dir);

    return '<div class="alertbox success">'.$result.'</div>'
         . ( $return_details ? '<div style="font-size:smaller;color:#aaa;">'.implode('<br />',$details).'</div>' : '' );
}

/**
 * Check if Droplet code syntax is valid by exploiting eval() function.
 * If WB_DEBUG const is true and system runs PHP7 or higher, Droplet syntax errors are displayed
 *
 * @param  string  $code PHP code to check.
 * @return boolean (PHP5), boolean|array(err_msge, line) with errors in case of PHP7.
 */
function wbce_check_syntax($code)
{
    // TODO: get rid of eval in a later version
    // Wrap into dummy function in case $code is empty or contains a syntax error at the start
    $code = "if(0){{$code}\n}";
    try {
        // till PHP 5 eval returns false and proceeds code execution in case of errors
        if (defined('WB_DEBUG') && WB_DEBUG) {
            return (eval($code) !== false);
        } else {
            return (@eval($code) !== false);
        }
    } catch (ParseError $e) {
        // PHP 7+ throws a ParseError exception if error occur inside eval
        // show error message caused by missformed Droplet code so we know whats to be fixed
        if (defined('WB_DEBUG') && WB_DEBUG) {
            echo '<strong>Droplet error: </strong>' . $e->getMessage() . '<br />';
        }
        return false;
    }
    return false;
}   // end function wbce_check_syntax()

/**
 * check for unique droplet name
 *
 * @param  string   $name
 * @return boolean
 **/
function wbce_check_unique($name)
{
	global $database;
	$query_droplets = $database->query(sprintf(
        "SELECT `name`  FROM `{TP}mod_droplets` WHERE `name` = '%s'",
        $name
    ));
	return ($query_droplets->numRows() == 1);
}   // end function wbce_check_unique()

/**
 * delete droplet(s)
 *
 **/
function wbce_delete_droplets()
{
    global $database, $admin, $MESSAGE;

    $list = isset( $_POST['markeddroplet'] ) ? $_POST['markeddroplet'] : array();
    if ( ! is_array( $list ) ) { $list = array($list); }
    if ( count( $list ) < 1 ) {
        return false;
    }

    // get the droplet(s) data
    $droplets = array();
    foreach ( $list as $id ) {
        $result = $database->query(sprintf(
            "SELECT * FROM `{TP}mod_droplets` WHERE id='%d'",
            $id
        ));
        if ( $result->numRows() > 0 ) {
            $droplets[] = $result->fetchRow();
        }
    }

    // create a backup
    wbce_backup_droplets( $droplets, 'drop_delete' );

    // delete
    foreach(array_values($list) as $id)
    {
        $database->query(sprintf(
            "DELETE FROM `{TP}mod_droplets` WHERE id = '%d' LIMIT 1",
            $id
        ));
    }
}   // end function wbce_delete_droplets()

/**
 * exports a list of droplets
 *
 * @access public
 * @param  array   $list      - list of droplet IDs
 * @param  string  $filename  - name part of the zip file to export to (optional)
 * @param  string  $export_id - single droplet ID, will overwrite $list!
 * @param  boolean $return_details - wether to return details about added files
 * @return string            - result of wbce_export_droplets()
 **/
function wbce_export_droplets($list, $filename='drop_export', $export_id=0, $return_details=false)
{
    global $database, $admin, $MESSAGE;

    $name = NULL;

    if ($export_id<>0) $list=$export_id;

    if ( ! is_array($list) ) {
        $list = array($list);
    }

    if ( count( $list ) < 1 AND $export_id==0) {
        return '<div class="drfail">Please mark some Droplets first!</div>';
    }

    // get the droplet(s) data
    $droplets = array();
    foreach ( $list as $id ) {
        $result = $database->query(sprintf(
            "SELECT * FROM `{TP}mod_droplets` WHERE id='%d'",
            TABLE_PREFIX, $id
        ));
        if ( $result->numRows() > 0 ) {
            $droplets[] = $result->fetchRow();
        }
    }

    // if there's only a single droplet to export, name the zip-file after this droplet
    if ( count( $list ) === 1 ) {
        $filename = 'droplet_'.$droplets[0]['name'];
    }

    // save
    return wbce_backup_droplets($droplets,$filename,$return_details);

}   // end function wbce_export_droplets()

/**
 * checks the exports folder for zip files
 *
 * @param  string $dir
 * @return array
 **/
function wbce_find_backups( $dir ) {
    $files = array();
    if ( $dh = opendir($dir) ) {
        while ( false !== ( $file = readdir($dh) ) )
        {
            if ( $file != "." && $file != ".." )
            {
                if ( preg_match( '/\.zip$/i', $file ) ) {
                    $files[] = $file;
                }
            }
        }
    }
    return $files;
}   // end function wbce_find_backups()

/**
 * handle import
 **/
function wbce_handle_upload()
{
    global $DR_TEXT, $TEXT, $database, $admin;
    if ( isset( $_POST['cancel'] ) )
    {
        return;
    }

    $return = '';

    if ( isset( $_FILES['userfile'] ) && isset( $_FILES['userfile']['name'] ) )
    {
        // Set temp vars
        $temp_dir   = WB_PATH.'/temp/';
        $temp_file  = $temp_dir . $_FILES['userfile']['name'];
        $temp_unzip = WB_PATH.'/temp/unzip/';
        $errors     = array();

        // Try to upload the file to the temp dir
        if( ! move_uploaded_file( $_FILES['userfile']['tmp_name'], $temp_file ) )
        {
            echo $DR_TEXT['Upload failed'];
       	    return;
        }

        $result = wbce_unpack_and_import( $temp_file, $temp_unzip );

        // Delete the temp zip file
        if( file_exists( $temp_file) )
        {
            unlink( $temp_file );
        }
        rm_full_dir($temp_unzip);

        // show errors
        if ( isset( $result['errors'] ) && is_array( $result['errors'] ) && count( $result['errors'] ) > 0 )
        {
            $return = '<div style="border: 1px solid #f00; padding: 5px; color: #f00; font-weight: bold;">'
                    . $DR_TEXT['IMPORT_ERRORS']
                    . "<br />\n";
            foreach ( $result['errors'] as $droplet => $error )
            {
                $return .= 'Droplet: ' . $droplet . '<br />'
                        .  '<span style="padding-left: 15px">'
                        .  $error
                        . '</span>';
            }
            $return .= "</div><br /><br />\n";
        }

        $return .= '<div class="drok">'
                . $result['count'] . " " . $DR_TEXT['IMPORTED']
                . '</div><br /><br />';
    }
    $return .= wbce_twig_display(array(),'upload',true);
    return $return;
}   // end function wbce_handle_upload()

/**
 * gets a list of all installed droplets
 *
 * @return  array
 **/
function wbce_list_droplets()
{
    global $admin, $database, $DR_TEXT, $TEXT;

    // Get userid for showing admin only droplets or not
    $loggedin_user  = ($admin->ami_group_member('1') ? 1 : $admin->get_user_id());
    $loggedin_group = $admin->get_groups_id();
    if ( version_compare(WB_VERSION, '2.8.2', '>=') && WB_VERSION<> "2.8.x" ) {
          $admin_user     = ( ($admin->get_home_folder() == '') && ($admin->ami_group_member('1') ) || ($loggedin_user == '1'));
    } else {
          $admin_user     = $loggedin_user;
    }

    // if ($loggedin_user == '1') {
    if ($admin_user) {
    	$query_droplets = $database->query("SELECT * FROM `{TP}mod_droplets` ORDER BY `name` ASC");
    } else {
    	$query_droplets = $database->query("SELECT * FROM `{TP}mod_droplets` WHERE `admin_view` <> '1' ORDER BY `name` ASC");
    }

    if($query_droplets->numRows() > 0)
    {
        $list = array();
        while ($droplet = $query_droplets->fetchRow(MYSQL_ASSOC))
        {
            if(is_array($droplet) && isset($droplet['name']))
            {
                $get_modified_user = $database->query(sprintf(
                    "SELECT `display_name`, `username`, `user_id` FROM `{TP}users` WHERE `user_id` = '%d' LIMIT 1",
                    $droplet['modified_by']
                ));
        		if($get_modified_user->numRows() > 0) {
        			$fetch_modified_user = $get_modified_user->fetchRow();
        			$modified_user   = $fetch_modified_user['username'];
        			$modified_userid = $fetch_modified_user['user_id'];
        		} else {
        			$modified_user   = $TEXT['UNKNOWN'];
        			$modified_userid = 0;
        		}
        		$comments = str_replace(array("\r\n", "\n", "\r"), '<br />', $droplet['comments']);
        		if (!strpos($comments,"[["))
                {
                    $comments = "Use: [[".$droplet['name']."]]<br />".$comments;
                }
        		$comments   = str_replace(array("[[", "]]"), array('<b>[[',']]</b>'), $comments);
        		$droplet['valid_code'] = wbce_check_syntax($droplet['code']);
        		if (!$droplet['valid_code'] === true)
                {
                    $comments = '<span style="color: #ff0000;"><strong>'.$DR_TEXT['INVALIDCODE'].'</strong></span><br /><br />'.$comments;
                }
        		$droplet['unique'] = wbce_check_unique($droplet['name']);
        		if ($droplet['unique'] === false)
                {
                    $comments = '<span style="color: #ff0000;"><strong>'.$DR_TEXT['NOTUNIQUE'].'</strong></span><br /><br />'.$comments;
                }
        		$comments = '<span>'.$comments.'</span>';
                $droplet['comments'] = $comments;
                $list[] = $droplet;
            }
        }
        return $list;
    }
}   // end function wbce_list_droplets()

/**
 * renders a template
 *
 * @param  array   $output
 * @param  string  $tplname
 * @param  boolean $return
 **/
function wbce_twig_display($output, $tplname='tool', $return=false)
{
    global $twig;
    $tpl = $twig->loadTemplate($tplname.'.twig');
    if($return)
    {
        return $tpl->render($output);
    }
    $tpl->display($output);
}   // function wbce_twig_display()

/**
 * unpacks a zipped droplet and imports it to the database
 *
 * @param  string  $temp_file
 * @param  string  $temp_unzip
 **/
function wbce_unpack_and_import( $temp_file, $temp_unzip )
{

    global $admin, $database;

    // Include the PclZip class file
    require_once WB_PATH.'/include/pclzip/pclzip.lib.php';

    $errors  = array();
    $imports = array();
    $count   = 0;
    $archive = new PclZip($temp_file);
    $list    = $archive->extract(PCLZIP_OPT_PATH, $temp_unzip);

    // now, open all *.php files and search for the header;
    // an exported droplet starts with "//:"
    if ( $dh = opendir($temp_unzip) ) {
        while ( false !== ( $file = readdir($dh) ) )
        {
            if ( $file != "." && $file != ".." )
            {
                if ( preg_match( '/^(.*)\.php$/i', $file, $name_match ) )
                {
                    // Name of the Droplet = Filename
                    $name  = $name_match[1];
                    // Slurp file contents
                    $lines = file( $temp_unzip.'/'.$file );
                    // check the number of lines: more than 3!
                    if(!(count($lines) > 3))
                    {
                        // try to resolve by reading the file again
                        if(!(count($lines) > 1))
                        {
                            ini_set('auto_detect_line_endings', true);
                            $lines = file( $temp_unzip.'/'.$file );
                        }
                        else
                        {
                            $errors[$name] = 'Invalid file, unable to import!';
                            continue;
                        }
                        // still failing -> break!
                        if(!(count($lines) > 3))
                        {
                            $errors[$name] = 'Invalid file, unable to import!';
                            continue;
                        }
                    }
                    // First line: Description
                    $description = "";
                    if ( preg_match( '#^//\:(.*)$#', $lines[0], $match ) ) {
                        $description = $match[1];
                    }
                    // Second line: Usage instructions
                    $usage = "";
                    if ( preg_match( '#^//\:(.*)$#', $lines[1], $match ) ) {
                        $usage       = addslashes( $match[1] );
                    }
                    // Remaining: Droplet code
                    $code = implode( '', array_slice( $lines, 2 ) );
                    // replace 'evil' chars in code
                    $tags = array('<?php', '?'.'>' , '<?');
                    $code = addslashes(str_replace($tags, '', $code));
                    // Already in the DB?
                    $stmt  = 'INSERT';
                    $id    = NULL;
                    $found = $database->get_one(sprintf("SELECT * FROM `{TP}mod_droplets` WHERE name='%s'", $name));
                    if ( $found && $found > 0 ) {
                        $stmt = 'REPLACE';
                        $id   = $found;
                    }
                    // execute
                    $result = $database->query(sprintf(
                        "%s INTO `{TP}mod_droplets` VALUES(" . ($id ? "'$id'" : 'NULL') . ",'%s','%s','%s','%s','%d',1,0,0,0,'%s')",
                        $stmt, $name, $code, $description, time(), $admin->get_user_id(), $usage
                    ));
                    if( ! $database->is_error() ) {
                        $count++;
                        $imports[$name] = 1;
                    }
                    else {
                        $errors[$name] = $database->get_error();
                    }
                }
            }
        }
        closedir($dh);
    }

    return array( 'count' => $count, 'errors' => $errors, 'imported' => $imports );

}   // end function wbce_unpack_and_import()
