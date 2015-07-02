<?php
/**
 *
 * @category        frontend
 * @package         framework
 * @author          WebsiteBaker Project
 * @copyright       Ryan Djurovich
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: functions.php 1601 2012-02-07 22:48:27Z Luisehahne $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/tags/2.8.3/wb/framework/functions.php $
 * @lastmodified    $Date: 2012-02-07 23:48:27 +0100 (Di, 07. Feb 2012) $
 *
*/
/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
    require_once(dirname(__FILE__).'/globalExceptionHandler.php');
    throw new IllegalFileException();
}
/* -------------------------------------------------------- */
// Define that this file has been loaded
define('FUNCTIONS_FILE_LOADED', true);

/**
 * @description: recursively delete a non empty directory
 * @param string $directory :
 * @param bool $empty : true if you want the folder just emptied, but not deleted
 *                      false, or just simply leave it out, the given directory will be deleted, as well
 * @return boolean: list of ro-dirs
 * @from http://www.php.net/manual/de/function.rmdir.php#98499
 */
function rm_full_dir($directory, $empty = false) {
    
    if(substr($directory,-1) == "/") {
        $directory = substr($directory,0,-1);
    }
   // If suplied dirname is a file then unlink it
    if (is_file( $directory )) {
      $retval = unlink($directory);
      clearstatcache();
      return $retval;
    }
    if(!file_exists($directory) || !is_dir($directory)) {
        return false;
    } elseif(!is_readable($directory)) {
        return false;
    } else {
        $directoryHandle = opendir($directory);
        while ($contents = readdir($directoryHandle))
        {
            if($contents != '.' && $contents != '..')
            {
                $path = $directory . "/" . $contents;
                if(is_dir($path)) {
                    rm_full_dir($path);
                } else {
                    unlink($path);
                    clearstatcache();
                }
            }
        }
        closedir($directoryHandle);
        if($empty == false) {
            if(!rmdir($directory)) {
                return false;
            }
        }
        return true;
    }
}

/*
 * returns a recursive list of all subdirectories from a given directory
 * @access  public
 * @param   string  $directory: from this dir the recursion will start
 * @param   bool    $show_hidden:  if set to TRUE also hidden dirs (.dir) will be shown
 * @return  array
 * example:
 *  /srv/www/httpdocs/wb/media/a/b/c/
 *  /srv/www/httpdocs/wb/media/a/b/d/
 * directory_list('/srv/www/httpdocs/wb/media/') will return:
 *  /a
 *  /a/b
 *  /a/b/c
 *  /a/b/d
 */
 function directory_list($directory, $show_hidden = false)
{
    $result_list = array();
    if (is_dir($directory))
    {
        $dir = dir($directory); // Open the directory
        while (false !== $entry = $dir->read()) // loop through the directory
        {
            if($entry == '.' || $entry == '..') { continue; } // Skip pointers
            if($entry[0] == '.' && $show_hidden == false) { continue; } // Skip hidden files
            if (is_dir("$directory/$entry")) { // Add dir and contents to list
                $result_list = array_merge($result_list, directory_list("$directory/$entry"));
                $result_list[] = "$directory/$entry";
            }
        }
        $dir->close();
    }
    // sorting
    if(natcasesort($result_list)) {
        // new indexing
        $result_list = array_merge($result_list);
    }
    return $result_list; // Now return the list
}

// Function to open a directory and add to a dir list
function chmod_directory_contents($directory, $file_mode)
{
    if (is_dir($directory))
    {
        // Set the umask to 0
        $umask = umask(0);
        // Open the directory then loop through its contents
        $dir = dir($directory);
        while (false !== $entry = $dir->read())
        {
            // Skip pointers
            if($entry[0] == '.') { continue; }
            // Chmod the sub-dirs contents
            if(is_dir("$directory/$entry")) {
                chmod_directory_contents($directory.'/'.$entry, $file_mode);
            }
            change_mode($directory.'/'.$entry);
        }
        $dir->close();
        // Restore the umask
        umask($umask);
    }
}

/**
* Scan a given directory for dirs and files.
*
* usage: scan_current_dir ($root = '' )
*
* @param     $root   set a absolute rootpath as string. if root is empty the current path will be scan
* @param     $search set a search pattern for files, empty search brings all files
* @access    public
* @return    array    returns a natsort array with keys 'path' and 'filename'
*
*/
if(!function_exists('scan_current_dir'))
{
    function scan_current_dir($root = '', $search = '/.*/')
    {
        $FILE = array();
        $array = array();
        clearstatcache();
        $root = empty ($root) ? getcwd() : $root;
        if (($handle = opendir($root)))
        {
        // Loop through the files and dirs an add to list  DIRECTORY_SEPARATOR
            while (false !== ($file = readdir($handle)))
            {
                if (substr($file, 0, 1) != '.' && $file != 'index.php')
                {
                    if (is_dir($root.'/'.$file)) {
                        $FILE['path'][] = $file;
                    } elseif (preg_match($search, $file, $array) ) {
                        $FILE['filename'][] = $array[0];
                    }
                }
            }
            $close_verz = closedir($handle);
        }
        // sorting
        if (isset ($FILE['path']) && natcasesort($FILE['path'])) {
            // new indexing
            $FILE['path'] = array_merge($FILE['path']);
        }
        // sorting
        if (isset ($FILE['filename']) && natcasesort($FILE['filename'])) {
            // new indexing
            $FILE['filename'] = array_merge($FILE['filename']);
        }
        return $FILE;
    }
}

// Function to open a directory and add to a file list
function file_list($directory, $skip = array(), $show_hidden = false)
{
    $result_list = array();
    if (is_dir($directory))
    {
        $dir = dir($directory); // Open the directory
        while (false !== ($entry = $dir->read())) // loop through the directory
        {
            if($entry == '.' || $entry == '..') { continue; } // Skip pointers
            if($entry[0] == '.' && $show_hidden == false) { continue; } // Skip hidden files
            if( sizeof($skip) > 0 && in_array($entry, $skip) ) { continue; } // Check if we to skip anything else
            if(is_file( $directory.'/'.$entry)) { // Add files to list
                $result_list[] = $directory.'/'.$entry;
            }
        }
        $dir->close(); // Now close the folder object
    }

    // make the list nice. Not all OS do this itself
    if(natcasesort($result_list)) {
        $result_list = array_merge($result_list);
    }
    return $result_list;
}

// Function to get a list of home folders not to show
function get_home_folders()
{
    global $database, $admin;
    $home_folders = array();
    // Only return home folders is this feature is enabled
    // and user is not admin
//    if(HOME_FOLDERS AND ($_SESSION['GROUP_ID']!='1')) {
    if(HOME_FOLDERS AND (!in_array('1',explode(',', $_SESSION['GROUPS_ID']))))
    {
        $sql  = 'SELECT `home_folder` FROM `'.TABLE_PREFIX.'users` ';
        $sql .= 'WHERE `home_folder`!=\''.$admin->get_home_folder().'\'';
        $query_home_folders = $database->query($sql);
        if($query_home_folders->numRows() > 0)
        {
            while($folder = $query_home_folders->fetchRow()) {
                $home_folders[$folder['home_folder']] = $folder['home_folder'];
            }
        }
        function remove_home_subs($directory = '/', $home_folders = '')
        {
            if( ($handle = opendir(WB_PATH.MEDIA_DIRECTORY.$directory)) )
            {
                // Loop through the dirs to check the home folders sub-dirs are not shown
                while(false !== ($file = readdir($handle)))
                {
                    if($file[0] != '.' && $file != 'index.php')
                    {
                        if(is_dir(WB_PATH.MEDIA_DIRECTORY.$directory.'/'.$file))
                        {
                            if($directory != '/') {
                                $file = $directory.'/'.$file;
                            }else {
                                $file = '/'.$file;
                            }
                            foreach($home_folders AS $hf)
                            {
                                $hf_length = strlen($hf);
                                if($hf_length > 0) {
                                    if(substr($file, 0, $hf_length+1) == $hf) {
                                        $home_folders[$file] = $file;
                                    }
                                }
                            }
                            $home_folders = remove_home_subs($file, $home_folders);
                        }
                    }
                }
            }
            return $home_folders;
        }
        $home_folders = remove_home_subs('/', $home_folders);
    }
    return $home_folders;
}

/*
 * @param object &$wb: $wb from frontend or $admin from backend
 * @return array: list of new entries
 * @description: callback remove path in files/dirs stored in array
 * @example: array_walk($array,'remove_path',PATH);
 */
//
function remove_path(&$path, $key, $vars = '')
{
    $path = str_replace($vars, '', $path);
}

/*
 * @param object &$wb: $wb from frontend or $admin from backend
 * @return array: list of ro-dirs
 * @description: returns a list of directories beyound /wb/media which are ReadOnly for current user
 */
function media_dirs_ro( &$wb )
{
    global $database;
    // if user is admin or home-folders not activated then there are no restrictions
    $allow_list = array();
    if( $wb->get_user_id() == 1 || !HOME_FOLDERS ) {
        return array();
    }
    // at first read any dir and subdir from /media
    $full_list = directory_list( WB_PATH.MEDIA_DIRECTORY );
    // add own home_folder to allow-list
    if( $wb->get_home_folder() ) {
        // old: $allow_list[] = get_home_folder();
        $allow_list[] = $wb->get_home_folder();
    }
    // get groups of current user
    $curr_groups = $wb->get_groups_id();
    // if current user is in admin-group
    if( ($admin_key = array_search('1', $curr_groups)) !== false)
    {
        // remove admin-group from list
        unset($curr_groups[$admin_key]);
        // search for all users where the current user is admin from
        foreach( $curr_groups as $group)
        {
            $sql  = 'SELECT `home_folder` FROM `'.TABLE_PREFIX.'users` ';
            $sql .= 'WHERE (FIND_IN_SET(\''.$group.'\', `groups_id`) > 0) AND `home_folder` <> \'\' AND `user_id` <> '.$wb->get_user_id();
            if( ($res_hf = $database->query($sql)) != null ) {
                while( $rec_hf = $res_hf->fetchrow() ) {
                    $allow_list[] = $rec_hf['home_folder'];
                }
            }
        }
    }
    $tmp_array = $full_list;
    // create a list for readonly dir
    $array = array();
    while( sizeof($tmp_array) > 0)
    {
        $tmp = array_shift($tmp_array);
        $x = 0;
        while($x < sizeof($allow_list)) {
            if(strpos ($tmp,$allow_list[$x])) {
                $array[] = $tmp;
            }
            $x++;
        }
    }
    $full_list = array_diff( $full_list, $array );
    $tmp = array();
    $full_list = array_merge($tmp,$full_list);
    return $full_list;
}

/*
 * @param object &$wb: $wb from frontend or $admin from backend
 * @return array: list of rw-dirs
 * @description: returns a list of directories beyound /wb/media which are ReadWrite for current user
 */
function media_dirs_rw ( &$wb )
{
    global $database;
    // if user is admin or home-folders not activated then there are no restrictions
    // at first read any dir and subdir from /media
    $full_list = directory_list( WB_PATH.MEDIA_DIRECTORY );
    $array = array();
    $allow_list = array();
    if( ($wb->ami_group_member('1')) && !HOME_FOLDERS ) {
        return $full_list;
    }
    // add own home_folder to allow-list
    if( $wb->get_home_folder() ) {
          $allow_list[] = $wb->get_home_folder();
    } else {
        $array = $full_list;
    }
    // get groups of current user
    $curr_groups = $wb->get_groups_id();
    // if current user is in admin-group
    if( ($admin_key = array_search('1', $curr_groups)) == true)
    {
        // remove admin-group from list
        // unset($curr_groups[$admin_key]);
        // search for all users where the current user is admin from
        foreach( $curr_groups as $group)
        {
            $sql  = 'SELECT `home_folder` FROM `'.TABLE_PREFIX.'users` ';
            $sql .= 'WHERE (FIND_IN_SET(\''.$group.'\', `groups_id`) > 0) AND `home_folder` <> \'\' AND `user_id` <> '.$wb->get_user_id();
            if( ($res_hf = $database->query($sql)) != null ) {
                while( $rec_hf = $res_hf->fetchrow() ) {
                    $allow_list[] = $rec_hf['home_folder'];
                }
            }
        }
    }

    $tmp_array = $full_list;
    // create a list for readwrite dir
    while( sizeof($tmp_array) > 0)
    {
        $tmp = array_shift($tmp_array);
        $x = 0;
        while($x < sizeof($allow_list)) {
            if(strpos ($tmp,$allow_list[$x])) {
                $array[] = $tmp;
            }
            $x++;
        }
    }
    $tmp = array();
    $array = array_unique($array);
    $full_list = array_merge($tmp,$array);
    unset($array);
    unset($allow_list);
    return $full_list;
}

// Function to create directories
function make_dir($dir_name, $dir_mode = OCTAL_DIR_MODE, $recursive=true)
{
    $retVal = false;
    if(!is_dir($dir_name))
    {
        $retVal = mkdir($dir_name, $dir_mode,$recursive);
    }
    return $retVal;
}

// Function to chmod files and directories
function change_mode($name)
{
    if(OPERATING_SYSTEM != 'windows')
    {
        // Only chmod if os is not windows
        if(is_dir($name)) {
            $mode = OCTAL_DIR_MODE;
        }else {
            $mode = OCTAL_FILE_MODE;
        }
        if(file_exists($name)) {
            $umask = umask(0);
            chmod($name, $mode);
            umask($umask);
            return true;
        }else {
            return false;
        }
    }else {
        return true;
    }
}

// Function to figure out if a parent exists
function is_parent($page_id)
{
    global $database;
    // Get parent
    $sql = 'SELECT `parent` FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.$page_id;
    $parent = $database->get_one($sql);
    // If parent isnt 0 return its ID
    if(is_null($parent)) {
        return false;
    }else {
        return $parent;
    }
}

// Function to work out level
function level_count($page_id)
{
    global $database;
    // Get page parent
    $sql = 'SELECT `parent` FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.$page_id;
    $parent = $database->get_one($sql);
    if($parent > 0)
    {    // Get the level of the parent
        $sql = 'SELECT `level` FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.$parent;
        $level = $database->get_one($sql);
        return $level+1;
    }else {
        return 0;
    }
}

// Function to work out root parent
function root_parent($page_id)
{
    global $database;
    // Get page details
    $sql = 'SELECT `parent`, `level` FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.$page_id;
    $query_page = $database->query($sql);
    $fetch_page = $query_page->fetchRow();
    $parent = $fetch_page['parent'];
    $level = $fetch_page['level'];
    if($level == 1) {
        return $parent;
    }elseif($parent == 0) {
        return $page_id;
    }else {    // Figure out what the root parents id is
        $parent_ids = array_reverse(get_parent_ids($page_id));
        return $parent_ids[0];
    }
}

// Function to get page title
function get_page_title($id)
{
    global $database;
    // Get title
    $sql = 'SELECT `page_title` FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.$id;
    $page_title = $database->get_one($sql);
    return $page_title;
}

// Function to get a pages menu title
function get_menu_title($id)
{
    global $database;
    // Get title
    $sql = 'SELECT `menu_title` FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.$id;
    $menu_title = $database->get_one($sql);
    return $menu_title;
}

// Function to get all parent page titles
function get_parent_titles($parent_id)
{
    $titles[] = get_menu_title($parent_id);
    if(is_parent($parent_id) != false) {
        $parent_titles = get_parent_titles(is_parent($parent_id));
        $titles = array_merge($titles, $parent_titles);
    }
    return $titles;
}

// Function to get all parent page id's
function get_parent_ids($parent_id)
{
    $ids[] = $parent_id;
    if(is_parent($parent_id) != false) {
        $parent_ids = get_parent_ids(is_parent($parent_id));
        $ids = array_merge($ids, $parent_ids);
    }
    return $ids;
}

// Function to genereate page trail
function get_page_trail($page_id)
{
    return implode(',', array_reverse(get_parent_ids($page_id)));
}

// Function to get all sub pages id's
function get_subs($parent, array $subs )
{
    // Connect to the database
    global $database;
    // Get id's
    $sql = 'SELECT `page_id` FROM `'.TABLE_PREFIX.'pages` WHERE `parent` = '.$parent;
    if( ($query = $database->query($sql)) ) {
        while($fetch = $query->fetchRow()) {
            $subs[] = $fetch['page_id'];
            // Get subs of this sub recursive
            $subs = get_subs($fetch['page_id'], $subs);
        }
    }
    // Return subs array
    return $subs;
}

// Function as replacement for php's htmlspecialchars()
// Will not mangle HTML-entities
function my_htmlspecialchars($string)
{
    $string = preg_replace('/&(?=[#a-z0-9]+;)/i', '__amp;_', $string);
    $string = strtr($string, array('<'=>'&lt;', '>'=>'&gt;', '&'=>'&amp;', '"'=>'&quot;', '\''=>'&#39;'));
    $string = preg_replace('/__amp;_(?=[#a-z0-9]+;)/i', '&', $string);
    return($string);
}

// Convert a string from mixed html-entities/umlauts to pure $charset_out-umlauts
// Will replace all numeric and named entities except &gt; &lt; &apos; &quot; &#039; &nbsp;
// In case of error the returned string is unchanged, and a message is emitted.
function entities_to_umlauts($string, $charset_out=DEFAULT_CHARSET)
{
    require_once(WB_PATH.'/framework/functions-utf8.php');
    return entities_to_umlauts2($string, $charset_out);
}

// Will convert a string in $charset_in encoding to a pure ASCII string with HTML-entities.
// In case of error the returned string is unchanged, and a message is emitted.
function umlauts_to_entities($string, $charset_in=DEFAULT_CHARSET)
{
    require_once(WB_PATH.'/framework/functions-utf8.php');
    return umlauts_to_entities2($string, $charset_in);
}

// Function to convert a page title to a page filename
function page_filename($string)
{
    require_once(WB_PATH.'/framework/functions-utf8.php');
    $string = entities_to_7bit($string);
    // Now remove all bad characters
    $bad = array(
    '\'', /* /  */ '"', /* " */    '<', /* < */    '>', /* > */
    '{', /* { */    '}', /* } */    '[', /* [ */    ']', /* ] */    '`', /* ` */
    '!', /* ! */    '@', /* @ */    '#', /* # */    '$', /* $ */    '%', /* % */
    '^', /* ^ */    '&', /* & */    '*', /* * */    '(', /* ( */    ')', /* ) */
    '=', /* = */    '+', /* + */    '|', /* | */    '/', /* / */    '\\', /* \ */
    ';', /* ; */    ':', /* : */    ',', /* , */    '?' /* ? */
    );
    $string = str_replace($bad, '', $string);
    // replace multiple dots in filename to single dot and (multiple) dots at the end of the filename to nothing
    $string = preg_replace(array('/\.+/', '/\.+$/'), array('.', ''), $string);
    // Now replace spaces with page spcacer
    $string = trim($string);
    $string = preg_replace('/(\s)+/', PAGE_SPACER, $string);
    // Now convert to lower-case
    $string = strtolower($string);
    // If there are any weird language characters, this will protect us against possible problems they could cause
    $string = str_replace(array('%2F', '%'), array('/', ''), urlencode($string));
    // Finally, return the cleaned string
    return $string;
}

// Function to convert a desired media filename to a clean mediafilename
function media_filename($string)
{
    require_once(WB_PATH.'/framework/functions-utf8.php');
    $string = entities_to_7bit($string);
    // Now remove all bad characters
    $bad = array('\'','"','`','!','@','#','$','%','^','&','*','=','+','|','/','\\',';',':',',','?');
    $string = str_replace($bad, '', $string);
    // replace multiple dots in filename to single dot and (multiple) dots at the end of the filename to nothing
    $string = preg_replace(array('/\.+/', '/\.+$/', '/\s/'), array('.', '', '_'), $string);
    // Clean any page spacers at the end of string
    $string = trim($string);
    // Finally, return the cleaned string
    return $string;
}

// Function to work out a page link
if(!function_exists('page_link'))
{
    function page_link($link)
    {
        global $admin;
        return $admin->page_link($link);
    }
}

// Create a new directory and/or protected file in the given directory
function createFolderProtectFile($sAbsDir='',$make_dir=true)
{
    global $admin, $MESSAGE;
    $retVal = array();
    $wb_path = rtrim(str_replace('\/\\', '/', WB_PATH), '/');
    if( ($sAbsDir=='') || ($sAbsDir == $wb_path) ) { return $retVal;}

    if ( $make_dir==true ) {
        // Check to see if the folder already exists
        if(file_exists($sAbsDir)) {
            // $admin->print_error($MESSAGE['MEDIA_DIR_EXISTS']);
            $retVal[] = basename($sAbsDir).'::'.$MESSAGE['MEDIA_DIR_EXISTS'];
        }
        if (!is_dir($sAbsDir) && !make_dir($sAbsDir) ) {
            // $admin->print_error($MESSAGE['MEDIA_DIR_NOT_MADE']);
            $retVal[] = basename($sAbsDir).'::'.$MESSAGE['MEDIA_DIR_NOT_MADE'];
        } else {
            change_mode($sAbsDir);
        }
    }

    if( is_writable($sAbsDir) )
    {
        // if(file_exists($sAbsDir.'/index.php')) { unlink($sAbsDir.'/index.php'); }
        // Create default "index.php" file
        $rel_pages_dir = str_replace($wb_path, '', dirname($sAbsDir) );
        $step_back = str_repeat( '../', substr_count($rel_pages_dir, '/')+1 );

        $sResponse  = $_SERVER['SERVER_PROTOCOL'].' 301 Moved Permanently';
        $content =
            '<?php'."\n".
            '// *** This file is generated by WebsiteBaker Ver.'.VERSION."\n".
            '// *** Creation date: '.date('c')."\n".
            '// *** Do not modify this file manually'."\n".
            '// *** WB will rebuild this file from time to time!!'."\n".
            '// *************************************************'."\n".
            "\t".'header(\''.$sResponse.'\');'."\n".
            "\t".'header(\'Location: '.WB_URL.'/index.php\');'."\n".
            '// *************************************************'."\n";
        $filename = $sAbsDir.'/index.php';

        // write content into file
          if(is_writable($filename) || !file_exists($filename)) {
              if(file_put_contents($filename, $content)) {
        //    print 'create => '.str_replace( $wb_path,'',$filename).'<br />';
                  change_mode($filename, 'file');
              }else {
            $retVal[] = $MESSAGE['GENERIC_BAD_PERMISSIONS'].' :: '.$filename;
           }
          }
         } else {
           $retVal[] = $MESSAGE['GENERIC_BAD_PERMISSIONS'];
         }
         return $retVal;
}

function rebuildFolderProtectFile($dir='')
{
    global $MESSAGE;
    $retVal = array();
    $dir = rtrim(str_replace('\/\\', '/', $dir), '/');
    try {
        $files = array();
        $files[] = $dir;
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) as $fileInfo) {
            $files[] = $fileInfo->getPath();
        }
        $files = array_unique($files);
        foreach( $files as $file) {
            $protect_file = rtrim(str_replace('\/\\', '/', $file), '/');
            $retVal[] = createFolderProtectFile($protect_file,false);
        }
    } catch ( Exception $e ) {
        $retVal[] = $MESSAGE['MEDIA_DIR_ACCESS_DENIED'];
    }
    return $retVal;
}

// Create a new file in the pages directory
function create_access_file($filename,$page_id,$level)
{
    global $admin, $MESSAGE;
    // First make sure parent folder exists
    $parent_folders = explode('/',str_replace(WB_PATH.PAGES_DIRECTORY, '', dirname($filename)));
    $parents = '';
    foreach($parent_folders AS $parent_folder)
    {
        if($parent_folder != '/' AND $parent_folder != '')
        {
            $parents .= '/'.$parent_folder;
            $acces_file = WB_PATH.PAGES_DIRECTORY.$parents;
            // can only be dirs
            if(!file_exists($acces_file)) {
                if(!make_dir($acces_file)) {
                    $admin->print_error($MESSAGE['PAGES']['CANNOT_CREATE_ACCESS_FILE_FOLDER']);
                }
            }
        }
    }
    // The depth of the page directory in the directory hierarchy
    // '/pages' is at depth 1
    $pages_dir_depth = count(explode('/',PAGES_DIRECTORY))-1;
    // Work-out how many ../'s we need to get to the index page
    $index_location = '';
    for($i = 0; $i < $level + $pages_dir_depth; $i++) {
        $index_location .= '../';
    }
    $content =
        '<?php'."\n".
        '// *** This file is generated by WebsiteBaker Ver.'.VERSION."\n".
        '// *** Creation date: '.date('c')."\n".
        '// *** Do not modify this file manually'."\n".
        '// *** WB will rebuild this file from time to time!!'."\n".
        '// *************************************************'."\n".
        "\t".'$page_id    = '.$page_id.';'."\n".
        "\t".'require(\''.$index_location.'index.php\');'."\n".
        '// *************************************************'."\n";

    if( ($handle = fopen($filename, 'w')) ) {
        fwrite($handle, $content);
        fclose($handle);
        // Chmod the file
        change_mode($filename);
    } else {
        $admin->print_error($MESSAGE['PAGES']['CANNOT_CREATE_ACCESS_FILE']);
    }
    return;
 }

// Function for working out a file mime type (if the in-built PHP one is not enabled)
if(!function_exists('mime_content_type'))
{
    function mime_content_type($filename)
    {
        $mime_types = array(
            'txt'    => 'text/plain',
            'htm'    => 'text/html',
            'html'    => 'text/html',
            'php'    => 'text/html',
            'css'    => 'text/css',
            'js'    => 'application/javascript',
            'json'    => 'application/json',
            'xml'    => 'application/xml',
            'swf'    => 'application/x-shockwave-flash',
            'flv'    => 'video/x-flv',

            // images
            'png'    => 'image/png',
            'jpe'    => 'image/jpeg',
            'jpeg'    => 'image/jpeg',
            'jpg'    => 'image/jpeg',
            'gif'    => 'image/gif',
            'bmp'    => 'image/bmp',
            'ico'    => 'image/vnd.microsoft.icon',
            'tiff'    => 'image/tiff',
            'tif'    => 'image/tiff',
            'svg'    => 'image/svg+xml',
            'svgz'    => 'image/svg+xml',

            // archives
            'zip'    => 'application/zip',
            'rar'    => 'application/x-rar-compressed',
            'exe'    => 'application/x-msdownload',
            'msi'    => 'application/x-msdownload',
            'cab'    => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3'    => 'audio/mpeg',
            'mp4'    => 'audio/mpeg',
            'qt'    => 'video/quicktime',
            'mov'    => 'video/quicktime',

            // adobe
            'pdf'    => 'application/pdf',
            'psd'    => 'image/vnd.adobe.photoshop',
            'ai'    => 'application/postscript',
            'eps'    => 'application/postscript',
            'ps'    => 'application/postscript',

            // ms office
            'doc'    => 'application/msword',
            'rtf'    => 'application/rtf',
            'xls'    => 'application/vnd.ms-excel',
            'ppt'    => 'application/vnd.ms-powerpoint',

            // open office
            'odt'    => 'application/vnd.oasis.opendocument.text',
            'ods'    => 'application/vnd.oasis.opendocument.spreadsheet',
        );
        $temp = explode('.',$filename);
        $ext = strtolower(array_pop($temp));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }else {
            return 'application/octet-stream';
        }
    }
}

// Generate a thumbnail from an image
function make_thumb($source, $destination, $size)
{
    // Check if GD is installed
    if(extension_loaded('gd') && function_exists('imageCreateFromJpeg'))
    {
        // First figure out the size of the thumbnail
        list($original_x, $original_y) = getimagesize($source);
        if ($original_x > $original_y) {
            $thumb_w = $size;
            $thumb_h = $original_y*($size/$original_x);
        }
        if ($original_x < $original_y) {
            $thumb_w = $original_x*($size/$original_y);
            $thumb_h = $size;
        }
        if ($original_x == $original_y) {
            $thumb_w = $size;
            $thumb_h = $size;
        }
        // Now make the thumbnail
        $source = imageCreateFromJpeg($source);
        $dst_img = ImageCreateTrueColor($thumb_w, $thumb_h);
        imagecopyresampled($dst_img,$source,0,0,0,0,$thumb_w,$thumb_h,$original_x,$original_y);
        imagejpeg($dst_img, $destination);
        // Clear memory
        imagedestroy($dst_img);
        imagedestroy($source);
       // Return true
        return true;
    } else {
        return false;
    }
}

/*
 * Function to work-out a single part of an octal permission value
 *
 * @param mixed $octal_value: an octal value as string (i.e. '0777') or real octal integer (i.e. 0777 | 777)
 * @param string $who: char or string for whom the permission is asked( U[ser] / G[roup] / O[thers] )
 * @param string $action: char or string with the requested action( r[ead..] / w[rite..] / e|x[ecute..] )
 * @return boolean
 */
function extract_permission($octal_value, $who, $action)
{
    // Make sure that all arguments are set and $octal_value is a real octal-integer
    if(($who == '') || ($action == '') || (preg_match( '/[^0-7]/', (string)$octal_value ))) {
        return false; // invalid argument, so return false
    }
    // convert $octal_value into a decimal-integer to be sure having a valid value
    $right_mask = octdec($octal_value);
    $action_mask = 0;
    // set the $action related bit in $action_mask
    switch($action[0]) { // get action from first char of $action
        case 'r':
        case 'R':
            $action_mask = 4; // set read-bit only (2^2)
            break;
        case 'w':
        case 'W':
            $action_mask = 2; // set write-bit only (2^1)
            break;
        case 'e':
        case 'E':
        case 'x':
        case 'X':
            $action_mask = 1; // set execute-bit only (2^0)
            break;
        default:
            return false; // undefined action name, so return false
    }
    // shift action-mask into the right position
    switch($who[0]) { // get who from first char of $who
        case 'u':
        case 'U':
            $action_mask <<= 3; // shift left 3 bits
        case 'g':
        case 'G':
            $action_mask <<= 3; // shift left 3 bits
        case 'o':
        case 'O':
            /* NOP */
            break;
        default:
            return false; // undefined who, so return false
    }
    return( ($right_mask & $action_mask) != 0 ); // return result of binary-AND
}

// Function to delete a page
    function delete_page($page_id)
    {
        global $admin, $database, $MESSAGE;
        // Find out more about the page
        $sql  = 'SELECT `page_id`, `menu_title`, `page_title`, `level`, ';
        $sql .=        '`link`, `parent`, `modified_by`, `modified_when` ';
        $sql .= 'FROM `'.TABLE_PREFIX.'pages` WHERE `page_id`='.$page_id;
        $results = $database->query($sql);
        if($database->is_error())    { $admin->print_error($database->get_error()); }
        if($results->numRows() == 0) { $admin->print_error($MESSAGE['PAGES']['NOT_FOUND']); }
        $results_array = $results->fetchRow();
        $parent     = $results_array['parent'];
        $level      = $results_array['level'];
        $link       = $results_array['link'];
        $page_title = $results_array['page_title'];
        $menu_title = $results_array['menu_title'];
        // Get the sections that belong to the page
        $sql  = 'SELECT `section_id`, `module` FROM `'.TABLE_PREFIX.'sections` ';
        $sql .= 'WHERE `page_id`='.$page_id;
        $query_sections = $database->query($sql);
        if($query_sections->numRows() > 0)
        {
            while($section = $query_sections->fetchRow()) {
                // Set section id
                $section_id = $section['section_id'];
                // Include the modules delete file if it exists
                if(file_exists(WB_PATH.'/modules/'.$section['module'].'/delete.php')) {
                    include(WB_PATH.'/modules/'.$section['module'].'/delete.php');
                }
            }
        }
        // Update the pages table
        $sql = 'DELETE FROM `'.TABLE_PREFIX.'pages` WHERE `page_id`='.$page_id;
        $database->query($sql);
        if($database->is_error()) {
            $admin->print_error($database->get_error());
        }
        // Update the sections table
        $sql = 'DELETE FROM `'.TABLE_PREFIX.'sections` WHERE `page_id`='.$page_id;
        $database->query($sql);
        if($database->is_error()) {
            $admin->print_error($database->get_error());
        }
        // Include the ordering class or clean-up ordering
        include_once(WB_PATH.'/framework/class.order.php');
        $order = new order(TABLE_PREFIX.'pages', 'position', 'page_id', 'parent');
        $order->clean($parent);
        // Unlink the page access file and directory
        $directory = WB_PATH.PAGES_DIRECTORY.$link;
        $filename = $directory.PAGE_EXTENSION;
        $directory .= '/';
        if(file_exists($filename))
        {
            if(!is_writable(WB_PATH.PAGES_DIRECTORY.'/')) {
                $admin->print_error($MESSAGE['PAGES']['CANNOT_DELETE_ACCESS_FILE']);
            }else {
                unlink($filename);
                if( file_exists($directory) &&
                   (rtrim($directory,'/') != WB_PATH.PAGES_DIRECTORY) &&
                   (substr($link, 0, 1) != '.'))
                {
                    rm_full_dir($directory);
                }
            }
        }
    }

/*
 * @param string $file: name of the file to read
 * @param int $size: number of maximum bytes to read (0 = complete file)
 * @return string: the content as string, false on error
 */
    function getFilePart($file, $size = 0)
    {
        $file_content = '';
        if( file_exists($file) && is_file($file) && is_readable($file))
        {
            if($size == 0) {
                $size = filesize($file);
            }
            if(($fh = fopen($file, 'rb'))) {
                if( ($file_content = fread($fh, $size)) !== false ) {
                    return $file_content;
                }
                fclose($fh);
            }
        }
        return false;
    }

    /**
    * replace varnames with values in a string
    *
    * @param string $subject: stringvariable with vars placeholder
    * @param array $replace: values to replace vars placeholder
    * @return string
    */
    function replace_vars($subject = '', &$replace = null )
    {
        if(is_array($replace))
        {
            foreach ($replace  as $key => $value) {
                $subject = str_replace("{{".$key."}}", $value, $subject);
            }
        }
        return $subject;
    }

// Load module into DB
function load_module($directory, $install = false)
{
    global $database,$admin,$MESSAGE;
    $retVal = false;
    if(is_dir($directory) && file_exists($directory.'/info.php'))
    {
        require($directory.'/info.php');
        if(isset($module_name))
        {
            if(!isset($module_license)) { $module_license = 'GNU General Public License'; }
            if(!isset($module_platform) && isset($module_designed_for)) { $module_platform = $module_designed_for; }
            if(!isset($module_function) && isset($module_type)) { $module_function = $module_type; }
            $module_function = strtolower($module_function);
            // Check that it doesn't already exist
            $sqlwhere = 'WHERE `type` = \'module\' AND `directory` = \''.$module_directory.'\'';
            $sql  = 'SELECT COUNT(*) FROM `'.TABLE_PREFIX.'addons` '.$sqlwhere;
            if( $database->get_one($sql) ) {
                $sql  = 'UPDATE `'.TABLE_PREFIX.'addons` SET ';
            }else{
                // Load into DB
                $sql  = 'INSERT INTO `'.TABLE_PREFIX.'addons` SET ';
                $sqlwhere = '';
            }
            $sql .= '`directory`=\''.$module_directory.'\', ';
            $sql .= '`name`=\''.$module_name.'\', ';
            $sql .= '`description`=\''.addslashes($module_description).'\', ';
            $sql .= '`type`=\'module\', ';
            $sql .= '`function`=\''.$module_function.'\', ';
            $sql .= '`version`=\''.$module_version.'\', ';
            $sql .= '`platform`=\''.$module_platform.'\', ';
            $sql .= '`author`=\''.addslashes($module_author).'\', ';
            $sql .= '`license`=\''.addslashes($module_license).'\'';
            $sql .= $sqlwhere;
            $retVal = $database->query($sql);
            // Run installation script
            if($install == true) {
                if(file_exists($directory.'/install.php')) {
                    require($directory.'/install.php');
                }
            }
        }
    }
}

// Load template into DB
function load_template($directory)
{
    global $database, $admin;
    $retVal = false;
    if(is_dir($directory) && file_exists($directory.'/info.php'))
    {
        require($directory.'/info.php');
        if(isset($template_name))
        {
            if(!isset($template_license)) {
              $template_license = 'GNU General Public License';
            }
            if(!isset($template_platform) && isset($template_designed_for)) {
              $template_platform = $template_designed_for;
            }
            if(!isset($template_function)) {
              $template_function = 'template';
            }
            // Check that it doesn't already exist
            $sqlwhere = 'WHERE `type`=\'template\' AND `directory`=\''.$template_directory.'\'';
            $sql  = 'SELECT COUNT(*) FROM `'.TABLE_PREFIX.'addons` '.$sqlwhere;
            if( $database->get_one($sql) ) {
                $sql  = 'UPDATE `'.TABLE_PREFIX.'addons` SET ';
            }else{
                // Load into DB
                $sql  = 'INSERT INTO `'.TABLE_PREFIX.'addons` SET ';
                $sqlwhere = '';
            }
            $sql .= '`directory`=\''.$template_directory.'\', ';
            $sql .= '`name`=\''.$template_name.'\', ';
            $sql .= '`description`=\''.addslashes($template_description).'\', ';
            $sql .= '`type`=\'template\', ';
            $sql .= '`function`=\''.$template_function.'\', ';
            $sql .= '`version`=\''.$template_version.'\', ';
            $sql .= '`platform`=\''.$template_platform.'\', ';
            $sql .= '`author`=\''.addslashes($template_author).'\', ';
            $sql .= '`license`=\''.addslashes($template_license).'\' ';
            $sql .= $sqlwhere;
            $retVal = $database->query($sql);
        }
    }
    return $retVal;
}

// Load language into DB
function load_language($file)
{
    global $database,$admin;
    $retVal = false;
    if (file_exists($file) && preg_match('#^([A-Z]{2}.php)#', basename($file)))
    {
        // require($file);  it's to large
        // read contents of the template language file into string
        $data = @file_get_contents(WB_PATH.'/languages/'.str_replace('.php','',basename($file)).'.php');
        // use regular expressions to fetch the content of the variable from the string
        $language_name = get_variable_content('language_name', $data, false, false);
        $language_code = get_variable_content('language_code', $data, false, false);
        $language_author = get_variable_content('language_author', $data, false, false);
        $language_version = get_variable_content('language_version', $data, false, false);
        $language_platform = get_variable_content('language_platform', $data, false, false);

        if(isset($language_name))
        {
            if(!isset($language_license)) { $language_license = 'GNU General Public License'; }
            if(!isset($language_platform) && isset($language_designed_for)) { $language_platform = $language_designed_for; }
            // Check that it doesn't already exist
            $sqlwhere = 'WHERE `type`=\'language\' AND `directory`=\''.$language_code.'\'';
            $sql  = 'SELECT COUNT(*) FROM `'.TABLE_PREFIX.'addons` '.$sqlwhere;
            if( $database->get_one($sql) ) {
                $sql  = 'UPDATE `'.TABLE_PREFIX.'addons` SET ';
            }else{
                // Load into DB
                $sql  = 'INSERT INTO `'.TABLE_PREFIX.'addons` SET ';
                $sqlwhere = '';
            }
            $sql .= '`directory`=\''.$language_code.'\', ';
            $sql .= '`name`=\''.$language_name.'\', ';
            $sql .= '`type`=\'language\', ';
            $sql .= '`version`=\''.$language_version.'\', ';
            $sql .= '`platform`=\''.$language_platform.'\', ';
            $sql .= '`author`=\''.addslashes($language_author).'\', ';
            $sql .= '`license`=\''.addslashes($language_license).'\' ';
            $sql .= $sqlwhere;
            $retVal = $database->query($sql);
        }
    }
    return $retVal;
}

// Upgrade module info in DB, optionally start upgrade script
function upgrade_module($directory, $upgrade = false)
{
    global $database, $admin, $MESSAGE, $new_module_version;
    $mod_directory = WB_PATH.'/modules/'.$directory;
    if(file_exists($mod_directory.'/info.php'))
    {
        require($mod_directory.'/info.php');
        if(isset($module_name))
        {
            if(!isset($module_license)) { $module_license = 'GNU General Public License'; }
            if(!isset($module_platform) && isset($module_designed_for)) { $module_platform = $module_designed_for; }
            if(!isset($module_function) && isset($module_type)) { $module_function = $module_type; }
            $module_function = strtolower($module_function);
            // Check that it does already exist
            $sql  = 'SELECT COUNT(*) FROM `'.TABLE_PREFIX.'addons` ';
            $sql .= 'WHERE `directory`=\''.$module_directory.'\'';
            if( $database->get_one($sql) )
            {
                // Update in DB
                $sql  = 'UPDATE `'.TABLE_PREFIX.'addons` SET ';
                $sql .= '`version`=\''.$module_version.'\', ';
                $sql .= '`description`=\''.addslashes($module_description).'\', ';
                $sql .= '`platform`=\''.$module_platform.'\', ';
                $sql .= '`author`=\''.addslashes($module_author).'\', ';
                $sql .= '`license`=\''.addslashes($module_license).'\' ';
                $sql .= 'WHERE `directory`=\''.$module_directory.'\' ';
                $database->query($sql);
                if($database->is_error()) {
                    $admin->print_error($database->get_error());
                }
                // Run upgrade script
                if($upgrade == true) {
                    if(file_exists($mod_directory.'/upgrade.php')) {
                        require($mod_directory.'/upgrade.php');
                    }
                }
            }
        }
    }
}

// extracts the content of a string variable from a string (save alternative to including files)
if(!function_exists('get_variable_content'))
{
    function get_variable_content($search, $data, $striptags=true, $convert_to_entities=true)
    {
        $match = '';
        // search for $variable followed by 0-n whitespace then by = then by 0-n whitespace
        // then either " or ' then 0-n characters then either " or ' followed by 0-n whitespace and ;
        // the variable name is returned in $match[1], the content in $match[3]
        if (preg_match('/(\$' .$search .')\s*=\s*("|\')(.*)\2\s*;/', $data, $match))
        {
            if(strip_tags(trim($match[1])) == '$' .$search) {
                // variable name matches, return it's value
                $match[3] = ($striptags == true) ? strip_tags($match[3]) : $match[3];
                $match[3] = ($convert_to_entities == true) ? htmlentities($match[3]) : $match[3];
                return $match[3];
            }
        }
        return false;
    }
}

/*
 * @param string $modulname: like saved in addons.directory
 * @param boolean $source: true reads from database, false from info.php
 * @return string:  the version as string, if not found returns null
 */

    function get_modul_version($modulname, $source = true)
    {
        global $database;
        $version = null;
        if( $source != true )
        {
            $sql  = 'SELECT `version` FROM `'.TABLE_PREFIX.'addons` ';
            $sql .= 'WHERE `directory`=\''.$modulname.'\'';
            $version = $database->get_one($sql);
        } else {
            $info_file = WB_PATH.'/modules/'.$modulname.'/info.php';
            if(file_exists($info_file)) {
                if(($info_file = file_get_contents($info_file))) {
                    $version = get_variable_content('module_version', $info_file, false, false);
                    $version = ($version !== false) ? $version : null;
                }
            }
        }
        return $version;
    }

/*
 * @param string $varlist: commaseperated list of varnames to move into global space
 * @return bool:  false if one of the vars already exists in global space (error added to msgQueue)
 */
    function vars2globals_wrapper($varlist)
    {
        $retval = true;
        if( $varlist != '')
        {
            $vars = explode(',', $varlist);
            foreach( $vars as $var)
            {
                if( isset($GLOBALS[$var]) ){
                    ErrorLog::write( 'variabe $'.$var.' already defined in global space!!',__FILE__, __FUNCTION__, __LINE__);
                    $retval = false;
                }else {
                    global $$var;
                }
            }
        }
        return $retval;
    }

/*
 * filter directory traversal more thoroughly, thanks to hal 9000
 * @param string $dir: directory relative to MEDIA_DIRECTORY
 * @param bool $with_media_dir: true when to include MEDIA_DIRECTORY
 * @return: false if directory traversal detected, real path if not
 */
    function check_media_path($directory, $with_media_dir = true)
    {
        $md = ($with_media_dir) ? MEDIA_DIRECTORY : '';
        $dir = realpath(WB_PATH . $md . '/' . utf8_decode($directory));
        $required = realpath(WB_PATH . MEDIA_DIRECTORY);
        if (strstr($dir, $required)) {
            return $dir;
        } else {
            return false;
        }
    }

/*
urlencode function and rawurlencode are mostly based on RFC 1738.
However, since 2005 the current RFC in use for URIs standard is RFC 3986.
Here is a function to encode URLs according to RFC 3986.
*/
if(!function_exists('url_encode')){
    function url_encode($string) {
        $string = html_entity_decode($string,ENT_QUOTES,'UTF-8');
        $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
        $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
        return str_replace($entities,$replacements, rawurlencode($string));
    }
}
