<?php
/**
 *
 * @category        module
 * @package         droplet
 * @author          Bianka (WebBird)
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2010, Website Baker Org. e.V.
 * @link			      http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 *
 */

function wb_handle_copy($droplet_id) {
    global $database, $admin;
    $tags          = array('<?php', '?>' , '<?');
    // get droplet code
    $query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_droplets WHERE id = '$droplet_id'");
    $fetch_content = $query_content->fetchRow();
    $code          = addslashes(str_replace($tags, '', $fetch_content['code']));
    $new_name      = $fetch_content['name'] . "_copy";
    $i             = 1;
    // look for doubles
    $found = $database->query( 'SELECT * FROM '.TABLE_PREFIX."mod_droplets WHERE name='$new_name'" );
    while( $found->numRows() > 0 ) {
        $new_name  = $fetch_content['name'] . "_copy" . $i;
        $found     = $database->query( 'SELECT * FROM '.TABLE_PREFIX."mod_droplets WHERE name='$new_name'" );
        $i++;
    }
    // generate query
    $query         = "INSERT INTO ".TABLE_PREFIX."mod_droplets VALUES('',"
                   . "'$new_name','$code','"
                   . $fetch_content['description']. "','".time()."','"
                   . $admin->get_user_id()."',1,0,0,0,'"
                   . $fetch_content['comments']."')";
    // add new droplet
    $result = $database->query($query);
    if( ! $database->is_error() ) {
        $new_id = mysql_insert_id();
        if ( version_compare(WB_VERSION, '2.8.2', '>=')  && WB_VERSION<>"2.8.x" ) {
            $new_id = $admin->getIDKEY($new_id);
        }
        //echo header( "Location: ".WB_URL."/modules/droplets/modify_droplet.php?droplet_id=$new_id" );
        // Added PCWacht - javascript redirect... since headers are allready sent
          
        $url = WB_URL.'/modules/droplets/modify_droplet.php?droplet_id='.$new_id;
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
}   // end function wb_handle_copy()
 
function wb_find_backups( $dir ) {
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
}   // end function wb_find_backups()

function wb_handle_upload() {
    global $DR_TEXT, $TEXT, $database, $admin;
    if ( isset( $_POST['cancel'] ) )
    {
        return;
    }
    if ( ! isset( $_FILES['userfile'] ) && ! isset( $_FILES['userfile']['name'] ) ) {
?>
    <form method="post" action="<?php echo ADMIN_URL; ?>/admintools/tool.php?tool=droplets&amp;upload=1" enctype="multipart/form-data" name="upload">
<?php
    if ( method_exists( $admin, 'getFTAN' ) ) { echo $admin->getFTAN(); }
?>
      <fieldset class="fieldset">
        <legend><?php echo $DR_TEXT['UPLOAD']; ?></legend>
        <table>
          <tbody>
            <tr>
              <td>
                <input type="file" name="userfile" size="50" />
                <script type="text/javascript">document.upload.userfile.focus();</script>
              </td>
            </tr>
            <tr>
              <td>
                <input type="submit" class="button" value="<?php echo $DR_TEXT['UPLOAD']; ?>" name="submit" />
                <input type="submit" class="button" value="<?php echo $TEXT['CANCEL']; ?>" name="cancel" />
              </td>
            </tr>
          </tbody>
        </table>
      </fieldset>
    </form>
<?php
    }
    else {

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

        $result = wb_unpack_and_import( $temp_file, $temp_unzip );

        // Delete the temp zip file
        if( file_exists( $temp_file) )
        {
            unlink( $temp_file );
        }
        rm_full_dir($temp_unzip);

        // show errors
        if ( isset( $result['errors'] ) && is_array( $result['errors'] ) && count( $result['errors'] ) > 0 ) {
            echo '<div style="border: 1px solid #f00; padding: 5px; color: #f00; font-weight: bold;">',
                 $DR_TEXT['IMPORT_ERRORS'],
                 "<br />\n";
            foreach ( $result['errors'] as $droplet => $error ) {
                echo 'Droplet: ', $droplet, '<br />',
                     '<span style="padding-left: 15px">',
                     $error,
                     '</span>';
            }
            echo "</div>\n";
        }

        echo '<div class="drok">',
             $result['count'], " ", $DR_TEXT['IMPORTED'],
             '</div>';
             
        return $result['imported'];
    }
}   // end function wb_handle_upload()

function wb_unpack_and_import( $temp_file, $temp_unzip ) {

    global $admin, $database;

    // Include the PclZip class file
    require_once(WB_PATH.'/include/pclzip/pclzip.lib.php');
    
    $errors  = array();
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
                if ( preg_match( '/^(.*)\.php$/i', $file, $name_match ) ) {
                    // Name of the Droplet = Filename
                    $name  = $name_match[1];
                    // Slurp file contents
                    $lines = file( $temp_unzip.'/'.$file );
                    // First line: Description
                    if ( preg_match( '#^//\:(.*)$#', $lines[0], $match ) ) {
                        $description = $match[1];
                    }
                    // Second line: Usage instructions
                    if ( preg_match( '#^//\:(.*)$#', $lines[1], $match ) ) {
                        $usage       = addslashes( $match[1] );
                    }
                    // Remaining: Droplet code
                    $code = implode( '', array_slice( $lines, 2 ) );
                    // replace 'evil' chars in code
                    $tags = array('<?php', '?>' , '<?');
                    $code = addslashes(str_replace($tags, '', $code));
                    // Already in the DB?
                    $stmt  = 'INSERT';
                    $id    = NULL;
                    $found = $database->get_one("SELECT * FROM ".TABLE_PREFIX."mod_droplets WHERE name='$name'");
                    if ( $found && $found > 0 ) {
                        $stmt = 'REPLACE';
                        $id   = $found;
                    }
                    // execute
                    $result = $database->query("$stmt INTO ".TABLE_PREFIX."mod_droplets VALUES('$id','$name','$code','$description','".time()."','".$admin->get_user_id()."',1,0,0,0,'$usage')");
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
    
    return array( 'count' => $count, 'errors' => $errors, 'imported'=> $imports );
    
}   // end function wb_unpack_and_import()

function wb_handle_export( $filename = 'drop_export',$export_id = 0 ) {

    global $database, $admin, $MESSAGE;

    $name = NULL;
    $list = isset( $_POST['markeddroplet'] ) ? $_POST['markeddroplet'] : array();

    if ($export_id<>0) $list=$export_id;
    
    if ( ! is_array( $list ) ) {
        $list = array( $list );
    }
    if ( count( $list ) < 1 AND $export_id==0) {
        echo '<div class="drfail">Please mark some Droplets first!</div>';
        return;
    }

    $temp_dir  = WB_PATH.'/temp/droplets/';

    // make the temporary working directory
    @mkdir($temp_dir);
    foreach ( $list as $id ) {
 	    // Added by PCWacht
		// Get id - needed $admin to be global!
		if ( version_compare(WB_VERSION, '2.8.2', '>=')  && WB_VERSION<>"2.8.x" AND $export_id==0) {
  			$id = $admin->checkIDKEY($id, false, '');
  			if (!$id) {
    			$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
    		exit();
  			}
		}

	    // End add
       $result = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_droplets WHERE id='$id'");
        if ( $result->numRows() > 0 ) {
            $droplet = $result->fetchRow();
            $name    = $droplet["name"];
            echo 'Saving: '.$name.'.php<br />';
        	  $sFile = $temp_dir.$name.'.php';
        	  $fh = fopen($sFile, 'w') ;
        	  fwrite($fh, '//:'.$droplet['description']."\n");
        	  fwrite($fh, '//:'.str_replace("\n"," ",$droplet['comments'])."\n");
        	  fwrite($fh, $droplet['code']);
        	  fclose($fh);
        }
    }

    // if there's only a single droplet to export, name the zip-file after this droplet
    if ( count( $list ) === 1 ) {
        $filename = 'droplet_'.$name;
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

    $temp_file = WB_PATH.'/temp/'.$filename.'.zip';

    // create zip
    require_once(WB_PATH.'/include/pclzip/pclzip.lib.php');
    $archive   = new PclZip($temp_file);
    $file_list = $archive->create($temp_dir, PCLZIP_OPT_REMOVE_ALL_PATH);
    if ($file_list == 0){
    	  echo "Packaging error: ", $archive->errorInfo(true), "<br />";
    	  die("Error : ".$archive->errorInfo(true));
    }
    else {
        // create the export folder if it doesn't exist
        if ( ! file_exists( WB_PATH.'/modules/droplets/export' ) ) {
            mkdir(WB_PATH.'/modules/droplets/export');
        }
        if ( ! copy( $temp_file, WB_PATH.'/modules/droplets/export/'.$filename.'.zip' ) ) {
            echo '<div class="drfail">Unable to move the exported ZIP-File!</div>';
            $download = WB_URL.'/temp/'.$filename.'.zip';
        }
        else {
            unlink( $temp_file );
            $download = WB_URL.'/modules/droplets/export/'.$filename.'.zip';
        }
    	  echo '<div class="drok">Backup created - <a href="'.$download.'">Download</a></div>';
    }
    rm_full_dir($temp_dir);

}   // end function wb_handle_export()

function wb_handle_delete() {
    global $database,$admin,$MESSAGE;
    
    $list = isset( $_POST['markeddroplet'] ) ? $_POST['markeddroplet'] : array();
    if ( ! is_array( $list ) ) { $list = array( $list ); }
    if ( count( $list ) < 1 ) {
        // error message already set by wb_handle_export()
        return;
    }
    foreach ( $list as $id ) {
	    // Added by PCWacht
		// Get id - needed $admin to be global!
		if ( version_compare(WB_VERSION, '2.8.2', '>=')  && WB_VERSION<>"2.8.x") {
  			$id = $admin->checkIDKEY($id, false, '');
  			if (!$id) {
    			$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
    		exit();
  			}
		}
		// First export before delete 
		wb_handle_export( $filename = 'delete_export',$export_id = $id );

	    // End add
        $result = $database->query("DELETE FROM ".TABLE_PREFIX."mod_droplets WHERE id='$id'");
    }
}   // end function wb_handle_delete()

?>