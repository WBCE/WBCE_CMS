<?php
/**
 *
 * @category        module
 * @package         droplet
 * @author          Bianka (WebBird)
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2010, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 *
 */

    
// Load Twig using WBCEs internal getTwig function
// Doing it this way anables you to use a whole lot of
// prepared Constants and useful Functions

defined('DROPLETS_BACKUP_DIR') or define(
    'DROPLETS_BACKUP_DIR',
    WB_PATH.'/var/modules/droplets/export/'
);

if (!is_dir(DROPLETS_BACKUP_DIR)) {
    // Create backup directory if not present
    make_dir(DROPLETS_BACKUP_DIR);
}

$oTwig = getTwig(dirname(__FILE__) . '/templates');

$oTwig->addFunction(new \Twig\TwigFunction("check_droplet_syntax", 
    function ($iDropletID) {       
        return check_droplet_syntax($iDropletID);
   }
));

$oTwig->addFunction(new \Twig\TwigFunction("get_user_name", 
    function ($iUserID) {       
        return $GLOBALS['database']->get_one(
                "SELECT `display_name` FROM `{TP}users` WHERE `user_id` = ". intval($iUserID)
        );
   }
));

include realpath( dirname(__FILE__).'/info.php' );
$oTwig->addGlobal('module_version', $module_version);


/**
 * copy droplet
 *
 * @param  integer  $droplet_id
 * @return
 **/
function wbce_copy_droplet($droplet_id)
{
    global $database, $admin;
    $tags = array('<'.'?'.'php', '?'.'>' , '<?');

    // get droplet code
    $query_content = $database->query(sprintf(
        "SELECT * FROM `{TP}mod_droplets` WHERE `id` = '%s'", $droplet_id
    ));

    $fetch_content = $query_content->fetchRow(MYSQLI_ASSOC);
    $code          = addslashes(str_replace($tags, '', $fetch_content['code']));
    $new_name      = $fetch_content['name'] . "_copy";
    $name          = $new_name;
    $i             = 1;

    // look for doubles
    $found = $database->query(sprintf(
        "SELECT * FROM `{TP}mod_droplets` WHERE `name`='%s'", $new_name
    ));
    while( $found->numRows() > 0 )
    {
        $new_name = $name . "_" . $i;
        $found = $database->query(sprintf(
            "SELECT * FROM `{TP}mod_droplets` WHERE `name`='%s'", $new_name
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
        return $database->get_one("SELECT LAST_INSERT_ID()");
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
function wbce_backup_droplets($list,$filename='backup-droplets',$return_details=false)
{
    global $DR_TEXT, $MESSAGE;
    $oMsgBox = new MessageBox();

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
        if (!is_resource($fh)){
            return '<div class="alertbox error">'.
                   $DR_TEXT['PACK_ERROR'].
                   '</div>';
        } else {
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
    $temp_file = DROPLETS_BACKUP_DIR.$filename.'.zip';
    $result = '';
    require_once( WB_PATH . '/include/pclzip/pclzip.lib.php' );
    $archive   = new PclZip( $temp_file );
    $file_list = $archive->create( $temp_dir, PCLZIP_OPT_REMOVE_ALL_PATH );
    if ( $file_list == 0 ) {
        $oMsgBox->error($DR_TEXT['PACK_ERROR'] . ': '. $archive->errorInfo(true));
    } else {
        $oMsgBox->success($DR_TEXT['BACKUP_CREATED']);
        $result  = '<div class="alert alert-success">'
                . $DR_TEXT['BACKUP_CREATED']                
                . ':<br>'
                . '<a class="button ico-download" href="'.get_url_from_path($temp_file).'">Download</a>'
                . '</div>';
    }

    // remove the temporary folder
    rm_full_dir($temp_dir);

    return $result . ( $return_details ? '<div style="font-size:smaller;color:#aaa;">'.implode('<br />',$details).'</div>' : '' );
}

/**
 * Check if Droplet code syntax is valid by exploiting eval() function.
 * If WB_DEBUG const is true and system runs PHP7 or higher, Droplet syntax errors are displayed
 *
 * @param  string  $code PHP code to check.
 * @return boolean (PHP5), boolean|array(err_msge, line) with errors in case of PHP7.
 */
function check_droplet_syntax($iDropletID)
{
    global $database;
    $sCode = $database->get_one(
        "SELECT `code` FROM `{TP}mod_droplets` WHERE `id` = ". (int) $iDropletID
    );
    // TODO: get rid of eval in a later version
    // Wrap into dummy function in case $sCode is empty or contains a syntax error at the start
    $sCode = "if(0){{$sCode}\n}";
    try {
        // till PHP 5 eval returns false and proceeds code execution in case of errors
        if (defined('WB_DEBUG') && WB_DEBUG) {
            return (eval($sCode) !== false);
        } else {
            return (@eval($sCode) !== false);
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
}   // end function check_droplet_syntax()

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
        "SELECT `name`  FROM `{TP}mod_droplets` WHERE `name` = '%s'", $name
    ));
	return ($query_droplets->numRows() == 1);
}   // end function wbce_check_unique()

/**
 * delete droplet(s)
 *
 **/
function wbce_delete_droplets()
{
    global $database,$admin,$MESSAGE;

    $list = isset( $_POST['markeddroplet'] ) ? $_POST['markeddroplet'] : array();
    if ( ! is_array( $list ) ) { $list = array($list); }
    if ( count( $list ) < 1 ) {
        return false;
    }

    // get the droplet(s) data
    $droplets = array();
    foreach ( $list as $id ) {
        $result = $database->query(sprintf(
            "SELECT * FROM `{TP}mod_droplets` WHERE id='%d'", $id
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
            "DELETE FROM `{TP}mod_droplets` WHERE id = '%d' LIMIT 1", $id
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
function wbce_export_droplets($list,$filename='drop_export',$export_id=0,$return_details=false)
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
            "SELECT * FROM `{TP}mod_droplets` WHERE id='%d'", $id
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
    $oMsgBox = new MessageBox();
    if ( isset( $_POST['cancel'] ) )
    {
        return;
    }
    $aToTwig = array();
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

        $result = importDropletFromZip( $temp_file, $temp_unzip );
        #debug_dump($result);
        // Delete the temp zip file
        if ( file_exists( $temp_file) ) {
            unlink( $temp_file );
        }
        rm_full_dir($temp_unzip);

        // show errors
        if ( isset( $result['errors'] ) && is_array( $result['errors'] ) && count( $result['errors'] ) > 0 )
        {
            $return = $DR_TEXT['IMPORT_ERRORS']."<br />";
            foreach ( $result['errors'] as $droplet => $error ) {
                $return .= 'Droplet: ' . $droplet . '<br />'
                        .  '<span>' .  $error . '</span>';
            }
            $oMsgBox->error($return);
        }
        $oMsgBox->success($result['count'] . " " . $DR_TEXT['IMPORTED']);
        $aToTwig = array('result' => $result);
    }
    $return .= wbce_twig_display($aToTwig, 'upload', true);
    return $return;
}   // end function wbce_handle_upload()

/**
 * gets a list of all installed droplets
 *
 * @return  array
 **/
function wbce_list_droplets($bShowDate = false)
{
    global $admin, $database, $DR_TEXT, $TEXT;

    // Get userid for showing admin only droplets or not
    $loggedin_user  = ($admin->ami_group_member('1') ? 1 : $admin->get_user_id());
    $loggedin_group = $admin->get_groups_id();
    $admin_user = $loggedin_user;
    if ( version_compare(WB_VERSION, '2.8.2', '>=') && WB_VERSION<> "2.8.x" ) {
          $admin_user = ( ($admin->get_home_folder() == '') && ($admin->ami_group_member('1') ) || ($loggedin_user == '1'));
    } 

    $sSql = "SELECT * FROM `{TP}mod_droplets` ";
    if (!$admin_user) {
        $sSql .=" WHERE `admin_view` <> '1'";
    }
    if($bShowDate){
        $sSql .=" ORDER BY `modified_when` DESC";
    } else {
        $sSql .=" ORDER BY `name` ASC";        
    }
    $query_droplets = $database->query($sSql);

    if($query_droplets->numRows() > 0)
    {
        $list = array();
        while ($droplet = $query_droplets->fetchRow(MYSQLI_ASSOC))
        {
            if(is_array($droplet) && isset($droplet['name']))
            {
                $get_modified_user = $database->query(sprintf(
                    "SELECT `display_name`, `username`, `user_id` FROM `{TP}users` WHERE `user_id` = '%d' LIMIT 1", $droplet['modified_by']
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
                if (!strpos($comments,"[[")) {
                    $comments = "Use: [[".$droplet['name']."]]<br />".$comments;
                }
                $comments   = str_replace(array("[[", "]]"), array('<b>[[',']]</b>'), $comments);
                $droplet['valid_code'] = check_droplet_syntax($droplet['id']);

                if (!$droplet['valid_code'] === true) {
                    $comments = '<span style="color: #ff0000;"><strong>'.$DR_TEXT['INVALIDCODE'].'</strong></span><br /><br />'.$comments;
                }

                $droplet['unique'] = wbce_check_unique($droplet['name']);
                if ($droplet['unique'] === false) {
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
    global $oTwig;
    $oTwig->addGlobal('MODULE_DIR', get_url_from_path(__DIR__)); 
    $oTemplate = $oTwig->load($tplname.'.twig');
    $sRender = $oTemplate->render($output);
    if($return) {
        return $sRender;
    }
    echo $sRender;
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
                    $found = $database->get_one(sprintf("SELECT * FROM `{TP}mod_droplets` WHERE name='%s'",$name));
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

    return array( 
        'count'    => $count, 
        'errors'   => $errors, 
        'imported' => $imports 
    );

}   // end function wbce_unpack_and_import()


if(!function_exists('renderTabs')){
    /**
     * @funcname renderTabs
     * @brief    Build the Tab Navigation used in Droplets Tool
     *           and other parts of the Admin Backend
     *  
     * @param    string $sCurrPos // current position in the tab grouü
     * @return   array
     */
    function renderTabs($sCurrPos = 'overview') {
        global $TEXT, $DR_TEXT;

        
        // the actual link positions available in the addons area 
        $aTabs = array(
            'overview' => array(
                $DR_TEXT['DROPLETS'], 
                'tint'
            ),
            'upload' => array(
                $DR_TEXT['UPLOAD'], 
                'upload'
            ),
            'manage_backups' => array(
                $DR_TEXT['MANAGE_BACKUPS'], 
                'hdd-o'
            ),
            'show_help' => array(
                $DR_TEXT['HELP'], 
                'question-circle'
            ),    
        );
        
        $aRetVal = array();
        foreach ($aTabs as $key => $aValues) {

            $sUri = 'admintools/tool.php?tool='.ADMIN_TOOL_DIR;
            if($key != 'overview'){
                $sUri .= '&do='.$key;
            }

            $bCurr = false;
            $bCurr = ($key == $sCurrPos);
            $aRetVal[$key]['pos']       = $sUri;
            $aRetVal[$key]['a_class']   = ($bCurr) ? ' sel' : '';
            $aRetVal[$key]['li_class']  = ($bCurr) ? ' class="actionSel"' : '';
            $aRetVal[$key]['link_name'] = $aValues[0];
            $aRetVal[$key]['icon']      = $aValues[1];
        }
        return $aRetVal;
    }    
}


        /**
         * importDropletFromZip
         * @param string $sZipPath - full path to your droplets zip-file e.g. __DIR__.'/droplets/droplets.zip'
         * @param string $sTempDir - optional; full path to temporary unzip folder e.g. !must be writeable
         */
        function importDropletFromZip($sZipPath, $sTempDir = '', $bDeleteTemp = true)
        {
                $errors  = array();
                $count   = 0;
                $aReturn = array();
                
                $sTempDir = $sTempDir != '' ? $sTempDir : WB_PATH . '/temp/droplets/';
                if (!file_exists($sTempDir))
                        mkdir($sTempDir, 0777, true);
                
                $oZip = new ZipArchive;
                if ($oZip->open($sZipPath) === TRUE) {
                        $oZip->extractTo($sTempDir);
                        $oZip->close();
                }
                
                // now, open the temp directory
                if (false !== ($dh = opendir($sTempDir))) {
                        while (false !== ($sFile = readdir($dh))) {
                                // read trough all the files in temp directory
                                if ($sFile != "." && $sFile != "..") {
                                        $feedback                       = importDropletFromFile($sFile, $sTempDir); // use import droplet function
                                        $imports[$feedback['imported']] = $feedback['imported'];
                                        if (isset($feedback['error'])) {
                                                $errors[$feedback['imported']] = $feedback['error'];
                                        } else {
                                                $count++;
                                        }
                                }
                        }
                        closedir($dh);
                }
                if (!isset($imports)) {
                        $imports = array();
                }
                if ($bDeleteTemp == true) {
                        rm_full_dir($sTempDir); // wb internal function
                }
                return array(
                        'count'    => $count,
                        'errors'   => $errors,
                        'imported' => $imports
                );
        }

/**
 * importDropletFromFile
 * @param string $sZipPath - full path to your droplets zip-file e.g. __DIR__.'/droplets/droplets.zip'
 * @param string $sTempDir - optional; full path to temporary unzip folder e.g. !must be writeable
 */
function importDropletFromFile($sFilename = '', $sDirPath = '')
{
    global $database, $admin;
    $description = '';
    $usage       = '';
    $code        = '';
    if ($sDirPath == '' && is_readable($sFilename)) {
        $sDirPath  = dirname($sFilename);
        $sFilename = basename($sFilename);
    }
    $aReturn = array();
    if (preg_match('/^(.*)\.php$/i', $sFilename, $name_match)) {
        // Name of the Droplet = Filename
        $name = $name_match[1];
        // Slurp file contents

        $aLines = file($sDirPath . '/' . $sFilename);
        if (strpos($aLines[0], '<?php') !== false) {
            array_shift($aLines);
        }
        // First line: Description
        if (preg_match('#^//\:(.*)$#', $aLines[0], $match)) {
                $description = $match[1];
        }
        // Second line: Usage instructions
        if (preg_match('#^//\:(.*)$#', $aLines[1], $match)) {
            $aBreaks  = array(
                "<br />",
                "<br/>",
                "<br>"
            );
            $match[1] = str_ireplace($aBreaks, "\r\n", $match[1]);
            $usage    = addslashes($match[1]);
        }
        // Remaining: Droplet code
        $code  = implode('', array_slice($aLines, 2));
        // replace 'evil' chars in code
        $tags  = array(
            '<?php',
            '?>',
            '<?'
        );
        $code  = addslashes(str_replace($tags, '', $code));
        // Already in the DB?
        $stmt  = 'INSERT';
        $id    = NULL;
        $found = $database->get_one("SELECT * FROM `{TP}mod_droplets` WHERE name='$name'");
        if ($found && $found > 0) {
            $stmt = 'REPLACE';
            $id   = $found;
        }
        // execute
        $result = $database->query("$stmt INTO `{TP}mod_droplets` VALUES(
            '$id', '$name', '$code', '$description', '" . time() . "', '" . $admin->get_user_id() . "', 1, 0, 0, 0, '$usage'
        )");
        $aReturn['imported'] = $name;
        if ($database->is_error()) {
            $aReturn['error'] = $database->get_error();
        }
    }
    return $aReturn;
}


        
/**
 * simple check if Droplet is already installed
 * isDroplet 
 * @param string Droplet Name
 */
function isDroplet($sDropletName)
{
        $tmp = $GLOBALS['database']->get_one("SELECT `id` FROM `{TP}mod_droplets` 
                        WHERE `name` = '" . $sDropletName . "'");
        return (is_numeric($tmp)) ? intval($tmp) : false;
}