<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

// Define that this file has been loaded
define('FUNCTIONS_FILE_LOADED', true);

/**
 * @brief   recursively remove a non empty directory and all its contents
 * 
 * @param   string $sDirPath  Full path to the directory
 * @param   bool   $empty     true if you want the folder just emptied, but not deleted
 *                            false, or just simply leave it out, the given directory 
 *                            will be deleted, as well
 * @return  bool list of ro-dirs
 * @from    http://www.php.net/manual/de/function.rmdir.php#98499
 */
function rm_full_dir($sDirPath, $empty = false)
{

    if (substr($sDirPath, -1) == "/") {
        $sDirPath = substr($sDirPath, 0, -1);
    }
    // If suplied dirname is a file then unlink it
    if (is_file($sDirPath)) {
        $retval = unlink($sDirPath);
        clearstatcache();
        return $retval;
    }
    if (!file_exists($sDirPath) || !is_dir($sDirPath)) {
        return false;
    } elseif (!is_readable($sDirPath)) {
        return false;
    } else {
        $directoryHandle = opendir($sDirPath);
        while ($contents = readdir($directoryHandle)) {
            if ($contents != '.' && $contents != '..') {
                $path = $sDirPath . "/" . $contents;
                if (is_dir($path)) {
                    rm_full_dir($path);
                } else {
                    unlink($path);
                    clearstatcache();
                }
            }
        }
        closedir($directoryHandle);
        if ($empty == false) {
            if (!rmdir($sDirPath)) {
                return false;
            }
        }
        return true;
    }
}

/**
 * @brief   returns a recursive list (array) of all subdirectories from a given directory
 * 
 * example:
 *  /srv/www/httpdocs/wb/media/a/b/c/
 *  /srv/www/httpdocs/wb/media/a/b/d/
 * directory_list('/srv/www/httpdocs/wb/media/') 
 * will return:
 *  /a
 *  /a/b
 *  /a/b/c
 *  /a/b/d
 * 
 * @param   string  $sDirPath     Full path to the directory
 *                                from this dir the recursion will start
 * @param   bool    $show_hidden  if set to TRUE also hidden dirs (.dir) will be shown
 * @return  array
 */
function directory_list($sDirPath, $show_hidden = false)
{
    $result_list = array();
    if (is_dir($sDirPath)) {
        $dir = dir($sDirPath); // Open the directory
        while (false !== $entry = $dir->read()) // loop through the directory
        {
            if ($entry == '.' || $entry == '..') {continue;} // Skip pointers
            if ($entry[0] == '.' && $show_hidden == false) {continue;} // Skip hidden files
            if (is_dir("$sDirPath/$entry")) {
                // Add dir and contents to list
                $result_list = array_merge($result_list, directory_list($sDirPath . '/' . $entry));
                $result_list[] = $sDirPath . '/' . $entry;
            }
        }
        $dir->close();
    }
    // sorting
    if (natcasesort($result_list)) {
        // new indexing
        $result_list = array_merge($result_list);
    }
    return $result_list; // Now return the list
}

/**
 * @brief Function to open a directory and add to a dir list
 * 
 * @param   string  $sDirPath  Full path to the directory
 * @param   string  $file_mode   
 * @return  ?
 */
function chmod_directory_contents($sDirPath, $file_mode)
{
    if (is_dir($sDirPath)) {
        // Set the umask to 0
        $umask = umask(0);
        // Open the directory then loop through its contents
        $dir = dir($sDirPath);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry[0] == '.') {continue;}
            // Chmod the sub-dirs contents
            if (is_dir("$sDirPath/$entry")) {
                chmod_directory_contents($sDirPath . '/' . $entry, $file_mode);
            }
            change_mode($sDirPath . '/' . $entry);
        }
        $dir->close();
        // Restore the umask
        umask($umask);
    }
}

/**
 * @brief   Scan a given directory for dirs and files. 
 * 
 * usage: 
 * scan_current_dir ($root = '' );
 *
 * @param  string $root    Set a absolute rootpath as string. 
 *                         If root is empty the current path will be scan.
 * @param  string $search  Set a search pattern for files, 
 *                         If set to empty, search brings all files. * 
 * @return array  returns a natsort array with keys 'path' and 'filename'
 *
 */
if (!function_exists('scan_current_dir')) {
    function scan_current_dir($root = '', $search = '/.*/')
    {
        $FILE = array();
        $array = array();
        clearstatcache();
        $root = empty($root) ? getcwd() : $root;
        if (($handle = opendir($root))) {
            // Loop through the files and dirs an add to list  DIRECTORY_SEPARATOR
            while (false !== ($file = readdir($handle))) {
                if (substr($file, 0, 1) != '.' && $file != 'index.php') {
                    if (is_dir($root . '/' . $file)) {
                        $FILE['path'][] = $file;
                    } elseif (preg_match($search, $file, $array)) {
                        $FILE['filename'][] = $array[0];
                    }
                }
            }
            $close_verz = closedir($handle);
        }
        // sorting
        if (isset($FILE['path']) && natcasesort($FILE['path'])) {
            // new indexing
            $FILE['path'] = array_merge($FILE['path']);
        }
        // sorting
        if (isset($FILE['filename']) && natcasesort($FILE['filename'])) {
            // new indexing
            $FILE['filename'] = array_merge($FILE['filename']);
        }
        return $FILE;
    }
}

/**
 * @brief   Function to open a directory and add to a file list
 *
 * @param   string $sDirPath     Full path to the directory 
 * @param   array  $skip  
 * @param   bool   $show_hidden
 * @return  array  
 *
 */
function file_list($sDirPath, $skip = array(), $show_hidden = false)
{
    $result_list = array();
    if (is_dir($sDirPath)) {
        $dir = dir($sDirPath); // Open the directory
        while (false !== ($entry = $dir->read())) // loop through the directory
        {
            if ($entry == '.' || $entry == '..')              continue; // Skip pointers
            if ($entry[0] == '.' && $show_hidden == false)    continue; // Skip hidden files
            if (sizeof($skip) > 0 && in_array($entry, $skip)) continue; // Check if we to skip anything else
            if (is_file($sDirPath . '/' . $entry)) {
                // Add files to list
                $result_list[] = $sDirPath . '/' . $entry;
            }
        }
        $dir->close(); // Now close the folder object
    }

    // Make the list nice. Not all OS do this itself
    if (natcasesort($result_list)) {
        $result_list = array_merge($result_list);
    }
    return $result_list;
}

/**
 * @brief   

 * @return  array  
 */
function get_home_folders()
{
    global $database, $admin;
    $home_folders = array();
    // Only return home folders is this feature is enabled
    // and user is not admin
    //    if(HOME_FOLDERS AND ($_SESSION['GROUP_ID']!='1')) {
    if (HOME_FOLDERS and (!in_array('1', explode(',', $_SESSION['GROUPS_ID'])))) {
        $sSql = 'SELECT `home_folder` FROM `{TP}users` WHERE `home_folder`!=\'' . $admin->get_home_folder() . '\'';
        $query_home_folders = $database->query($sSql);
        if ($query_home_folders->numRows() > 0) {
            while ($folder = $query_home_folders->fetchRow()) {
                $home_folders[$folder['home_folder']] = $folder['home_folder'];
            }
        }
        function remove_home_subs($sDirPath = '/', $home_folders = '')
        {
            if (($handle = opendir(WB_PATH . MEDIA_DIRECTORY . $sDirPath))) {
                // Loop through the dirs to check the home folders sub-dirs are not shown
                while (false !== ($file = readdir($handle))) {
                    if ($file[0] != '.' && $file != 'index.php') {
                        if (is_dir(WB_PATH . MEDIA_DIRECTORY . $sDirPath . '/' . $file)) {
                            if ($sDirPath != '/') {
                                $file = $sDirPath . '/' . $file;
                            } else {
                                $file = '/' . $file;
                            }
                            foreach ($home_folders as $hf) {
                                $hf_length = strlen($hf);
                                if ($hf_length > 0) {
                                    if (substr($file, 0, $hf_length + 1) == $hf) {
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


/**
 * @brief  returns a list of directories beyound /wb/media 
 *         which are ReadOnly for current user
 * 
 * @param  object &$wb: $wb from frontend or $admin from backend
 * @return array: list of ro-dirs
 */
function media_dirs_ro(&$wb)
{
    global $database;
    // if user is admin or home-folders not activated then there are no restrictions
    $allow_list = array();
    if ($wb->get_group_id() == 1 || !HOME_FOLDERS) {
        return array();
    }
    // at first read any dir and subdir from /media
    $full_list = directory_list(WB_PATH . MEDIA_DIRECTORY);
    // add own home_folder to allow-list
    if ($wb->get_home_folder()) {
        $allow_list[] = $wb->get_home_folder();
    }
    $curr_groups = $wb->get_groups_id(); // get groups of current user
    
    // if current user is in admin-group
    if (($admin_key = array_search('1', $curr_groups)) !== false) {        
        unset($curr_groups[$admin_key]); // remove admin-group from list
    
        // search for all users where the current user is admin from
        foreach ($curr_groups as $group) {
            $sSql = 'SELECT `home_folder` FROM `{TP}users` '
                    . 'WHERE (FIND_IN_SET(\'' . $group . '\', `groups_id`) > 0) '
                    . 'AND `home_folder` <> \'\' AND `user_id` <> ' . $wb->get_user_id();
            if (($res_hf = $database->query($sSql)) != null) {
                while ($rec_hf = $res_hf->fetchrow()) {
                    $allow_list[] = $rec_hf['home_folder'];
                }
            }
        }
    }
    $tmp_array = $full_list;
    $array = array(); // create a list for readonly dir
    while (sizeof($tmp_array) > 0) {
        $tmp = array_shift($tmp_array);
        $x = 0;
        while ($x < sizeof($allow_list)) {
            if (strpos($tmp, $allow_list[$x])) {
                $array[] = $tmp;
            }
            $x++;
        }
    }
    $full_list = array_diff($full_list, $array);
    $tmp       = array();
    $full_list = array_merge($tmp, $full_list);
    return $full_list;
}

/**
 * @brief  returns a list of directories beyound /wb/media 
 *         which are ReadWrite for current user
 * 
 * @param  object &$wb $wb from frontend or $admin from backend
 * @return array  list of rw-dirs
 */
function media_dirs_rw(&$wb)
{
    global $database;
    // if user is admin or home-folders not activated then there are no restrictions
    // at first read any dir and subdir from /media
    $full_list = directory_list(WB_PATH . MEDIA_DIRECTORY);
    $array = array();
    $allow_list = array();
    if (($wb->ami_group_member('1')) && !HOME_FOLDERS) {
        return $full_list;
    }
    // add own home_folder to allow-list
    if ($wb->get_home_folder()) {
        $allow_list[] = $wb->get_home_folder();
    } else {
        $array = $full_list;
    }
    // get groups of current user
    $curr_groups = $wb->get_groups_id();
    // if current user is in admin-group
    if (($admin_key = array_search('1', $curr_groups)) == true) {
        // remove admin-group from list
        // unset($curr_groups[$admin_key]);
        // search for all users where the current user is admin from
        foreach ($curr_groups as $group) {
            $sSql = 'SELECT `home_folder` FROM `{TP}users` '
                    . 'WHERE (FIND_IN_SET(\'' . $group . '\', `groups_id`) > 0) '
                    . 'AND `home_folder` <> \'\' AND `user_id` <> '.$wb->get_user_id();
            if (($res_hf = $database->query($sSql)) != null) {
                while ($rec_hf = $res_hf->fetchrow()) {
                    $allow_list[] = $rec_hf['home_folder'];
                }
            }
        }
    }

    $tmp_array = $full_list;
    // create a list for readwrite dir
    while (sizeof($tmp_array) > 0) {
        $tmp = array_shift($tmp_array);
        $x = 0;
        while ($x < sizeof($allow_list)) {
            if (strpos($tmp, $allow_list[$x])) {
                $array[] = $tmp;
            }
            $x++;
        }
    }
    $tmp       = array();
    $array     = array_unique($array);
    $full_list = array_merge($tmp, $array);
    unset($array);
    unset($allow_list);
    return $full_list;
}

/**
 * @brief   Function to create directories
 * 
 * @param   string  $dir_name
 * @param   string  $dir_mode
 * @param   bool    $recursive
 * @return  bool
 */
function make_dir($dir_name, $dir_mode = OCTAL_DIR_MODE, $recursive = true)
{
    $retVal = false;
    if (!is_dir($dir_name)) {
        $umask = umask(0);
        $retVal = mkdir($dir_name, $dir_mode, $recursive);
        umask($umask);
    }
    return $retVal;
}


/**
 * @brief   Function to chmod files and directories
 * 
 * @param   string  $name
 * @return  bool
 */
function change_mode($name)
{
    if (OPERATING_SYSTEM != 'windows') {
        // Only chmod if os is not windows
        if (is_dir($name)) {
            $mode = OCTAL_DIR_MODE;
        } else {
            $mode = OCTAL_FILE_MODE;
        }
        if (file_exists($name)) {
            $umask = umask(0);
            chmod($name, $mode);
            umask($umask);
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}

/**
 * @brief   Function to figure out if a parent of a specific page_id exists
 * 
 * @param   int     $page_id
 * @return  unspec  If parent isn't 0 its ID will be returned
 */
function is_parent($page_id)
{
    $parent = $GLOBALS['database']->get_one('SELECT `parent` FROM `{TP}pages` WHERE `page_id` = '.(int)$page_id);
    return (is_null($parent)) ? false : $parent;
        
}

/**
 * @brief   Function to work out the level of a specific page
 * 
 * @param   int   $page_id
 * @return  int   The level of the page
 */
function level_count($page_id)
{
    global $database;
    // Get page parent
    $iParentID = $database->get_one('SELECT `parent` FROM `{TP}pages` WHERE `page_id` = '.(int)$page_id);
    if ($iParentID > 0) {
        // Get the level of the parent
        $iLevel = $database->get_one('SELECT `level` FROM `{TP}pages` WHERE `page_id` = '.$iParentID);
        return $iLevel + 1;
    } else {
        return 0;
    }
}

/**
 * @brief   Function to work out root parent
 * 
 * @param   int     $page_id
 * @return  unspec  int or array 
 */
function root_parent($page_id)
{
    global $database;
    // Get page details
    $rQueryPage = $database->query('SELECT `parent`, `level` FROM `{TP}pages` WHERE `page_id`='.(int)$page_id);
    $aPageData = $rQueryPage->fetchRow(MYSQL_ASSOC);
    if ($aPageData['level'] == 1) {
        return $aPageData['parent'];
    } elseif ($aPageData['parent'] == 0) {
        return $page_id;
    } else {
        // Figure out what the root parents id is
        $aParentIDs = array_reverse(get_parent_ids($page_id));
        return $aParentIDs[0];
    }
}

/**
 * @brief   Function to get page title
 * 
 * @param   int     $page_id
 * @return  string  The page title
 */
function get_page_title($page_id)
{
    return $GLOBALS['database']->get_one('SELECT `page_title` FROM `{TP}pages` WHERE `page_id`='.(int)$page_id);
}

/**
 * @brief   Function to get a pages menu title
 * 
 * @param   int     $page_id
 * @return  string  The menu title
 */
function get_menu_title($page_id)
{
    return $GLOBALS['database']->get_one('SELECT `menu_title` FROM `{TP}pages` WHERE `page_id`='.(int)$page_id);
}

/**
 * @brief   Function to get all parent page titles
 * 
 * @param   int     $iParentID
 * @return  array 
 */
function get_parent_titles($iParentID)
{
    $aTitles[] = get_menu_title($iParentID);
    if (is_parent($iParentID) != false) {
        $aParentTitles = get_parent_titles(is_parent($iParentID));
        $aTitles = array_merge($aTitles, $aParentTitles);
    }
    return $aTitles;
}

/**
 * @brief   Function to get all parent page id's
 * 
 * @param   int     $iParentID
 * @return  array 
 */
function get_parent_ids($iParentID)
{
    $aIDs[] = $iParentID;
    if (is_parent($iParentID) != false) {
        $aParentIDs = get_parent_ids(is_parent($iParentID));
        $aIDs = array_merge($aIDs, $aParentIDs);
    }
    return $aIDs;
}

/**
 * @brief   Function to genereate page trail
 * 
 * @param   int     $page_id
 * @return  array 
 */
function get_page_trail($page_id)
{
    return implode(',', array_reverse(get_parent_ids($page_id)));
}

/**
 * @brief   Function to get all sub pages id's
 * 
 * @param   int     $iParentID
 * @param   array   $aSubs
 * @return  array 
 */
function get_subs($iParentID, array $aSubs)
{
    global $database;
    $sSql = 'SELECT `page_id` FROM `{TP}pages` WHERE `parent` = '.(int)$iParentID;
    if (($query = $database->query($sSql))) {
        while ($fetch = $query->fetchRow()) {
            $aSubs[] = $fetch['page_id'];
            // Get subs of this sub recursive
            $aSubs = get_subs($fetch['page_id'], $aSubs);
        }
    }
    // Return subs array
    return $aSubs;
}


/**
 * @brief   Function as replacement for php's htmlspecialchars()
 *          Will not mangle HTML-entities
 * 
 * @param   string     $sStr
 * @return  string 
 */
function my_htmlspecialchars($sStr)
{
    $sStr = preg_replace('/&(?=[#a-z0-9]+;)/i', '__amp;_', $sStr);
    $sStr = strtr($sStr, array('<' => '&lt;', '>' => '&gt;', '&' => '&amp;', '"' => '&quot;', '\'' => '&#39;'));
    $sStr = preg_replace('/__amp;_(?=[#a-z0-9]+;)/i', '&', $sStr);
    return ($sStr);
}

/**
 * @brief   Convert a string from mixed html-entities/umlauts to pure $charset_out-umlauts
 *          Will replace all numeric and named entities except &gt; &lt; &apos; &quot; &#039; &nbsp;
 *          In case of error the returned string is unchanged, and a message is emitted.
 * 
 * @param   string     $sStr
 * @param   string     $charset_out
 * @return  string 
 */
function entities_to_umlauts($sStr, $charset_out = DEFAULT_CHARSET)
{
    require_once WB_PATH . '/framework/functions-utf8.php';
    return entities_to_umlauts2($sStr, $charset_out);
}

/**
 * @brief   Will convert a string in $charset_in encoding to a pure ASCII string with HTML-entities.
 *          In case of error the returned string is unchanged, and a message is emitted.
 * 
 * @param   string     $sStr
 * @param   string     $charset_in
 * @return  string 
 */
function umlauts_to_entities($sStr, $charset_in = DEFAULT_CHARSET)
{
    require_once WB_PATH . '/framework/functions-utf8.php';
    return umlauts_to_entities2($sStr, $charset_in);
}

/**
 * @brief   Function to convert a page title to a page filename
 * 
 * @param   string     $sStr
 * @return  string 
 */
function page_filename($sStr)
{
    require_once WB_PATH . '/framework/functions-utf8.php';
    $sStr = entities_to_7bit($sStr);
    // Now remove all bad characters
    $aBadChars = array(
        '\'', 
        '"', 
        '`', 
        '!', 
        '@', 
        '#', 
        '$', 
        '%', 
        '^', 
        '&', 
        '*', 
        '=', 
        '+', 
        '|', 
        '/', 
        '\\', 
        ';', 
        ':', 
        ',', 
        '?', 
        '[', 
        ']', 
        '<', 
        '>', 
        '{', // in page_filename only
        '}', // in page_filename only
        '(', // in page_filename only
        ')', // in page_filename only
        
    //   '~', // in media only
    //   '<', // in media only
    //   '>'  // in media only
    );
    $sStr = str_replace($aBadChars, '', $sStr);
    // replace multiple dots in filename to single dot and (multiple) dots at the end of the filename to nothing
    $sStr = preg_replace(array('/\.+/', '/\.+$/'), array('.', ''), $sStr);
    // Now replace spaces with page spcacer
    $sStr = trim($sStr);
    $sStr = preg_replace('/(\s)+/', PAGE_SPACER, $sStr);
    // Now convert to lower-case
    $sStr = strtolower($sStr);
    // If there are any weird language characters, this will protect us against possible problems they could cause
    $sStr = str_replace(array('%2F', '%'), array('/', ''), urlencode($sStr));
    // Finally, return the cleaned string
    return $sStr;
}

/**
 * @brief   Function to convert a desired media filename to a clean mediafilename
 * 
 * @param   string     $sStr
 * @return  string 
 */
function media_filename($sStr)
{
    require_once WB_PATH . '/framework/functions-utf8.php';
    $sStr = entities_to_7bit($sStr);
    // Now remove all bad characters
    $aBadChars = array(
        '\'', 
        '"', 
        '`', 
        '!', 
        '@', 
        '#', 
        '$', 
        '%', 
        '^', 
        '&', 
        '*', 
        '=', 
        '+', 
        '|', 
        '/', 
        '\\', 
        ';', 
        ':', 
        ',', 
        '?', 
        '[', 
        ']', 
        '<', 
        '>', 
    //  '{', // in page_filename only
    //  '}', // in page_filename only
    //  '(', // in page_filename only
    //  ')', // in page_filename only
        
        '~', // in media only
        '<', // in media only
        '>'  // in media only
    );
    $sStr = str_replace($aBadChars, '', $sStr);
    // replace multiple dots in filename to single dot and (multiple) dots at the end of the filename to nothing
    $sStr = preg_replace(array('/\.+/', '/\.+$/', '/\s/'), array('.', '', '_'), $sStr);
    // Clean any page spacers at the end of string
    $sStr = trim($sStr);
    // Finally, return the cleaned string
    return $sStr;
}

/**
 * @brief   Function to work out a page link
 * 
 * @param   string     $sStr
 * @return  string 
 */
if (!function_exists('page_link')) {
    function page_link($sLink)
    {
        return $GLOBALS['admin']->page_link($sLink);
    }
}

/**
 * @brief   Create a new directory and/or protected file in the given directory
 * 
 * @param   string    $sAbsDir
 * @param   bool      $bMakeDir
 * @return  
 */
function createFolderProtectFile($sAbsDir = '', $bMakeDir = true)
{
    global $admin, $MESSAGE;
    $retVal = array();
    $wb_path = rtrim(str_replace('\/\\', '/', WB_PATH), '/');
    if (($sAbsDir == '') || ($sAbsDir == $wb_path)) {return $retVal;}

    if ($bMakeDir == true) {
        // Check to see if the folder already exists
        if (file_exists($sAbsDir)) {
            // $admin->print_error($MESSAGE['MEDIA_DIR_EXISTS']);
            $retVal[] = basename($sAbsDir) . '::' . $MESSAGE['MEDIA_DIR_EXISTS'];
        }
        if (!is_dir($sAbsDir) && !make_dir($sAbsDir)) {
            // $admin->print_error($MESSAGE['MEDIA_DIR_NOT_MADE']);
            $retVal[] = basename($sAbsDir) . '::' . $MESSAGE['MEDIA_DIR_NOT_MADE'];
        } else {
            change_mode($sAbsDir);
        }
    }

    if (is_writable($sAbsDir)) {
        // if(file_exists($sAbsDir.'/index.php')) { unlink($sAbsDir.'/index.php'); }
        // Create default "index.php" file
        $rel_pages_dir = str_replace($wb_path, '', dirname($sAbsDir));
        $sIndexTrail = str_repeat('../', substr_count($rel_pages_dir, '/') + 1);

        $sResponse = $_SERVER['SERVER_PROTOCOL'] . ' 301 Moved Permanently';
        $sContent =
        '<?php' . "\n" .
        '// *** This file is generated by WBCE v' . WBCE_VERSION . "\n" .
        '// *** Creation date: ' . date('c') . "\n" .
        '// *** Do not modify this file manually' . "\n" .
        '// *** WBCE will rebuild this file from time to time!!' . "\n" .
        '// *************************************************' . "\n" .
        "\t" . 'header(\'' . $sResponse . '\');' . "\n" .
        "\t" . 'header(\'Location: '.$sIndexTrail.'index.php\');'."\n".
        '// *************************************************' . "\n";
        $sFilename = $sAbsDir . '/index.php';

        // write content into file
        if (is_writable($sFilename) || !file_exists($sFilename)) {
            if (file_put_contents($sFilename, $sContent)) {
                // debug_dump('create => '.str_replace( $wb_path, '' ,$sFilename));
                change_mode($sFilename, 'file');
            } else {
                $retVal[] = $MESSAGE['GENERIC_BAD_PERMISSIONS'] . ' :: ' . $sFilename;
            }
        }
    } else {
        $retVal[] = $MESSAGE['GENERIC_BAD_PERMISSIONS'];
    }
    return $retVal;
}

/**
 * @brief   automatically rebuild a protect index.php file 
 * 
 * @param   string    $sDir
 * @return  
 */
function rebuildFolderProtectFile($sDir = '')
{
    global $MESSAGE;
    $retVal = array();
    $sDir = rtrim(str_replace('\/\\', '/', $sDir), '/');
    try {
        $files = array();
        $files[] = $sDir;
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sDir)) as $fileInfo) {
            $files[] = $fileInfo->getPath();
        }
        $files = array_unique($files);
        foreach ($files as $file) {
            $protect_file = rtrim(str_replace('\/\\', '/', $file), '/');
            $retVal[] = createFolderProtectFile($protect_file, false);
        }
    } catch (Exception $e) {
        $retVal[] = $MESSAGE['MEDIA_DIR_ACCESS_DENIED'];
    }
    return $retVal;
}

/**
 * @brief   Create a new index.php file in the pages directory
 * 
 * @param   string  $sFilename
 * @param   int     $page_id
 * @param   int     $iLevel
 * @return  
 */
function create_access_file($sFilename, $page_id, $iLevel)
{
    global $admin, $MESSAGE;
    // First make sure parent folder exists
    $parent_folders = explode('/', str_replace(WB_PATH . PAGES_DIRECTORY, '', dirname($sFilename)));
    $parents = '';
    foreach ($parent_folders as $parent_folder) {
        if ($parent_folder != '/' and $parent_folder != '') {
            $parents .= '/' . $parent_folder;
            $acces_file = WB_PATH . PAGES_DIRECTORY . $parents;
            // can only be dirs
            if (!file_exists($acces_file)) {
                if (!make_dir($acces_file)) {
                    $admin->print_error($MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE_FOLDER']);
                }
            }
        }
    }
    // The depth of the page directory in the directory hierarchy
    // '/pages' is at depth 1
    $pages_dir_depth = count(explode('/', PAGES_DIRECTORY)) - 1;
    // Work-out how many ../'s we need to get to the index page
    $sIndexTrail = '';
    for ($i = 0; $i < $iLevel + $pages_dir_depth; $i++) {
        $sIndexTrail .= '../';
    }
    $sContent =
    '<?php' . "\n" .
    '// *** This file is generated by WBCE v' . WBCE_VERSION . "\n" .
    '// *** Creation date: ' . date('c') . "\n" .
    '// *** Do not modify this file manually' . "\n" .
    '// *** WBCE will rebuild this file from time to time!!' . "\n" .
    '// *************************************************' . "\n" .
    "\t" . '$page_id    = ' . (int) $page_id . ';' . "\n" .
    "\t" . 'require(\'' . $sIndexTrail . 'index.php\');' . "\n" .
    '// *************************************************' . "\n";

    if (($handle = fopen($sFilename, 'w'))) {
        fwrite($handle, $sContent);
        fclose($handle);
        // Chmod the file
        change_mode($sFilename);
    } else {
        $admin->print_error($MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE']);
    }
    return;
}

/**
 * @brief   Work out a file mime-type (if the in-built PHP one is not enabled)
 * 
 * @param   string  $sFilename
 * @return  string  The corresponding mime-type
 */
if (!function_exists('mime_content_type')) {
    function mime_content_type($sFilename)
    {
        $aMimeTypes = array(
            'txt'  => 'text/plain',
            'htm'  => 'text/html',
            'html' => 'text/html',
            'php'  => 'text/html',
            'css'  => 'text/css',
            'js'   => 'application/javascript',
            'json' => 'application/json',
            'xml'  => 'application/xml',
            'swf'  => 'application/x-shockwave-flash',
            'flv'  => 'video/x-flv',

            // images
            'png'  => 'image/png',
            'jpe'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg'  => 'image/jpeg',
            'gif'  => 'image/gif',
            'bmp'  => 'image/bmp',
            'ico'  => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif'  => 'image/tiff',
            'svg'  => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'mp4' => 'audio/mpeg',
            'qt'  => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai'  => 'application/postscript',
            'eps' => 'application/postscript',
            'ps'  => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );
		
        $aTemp = explode('.', $sFilename);
        $ext = strtolower(array_pop($aTemp));
        if (array_key_exists($ext, $aMimeTypes)) {
            return $aMimeTypes[$ext];
        } elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $sFilename);
            finfo_close($finfo);
            return $mimetype;
        } else {
            return 'application/octet-stream';
        }
    }
}

/**
 * @brief   Generate a thumbnail from an image
 * 
 * @param   string  $source
 * @param   string  $destination
 * @param   int     $size
 * @return  bool
 */
function make_thumb($source, $destination, $size)
{
    // Check if GD is installed
    if (extension_loaded('gd') && function_exists('imageCreateFromJpeg')) {
        // First figure out the size of the thumbnail
        list($original_x, $original_y) = getimagesize($source);
        if ($original_x > $original_y) {
            $thumb_w = $size;
            $thumb_h = $original_y * ($size / $original_x);
        }
        if ($original_x < $original_y) {
            $thumb_w = $original_x * ($size / $original_y);
            $thumb_h = $size;
        }
        if ($original_x == $original_y) {
            $thumb_w = $size;
            $thumb_h = $size;
        }
        // Now make the thumbnail
        $source = imageCreateFromJpeg($source);
        $dst_img = ImageCreateTrueColor($thumb_w, $thumb_h);
        imagecopyresampled($dst_img, $source, 0, 0, 0, 0, $thumb_w, $thumb_h, $original_x, $original_y);
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

/**
 * @brief  Function to work-out a single part of an octal permission value
 *
 * @param  mixed    $octal_value  an octal value as string (i.e. '0777') 
 *                                or real octal integer (i.e. 0777 | 777)
 * @param  string   $who          char or string for whom the permission 
 *                                is asked( U[ser] / G[roup] / O[thers] )
 * @param  string   $action       char or string with the requested 
 *                                action( r[ead..] / w[rite..] / e|x[ecute..] )
 * @return boolean
 */
function extract_permission($octal_value, $who, $action)
{
    // Make sure that all arguments are set and $octal_value is a real octal-integer
    if (($who == '') || ($action == '') || (preg_match('/[^0-7]/', (string) $octal_value))) {
        return false; // invalid argument, so return false
    }
    // convert $octal_value into a decimal-integer to be sure having a valid value
    $right_mask = octdec($octal_value);
    $action_mask = 0;
    // set the $action related bit in $action_mask
    switch ($action[0]) {
        // get action from first char of $action
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
    switch ($who[0]) {
        // get who from first char of $who
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
    return (($right_mask & $action_mask) != 0); // return result of binary-AND
}

/**
 * @brief   Function to delete a page from pageTree, from DB 
 *          and its physical php file from pages directory
 * 
 * @param   int     $page_id
 * @return  
 */
function delete_page($page_id)
{
    global $admin, $database, $MESSAGE;
    // Find out more about the page
    $sSql = 'SELECT `page_id`, `menu_title`, `page_title`, `level`, '
            . '`link`, `parent`, `modified_by`, `modified_when` '
            . 'FROM `{TP}pages` WHERE `page_id`=' . $page_id;
    $results = $database->query($sSql);
    if ($database->is_error()) {
		$admin->print_error($database->get_error());
	}
    if ($results->numRows() == 0) {
		$admin->print_error($MESSAGE['PAGES_NOT_FOUND']);
	}
    $aData = $results->fetchRow(MYSQL_ASSOC);
    $parent     = $aData['parent'];
    $level      = $aData['level'];
    $link       = $aData['link'];
    $page_title = $aData['page_title'];
    $menu_title = $aData['menu_title'];
    // Get the sections that belong to the page
    $sSql = "SELECT `section_id`, `module` FROM `{TP}sections` WHERE `page_id`=".$page_id;
    $query_sections = $database->query($sSql);
    if ($query_sections->numRows() > 0) {
        while ($section = $query_sections->fetchRow(MYSQL_ASSOC)) {
            // Set section id
            $section_id = $section['section_id'];
            // Include the modules delete file if it exists
            if (file_exists(WB_PATH . '/modules/' . $section['module'] . '/delete.php')) {
                include WB_PATH . '/modules/' . $section['module'] . '/delete.php';
            }
        }
    }
    // delete page from pages and sections tables
    $database->delRow('{TP}pages', 'page_id', $page_id);
    if ($database->is_error()) {
        $admin->print_error($database->get_error());
    }
    $database->delRow('{TP}sections', 'page_id', $page_id);
    if ($database->is_error()) {
        $admin->print_error($database->get_error());
    }
    // Include the ordering class or clean-up ordering
    include_once WB_PATH . '/framework/class.order.php';
    $order = new order(TABLE_PREFIX . 'pages', 'position', 'page_id', 'parent');
    $order->clean($parent);
    // Unlink the page access file and directory
    $directory = WB_PATH . PAGES_DIRECTORY . $link;
    $filename = $directory . PAGE_EXTENSION;
    $directory .= '/';
    if (file_exists($filename)) {
        if (!is_writable(WB_PATH . PAGES_DIRECTORY . '/')) {
            $admin->print_error($MESSAGE['PAGES_CANNOT_DELETE_ACCESS_FILE']);
        } else {
            unlink($filename);
            if (file_exists($directory) &&
                (rtrim($directory, '/') != WB_PATH . PAGES_DIRECTORY) &&
                (substr($link, 0, 1) != '.')) {
                rm_full_dir($directory);
            }
        }
    }
}

/**
 * @brief   ...
 * 
 * @param   string  $sFilePath  name of the file to read
 * @param   int     $size       number of maximum bytes to read (0 = complete file)
 * @return  unspec  The content as string, false on error
 */
function getFilePart($sFilePath, $size = 0)
{
    $sFileContent = '';
    if (file_exists($sFilePath) && is_file($sFilePath) && is_readable($sFilePath)) {
        if ($size == 0) {
            $size = filesize($sFilePath);
        }
        if (($fh = fopen($sFilePath, 'rb'))) {
            if (($sFileContent = fread($fh, $size)) !== false) {
                return $sFileContent;
            }
            fclose($fh);
        }
    }
    return false;
}

/**
 * @brief  replace Placeholder-Tokens with values in a string
 *
 * @param  string  $sString   string with Placeholder-Tokens
 * @param  array   $aReplace  values to replace vars placeholder
 * @param  string  $sHem      The delimiters on both sides that are being used
 *                            in PH-Tokens: '[%s]' or '{%s}'. Default is: '{{%s}}'
 * @return string  The string with replaced Placeholder Tokens
 */
function replace_vars($sString = '', &$aReplace = array(), $sHem = '{{%s}}')
{
    if (!empty($aReplace)) {
        foreach ($aReplace as $sKey => $sValue) {
            $sString = str_replace(sprintf($sHem, $sKey), $sValue, $sString);
        }
    }
    return $sString;
}

/**
 * @brief  Load module into DB
 *
 * @param  string  $sModulePath   
 * @param  bool    $bInstall     
 * @return 
 */
function load_module($sModulePath, $bInstall = false)
{
    global $database, $admin, $MESSAGE;
    $retVal = array();
    if(is_dir($sModulePath) && file_exists($sModulePath.'/info.php'))
    {
        require($sModulePath.'/info.php');
        if(isset($module_name))
        {
            if(!isset($module_license)) { $module_license = 'GNU General Public License'; }
            if(!isset($module_platform) && isset($module_designed_for)) { $module_platform = $module_designed_for; }
            if(!isset($module_function) && isset($module_type)) { $module_function = $module_type; }
            $module_function = strtolower($module_function);
            $aData = array( 
                'directory'   => $database->escapeString($module_directory),
                'name'        => $database->escapeString($module_name),
                'description' => $database->escapeString($module_description),
                'type'        => 'module', 
                'function'    => $database->escapeString($module_function),
                'version'     => $database->escapeString($module_version),
                'platform'    => $database->escapeString($module_platform),
                'author'      => $database->escapeString($module_author),
                'license'     => $database->escapeString($module_license),
            );
            // Check that it doesn't already exist
            $sSqlwhere = "WHERE `type`='module' AND `directory`='".$module_directory."'";
            $sSqlCheck  = 'SELECT COUNT(*) FROM `{TP}addons` '.$sSqlwhere;
            if( $database->get_one($sSqlCheck) ) {
                $retVal[] = $database->updateRow('{TP}addons', 'directory', $aData);
            }else{
                $retVal[] = $database->insertRow('{TP}addons', $aData);
            }
            // Run installation script
            if($bInstall == true) {
                if(file_exists($sModulePath.'/install.php')) {
                    require($sModulePath.'/install.php');
                        $retVal[] = isset($msg)?:'Info '.$module_name;
                }
            }
        }
    }
	return $retVal;
}

/**
 * @brief  Load template into DB
 *
 * @param  string  $sTemplatePath   
 * @return 
 */
function load_template($sTemplatePath)
{
    global $database, $admin;
    $retVal = false;
    if(is_dir($sTemplatePath) && file_exists($sTemplatePath.'/info.php'))
    {
        require($sTemplatePath.'/info.php');
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
            $aData = array( 
                'directory'   => $database->escapeString($template_directory),
                'name'        => $database->escapeString($template_name),
                'description' => $database->escapeString($template_description),
                'type'        => 'template',
                'function'    => $database->escapeString($template_function),
                'version'     => $database->escapeString($template_version),
                'platform'    => $database->escapeString($template_platform),
                'author'      => $database->escapeString($template_author),
                'license'     => $database->escapeString($template_license)
            );
            // Check that it doesn't already exist
            $sSqlwhere = "WHERE `type`='template' AND `directory`='".$template_directory."'";
            $sSqlCheck  = 'SELECT COUNT(*) FROM `{TP}addons` '.$sSqlwhere;
            if( $database->get_one($sSqlCheck) ) {
                $retVal = $database->updateRow('{TP}addons', 'directory', $aData);
            }else{
                $retVal = $database->insertRow('{TP}addons', $aData);
            }
            
        }
    }
    return $retVal;
}


/**
 * @brief  Load language into DB
 *
 * @param  string  $sFilePath  
 * @return array
 */
function load_language($sFilePath)
{
    global $database, $admin;
    $retVal = false;
    if (file_exists($sFilePath) && preg_match('#^([A-Z]{2}.php)#', basename($sFilePath)))
    {
        // require($sFilePath);  it's to large
        // read contents of the template language file into string
        $data = @file_get_contents(WB_PATH.'/languages/'.str_replace('.php','',basename($sFilePath)).'.php');
        // use regular expressions to fetch the content of the variable from the string
        $language_name        = get_variable_content('language_name', $data, false, false);
        $language_code        = preg_replace('/^.*([a-zA-Z]{2})\.php$/si', '\1', $sFilePath);
        $language_author      = get_variable_content('language_author',   $data, false, false);
        $language_version     = get_variable_content('language_version',  $data, false, false);
        $language_platform    = get_variable_content('language_platform', $data, false, false);
        $language_description = get_variable_content('language_platform', $data, false, false);
        if(isset($language_name))
        {
            if(!isset($language_license)) { $language_license = 'GNU General Public License'; }
            if(!isset($language_platform) && isset($language_designed_for)) { $language_platform = $language_designed_for; }
            
            $aData = array( 
                'directory'   => $language_code,
                'name'        => $database->escapeString($language_name),
                'type'        =>'language',
                'version'     => $database->escapeString($language_version),
                'platform'    => $database->escapeString($language_platform),
                'author'      => $database->escapeString($language_author),
                'description' => '',
                'license'     => $database->escapeString($language_license)
            );
            // Check that it doesn't already exist
            $sSqlwhere = "WHERE `type`='language' AND `directory`='".$language_code."'";
            $sSqlCheck  = 'SELECT COUNT(*) FROM `{TP}addons` '.$sSqlwhere;
            if( $database->get_one($sSqlCheck) ) {
                $retVal = $database->updateRow('{TP}addons', 'directory', $aData);
            }else{
                $retVal = $database->insertRow('{TP}addons', $aData);
            }
        }
    }
    return $retVal;
}

/**
 * @brief  Upgrade Module Data in DB
 *
 * @param  string  $sModDirname 
 * @param  bool    $bUpgrade     
 * @return 
 */
function upgrade_module($sModDirname, $bUpgrade = false)
{
    global $database, $admin, $MESSAGE, $new_module_version;
    $sModulePath = WB_PATH . '/modules/' . $sModDirname;
    if (file_exists($sModulePath . '/info.php')) {
        require $sModulePath . '/info.php';
        if (isset($module_name)) {			
            // Check that the module does already exist
            $sCheckSql = "SELECT COUNT(*) FROM `{TP}addons` WHERE `directory`='". $sModDirname ."'";
            if ($database->get_one($sCheckSql)) {
                // Update in DB
                $aUpdate = array(
                    'directory'   => $module_directory,
                    'version'     => $module_version,
                    'description' => addslashes($module_description),
                    'platform'    => (!isset($module_platform) && isset($module_designed_for)) ? $module_designed_for : $module_platform,
                    'author'      => addslashes($module_author),
                    'license'     => addslashes((!isset($module_license)) ? 'GNU General Public License' : $module_license),
                    'function'    => strtolower((!isset($module_function) && isset($module_type)) ? $module_type : $module_function),
                );
                $database->updateRow('{TP}addons', 'directory', $aUpdate);
                if ($database->is_error()) {
                    $admin->print_error($database->get_error());
                }
                // Run upgrade script
                if ($bUpgrade == true) {
                    if (file_exists($sModulePath . '/upgrade.php')) {
                        require $sModulePath . '/upgrade.php';
                    }
                }
            }
        }
    }
}

/**
 * @brief  Extracts the content of a string variable from a string (save alternative to including files)
 *
 * @param  string  $search                The variable to be looked for 
 * @param  string  $data                  The string to look inside for the variable
 * @param  bool    $striptags             Should tags be stripped from variable content?
 * @param  bool    $convert_to_entities   Should content be converted using htmlentities?
 * @return unspec  string if the var was found, false if not
 */
function get_variable_content($search, $data, $striptags = true, $convert_to_entities = true)
{
    $match = '';
    // search for $variable followed by 0-n whitespace then by = then by 0-n whitespace
    // then either " or ' then 0-n characters then either " or ' followed by 0-n whitespace and ;
    // the variable name is returned in $match[1], the content in $match[3]
    if (preg_match('/(\$' . $search . ')\s*=\s*("|\')(.*)\2\s*;/', $data, $match)) {
        if (strip_tags(trim($match[1])) == '$' . $search) {
            // variable name matches, return it's value
            $match[3] = ($striptags == true) ? strip_tags($match[3]) : $match[3];
            $match[3] = ($convert_to_entities == true) ? htmlentities($match[3]) : $match[3];
            return $match[3];
        }
    }
    return false;
}

/**
 * @brief  get the version of specified Addon based on DB entry or info.php file
 *
 * @param  string  $sAddonDirname  like saved in addons.directory
 * @param  bool    $bSource        true reads from database, false from info.php
 * @return string  the version as string, if not found returns null
 */

function get_module_version($sAddonDirname, $bSource = true)
{
    global $database;
    $sAddonVersion = null;
    if ($bSource != true) {
        $sSql = 'SELECT `version` FROM `{TP}addons` WHERE `directory`=\'' . $sAddonDirname . '\'';
        $version = $database->get_one($sSql);
    } else {
        $sInfoFilePath = WB_PATH . '/modules/' . $sAddonDirname . '/info.php';
        if (file_exists($sInfoFilePath)) {
            if (($sInfoFileContent = file_get_contents($sInfoFilePath))) {
                $sAddonVersion = get_variable_content('module_version', $sInfoFileContent, false, false);
                $sAddonVersion = ($sAddonVersion !== false) ? $sAddonVersion : null;
            }
        }
    }
    return $sAddonVersion;
}
// deprecated alias of the above function (get_module_version)
function get_modul_version($sAddonDirname, $bSource = true)
{
	return get_module_version($sAddonDirname, $bSource);
}

/**
 * @brief  ...
 *
 * @param   string  $aVarlistCSV: comma seperated list of varnames to move into global space
 * @return  bool    false if one of the vars already exists in global space (error added to msgQueue)
 */
function vars2globals_wrapper($aVarlistCSV)
{
    $retval = true;
    if ($aVarlistCSV != '') {
        $aVars = explode(',', $aVarlistCSV);
        foreach ($aVars as $var) {
            if (isset($GLOBALS[$var])) {
                ErrorLog::write(
                    'variabe $' . $var . ' already defined in global space!!',
                    __FILE__, __FUNCTION__, __LINE__
                );
                $retval = false;
            } else {
                global $$var;
            }
        }
    }
    return $retval;
}

/**
 * @brief   Filter directory traversal more thoroughly, thanks to hal 9000
 * 
 * @param   string  $dir             directory relative to MEDIA_DIRECTORY
 * @param   bool    $with_media_dir  true when to include MEDIA_DIRECTORY
 * @return  false if directory traversal detected or real path if not
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

 */
/**
 * @brief   The urlencode function and rawurlencode are mostly based on RFC 1738.
 *          However, since 2005 the current RFC in use for URIs standard is RFC 3986.
 *          Here is a function to encode URLs according to RFC 3986.
 * 
 * @param   string  $sStr
 * @return  string
 */
if (!function_exists('url_encode')) {
    function url_encode($sStr)
    {
        $sStr = html_entity_decode($sStr, ENT_QUOTES, 'UTF-8');
        $aReplacements = array(
            '%21' => "!", 
            '%2A' => "*", 
            '%27' => "'", 
            '%28' => "(", 
            '%29' => ")", 
            '%3B' => ";", 
            '%3A' => ":", 
            '%40' => "@", 
            '%26' => "&", 
            '%3D' => "=", 
            '%2B' => "+", 
            '%24' => "$", 
            '%2C' => ",", 
            '%2F' => "/", 
            '%3F' => "?", 
            '%25' => "%", 
            '%23' => "#", 
            '%5B' => "[", 
            '%5D' => "]",
        );
        return strtr(rawurlencode($sStr), $aReplacements);
    }
}


/**
 * @brief   This function will be implemented in PHP since version 7.3.0
 *          This polyfill function will allow the use of this function
 *          also on environments with a PHP lower than 7.3.0
 *          see also: http://php.net/manual/en/function.is-countable.php
 *
 * @param   unspecified  $uVar The variable you want to check 
 * @return  bool         true or false
 */
if (!function_exists('is_countable')) {
    function is_countable($uVar) 
    {
        return (is_array($uVar) || $uVar instanceof Countable);
    }
}

/**
 * @brief   This function displays structured information about a variable
 *          or other expression that includes its type and value. 
 *          It does the same as PHP print_r or var_dump, but does so in 
 *          a friendly colorized wrapper output
 *
 * Example usage:
 * 
 *     debug_dump(get_defined_constants());
 *     // will output:
 *     //   The entire array of defined constants
 * 
 *     $myArray = array('milk', 'honey', 'cinnamon');
 *     debug_dump($myArray, 'my array');
 *     // will output:
 *          Array
 *          (
 *              [0] => milk
 *              [1] => honey
 *              [2] => cinnamon
 *          )
 * 
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * @author   Christian M. Stefan <stefek@designthings.de>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @param    unspec  $uVar could be any type of input, a string, 
                     int, bool, object, array, what have you...
 * @param    string  $sHeading  A heading you want to show above the 
 *                   output. You can also use __LINE__ when using this 
 *                   function several times, so you know where the output 
 *                   comes from in the file(s)
 * @param    bool    $bUse_var_dump you can display as print_r or var_dump.
 *                   Default is print_r
 * @return   string  
 */

// NOTE: This function will load only if  WB_DEBUG  constant is set to true 
// otherwise another "empty return" function (below this one) will be load.

function debug_dump($mVar = '', $sHeading ='', $bUse_var_dump = false)
{
    
    // get Type of variable
    switch (true){
        case is_object($mVar): $sType = 'object'; break;                
        case is_array($mVar):  $sType = 'array';  break;                
        case is_string($mVar): $sType = 'string'; break;                
        case is_bool($mVar):   $sType = 'bool';   break;                
        case is_int($mVar):    $sType = 'int';    break;                
        case is_scalar($mVar): $sType = 'scalar'; break;                
        case ($mVar === NULL): $sType = 'NULL';   break;                
        default: $sType = 'unknown var type';         
    }
    $sRetVal = '';
    $sRetVal .=  '<fieldset class="debug_frame '.$sType.'">';
    $sRetVal .=  '<p class="heading">';
    
    $sCountable = is_countable($mVar) ? ' <i>countable</i>' : '';
    $sRetVal .=  '<span class="var-type">('.$sType.')'.$sCountable.' </span> '.$sHeading;
         
    $sCloseBtn = '<button type="button" class="close"><span aria-hidden="true">&times;</span></button>';    
    $sCollapse = '<button type="button" class="collapse"><span aria-hidden="true">+</span></button>';
    $sRetVal .=  $sCloseBtn.$sCollapse.'</p>'; 
    $sRetVal .=  '<pre>';
    $sData    =  '';
    if((is_array($mVar)) or (!is_array($mVar) && $mVar != '' && !is_bool($mVar) && !is_int($mVar))){
        $func = ($bUse_var_dump == true) ? 'var_dump' : 'print_r';
        ob_start();
        $func($mVar);
        $sData .= ob_get_clean();
    } 
    if ($mVar === TRUE) {
        $sData .=  '<span class="keyname">true</span> <i class="str-length">(bool)</i>';
    } elseif ($mVar === FALSE) {
        $sData .=  '<span class="keyname">false</span> <i class="str-length">(bool)</i>';
    } elseif ($mVar === NULL) {
        $sData .=  '<span class="keyname">NULL</span>';
    } elseif (is_int($mVar)) {
        $sData .=  '<span class="keyname">'.$mVar.'</span> <i class="str-length">(int)</i>';
    } 
    $sRetVal .=  $sData. PHP_EOL .'</pre>';
    
    $aBackTrace = debug_backtrace()[0];
    $sRetVal .= '<p class="backtrace">called in file: <b>'
            . str_replace(WB_PATH, 'WB_PATH ', $aBackTrace['file'])
            .'</b><br />on line: <b>'.$aBackTrace['line'].'</b></p>';  
    $sRetVal .= '</fieldset>';  

    // apply RegEx for colorization if the output is an Array or an Object            
    $aRegEx = array(                
        0 => array(
            'find'    => "/\=\>\n/",
            'replace' => '=><br><span class="tab"></span>',
        ), 
        1 => array(
            'find'    => '/\=\>/',
            'replace' => "<span class=\"arrow\">=></span>",
        ),  
        2 => array(
            'find'    => '#(?<=\[)(.*?)(?=\])#',
            'replace' => '<span class="keyname">$1</span>',
        ),     
        3 => array(
            'find'    => '/\[/',
            'replace' => '<div class="vert-spacer">&nbsp;</div>'
                       . '<span class="tab"></span>'
                       . '<span class="brackets">[</span>',
        ),                 
        4 => array(
            'find'    => '/\]/',
            'replace' => '<span class="brackets">]</span>',
        ),          
        5 => array(
            'find'    => '/(string|array|int)(\()([1-9][0-9]*)(\))/',
            'replace' => '<span class="var-type">$1</span>'
                       . '<span class="brackets">$2</span>'
                       . '<span class="str-length">$3</span>'
                       . '<span class="brackets">$4</span>',
        )
    );

    $sRetVal = do_regex_array($aRegEx, $sRetVal);

    // provide stylesheet for the colorization
    $sCssFile = '/templates/%s/css/debug_dump.css';
    if(is_readable(WB_PATH.sprintf($sCssFile, DEFAULT_THEME))){
        $sCssFile = WB_URL.sprintf($sCssFile, DEFAULT_THEME);
    } else {
        $sCssFile = WB_URL.sprintf($sCssFile, 'theme_fallbacks');
    }
    I::insertCssFile($sCssFile, 'HEAD BTM-', 'debug_dump'); 
    $sToJs  = "
        jQuery(document).ready(function($) {
            $('.debug_frame .close').on( 'click', function( event ) {
                $( event.target ).closest( 'fieldset.debug_frame' ).slideToggle(150);
            });            
            $('.collapse').on( 'click', function() {
                 $(this).parent().next().slideToggle('fast');
            }); 
        });";    
    I::insertJsCode($sToJs, 'BODY BTM-', 'debug_dump'); 

    echo $sRetVal;
}


/**
 * @brief   With this function you can preg_replace 
 *          an array of RegEx commands on a string
 *
 * Example usage:
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * $aRegEx = array(                
 *        0 => array(
 *            'find'    => "/\=\>\n/",
 *            'replace' => "=><br /><span class=\"tab\"></span>",
 *        ), 
 *        1 => array(
 *            'find'    => '/\=\>/',
 *            'replace' => "<span class=\"arrow\">=></span>",
 *        )
 *    );
 * $sRetVal = do_regex_array($aRegEx, $sRetVal);
 *
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * @author   Christian M. Stefan <stefek@designthings.de>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @param   array   $aRegEx The array containing RegEx commands
 * @param   string  $sStr   The string you want to process
 * @return  string  The processed string
 */
function do_regex_array($aRegEx, &$sStr)
{            
    if(is_array($aRegEx))
        foreach($aRegEx as $regex)
            $sStr = preg_replace($regex['find'], $regex['replace'], $sStr); 
    return $sStr;
}

/**
 * @brief   Simply get a URL based on a PATH.
 *
 * Example usage:
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * echo get_url_from_path(WB_PATH . '/modules/mymodule');
 * // or, if your work from inside the above directory, simply do:
 * echo get_url_from_path(__DIR__);
 * // will return: http://www.domain.tld/modules/mymodule
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 *
 * @param   string  $sPath   The path to be translated
 * @return  string  The translated string
 */
function get_url_from_path($sPath)
{
    return str_replace(array(WB_PATH, '\\'), array(WB_URL, '/'), $sPath);
}

/**
 * @brief   Get a string between a [tagname] your string [/tagname]
 *
 * @param   string  $sContent The String on wich the process has to be done
 * @param   string  $sTagname The tag name being used
 * @return  string
 */
function get_string_between_tags($sContent, $sTagname)
{
    if(strpos($sContent, $sTagname.']') !== false){       
        $sRegEx = "#[\s*?$sTagname\b[^>]*](.*?)[/$sTagname\b[^>]*]#s";
        if(preg_match($sRegEx, $sContent, $aMatches)){
                return $aMatches[1];
        }
    }
    return;
}

/**
 * @brief   A variant of strpos() with the distinction that it can take an 
 *          array of values as needle to match against the haystack;
 *          the needle can be both, an array or a string
 *
 * @param   string  $sHaystack  the string to be checked
 * @param   unspec  $uNeedle    can be array or single string
 * @return  bool
 */
function strposm($sHaystack, $uNeedle)
{
    if (!is_array($uNeedle)) $uNeedle = array($uNeedle);
    foreach($uNeedle as $lookup) {
        if (($pos = strpos($sHaystack, $lookup))!== false){
            return $pos;
        }
    }
    return false;
}
