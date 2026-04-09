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

// Prevent  this  file  from  being  accessed  directly
defined('WB_PATH') or die('No direct access allowed');

// load_* functions are now in a separate file to work with the new, 
// stand alone `wbce_installer`
require_once __DIR__ . '/functions.load_addons.php';

// Define that this file has been loaded
define('FUNCTIONS_FILE_LOADED', true);

if (!function_exists('str_contains')) {
    /**
     * str_contains Checks if a string contains a given substring.
     *
     * This function is a fallback for PHP versions lower than 8.0.0, 
     * which do not have the built-in `str_contains()` function.
     *
     * @param string $haystack The string to search within.
     * @param string $needle The substring to search for.
     * @return bool true if the substring is found, false otherwise.
     */
    function str_contains($haystack, $needle) {
        return !empty($needle) && false !== strpos($haystack, $needle);
    }
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
        $query_home_folders = $database->query(
            'SELECT `home_folder` FROM `{TP}users` WHERE `home_folder` != ?',
            [$admin->get_home_folder()]
        );
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
 * @param object &$wb : $wb from frontend or $admin from backend
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
            // FIND_IN_SET is registered as UDF for SQLite - no driver branch needed
            $sSql = "SELECT `home_folder`
                     FROM `{TP}users`
                     WHERE `home_folder` <> ''
                       AND `user_id` <> ?
                       AND FIND_IN_SET(?, `groups_id`) > 0";
            if ($res_hf = $database->query($sSql, [$wb->get_user_id(), $group])) {
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
    $tmp = array();
    $full_list = array_merge($tmp, $full_list);
    return $full_list;
}

/**
 * @brief  returns a list of directories beyound /wb/media
 *         which are ReadWrite for current user
 *
 * @param object &$wb $wb from frontend or $admin from backend
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
            // FIND_IN_SET is registered as UDF for SQLite - no driver branch needed
            $sSql = "SELECT `home_folder`
                     FROM `{TP}users`
                     WHERE `home_folder` <> ''
                       AND `user_id` <> ?
                       AND FIND_IN_SET(?, `groups_id`) > 0";
            if ($res_hf = $database->query($sSql, [$wb->get_user_id(), $group])) {
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
    $tmp = array();
    $array = array_unique($array);
    $full_list = array_merge($tmp, $array);
    unset($array);
    unset($allow_list);
    return $full_list;
}


/**
 * @brief   Function to chmod files and directories
 *
 * @param string $name
 * @return  bool
 */
function change_mode(string $name): bool
{
    // Early return for Windows - chmod has very limited effect
    if (PHP_OS_FAMILY === 'Windows') {
        return true;
    }

    // Not Windows → proceed with chmod
    
	if (!file_exists($name)) {
        return false;
    }

    $mode = is_dir($name) ? OCTAL_DIR_MODE : OCTAL_FILE_MODE;

    $umask = umask(0);
    $result = chmod($name, $mode);
    umask($umask);

    return $result;
}

/**
 * @brief   Function to figure out if a parent of a specific page_id exists
 *
 * @param int $page_id
 * @return  unspec  If parent isn't 0 its ID will be returned
 */
function is_parent($page_id)
{
    $parent = $GLOBALS['database']->fetchValue(
        'SELECT `parent` FROM `{TP}pages` WHERE `page_id` = ?',
        [(int)$page_id]
    );
    return ($parent === '') ? false : $parent;
}

/**
 * @brief   Function to work out the level of a specific page
 *
 * @param int $page_id
 * @return  int   The level of the page
 */
function level_count($page_id)
{
    global $database;
    // Get page parent
    $iParentID = $database->fetchValue(
        'SELECT `parent` FROM `{TP}pages` WHERE `page_id` = ?',
        [(int)$page_id]
    );
    if ($iParentID > 0) {
        // Get the level of the parent
        $iLevel = $database->fetchValue(
            'SELECT `level` FROM `{TP}pages` WHERE `page_id` = ?',
            [(int)$iParentID]
        );
        return $iLevel + 1;
    } else {
        return 0;
    }
}

/**
 * @brief   Function to work out root parent
 *
 * @param int $page_id
 * @return  unspec  int or array
 */
function root_parent($page_id)
{
    global $database;
    // Get page details
    $rQueryPage = $database->query(
        'SELECT `parent`, `level` FROM `{TP}pages` WHERE `page_id` = ?',
        [(int)$page_id]
    );
    $aPageData = $rQueryPage->fetchRow(MYSQLI_ASSOC);
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
 * @param int $page_id
 * @return  string  The page title
 */
function get_page_title($page_id)
{
    return $GLOBALS['database']->fetchValue(
        'SELECT `page_title` FROM `{TP}pages` WHERE `page_id` = ?',
        [(int)$page_id]
    );
}

/**
 * @brief   Function to get a pages menu title
 *
 * @param int $page_id
 * @return  string  The menu title
 */
function get_menu_title($page_id)
{
    return $GLOBALS['database']->fetchValue(
        'SELECT `menu_title` FROM `{TP}pages` WHERE `page_id` = ?',
        [(int)$page_id]
    );
}

/**
 * @brief   Function to get all parent page titles
 *
 * @param int $iParentID
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
 * @param int $iParentID
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
 * @param int $page_id
 * @return  array
 */
function get_page_trail($page_id)
{
    return implode(',', array_reverse(get_parent_ids($page_id)));
}

/**
 * @brief   Function to get all sub pages id's
 *
 * @param int   $iParentID
 * @param array $aSubs
 * @return array
 */
function get_subs(int $iParentID, array $aSubs = []): array
{
    global $database;

    $children = $database->fetchAll(
        'SELECT `page_id` FROM `{TP}pages` WHERE `parent` = ?',
        [$iParentID]
    );

    foreach ($children as $child) {
        $aSubs[] = (int)$child['page_id'];
        $aSubs   = get_subs((int)$child['page_id'], $aSubs);
    }

    return $aSubs;
}



/**
 * @brief   Function as replacement for php's htmlspecialchars()
 *          Will not mangle HTML-entities
 *
 * @param string $sStr
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
 * @param string $sStr
 * @param string $charset_out
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
 * @param string $sStr
 * @param string $charset_in
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
 * @param string $sStr
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
 * @brief  Get the path of pages access file
 *
 * @param int $iPageID
 * @return string
 */
function getAccessFilePath($iPageID)
{
    global $database;
    $sDbLink = $database->fetchValue(
        'SELECT `link` FROM `{TP}pages` WHERE `page_id` = ?',
        [(int)$iPageID]
    );
    $sFilePath = WB_PATH . PAGES_DIRECTORY . $sDbLink . PAGE_EXTENSION;
    return $sFilePath;
}

/**
 * @brief   Function to convert a desired media filename to a clean mediafilename
 *
 * @param string $sStr
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
 * @param string $sStr
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
 * @param string $sAbsDir
 * @param bool $bMakeDir
 * @return
 */
function createFolderProtectFile($sAbsDir = '', $bMakeDir = true)
{
    global $admin, $MESSAGE;
    $retVal = array();
    $wb_path = rtrim(str_replace('\/\\', '/', WB_PATH), '/');
    if (($sAbsDir == '') || ($sAbsDir == $wb_path)) {
        return $retVal;
    }

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
            "\t" . 'header(\'Location: ' . $sIndexTrail . 'index.php\');' . "\n" .
            '// *************************************************' . "\n";
        $sFilename = $sAbsDir . '/index.php';

        // write content into file
        if (is_writable($sFilename) || !file_exists($sFilename)) {
            if (file_put_contents($sFilename, $sContent)) {
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
 * @param string $sDir
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
 * @param string $sFilename
 * @param int $page_id
 * @param int $iLevel
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
        "\t" . '$page_id    = ' . (int)$page_id . ';' . "\n" .
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
 * @param string $sFilename
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
 * @param string $source
 * @param string $destination
 * @param int $size
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
            $thumb_h = abs($original_y * ($size / $original_x));
        }
        if ($original_x < $original_y) {
            $thumb_w = abs($original_x * ($size / $original_y));
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
 * @param mixed $octal_value an octal value as string (i.e. '0777')
 *                                or real octal integer (i.e. 0777 | 777)
 * @param string $who char or string for whom the permission
 *                                is asked( U[ser] / G[roup] / O[thers] )
 * @param string $action char or string with the requested
 *                                action( r[ead..] / w[rite..] / e|x[ecute..] )
 * @return boolean
 */
function extract_permission($octal_value, $who, $action): bool
{
    // Ungültige oder leere Eingaben abfangen
    if (empty($who) || empty($action) || preg_match('/[^0-7]/', (string)$octal_value)) {
        return false;
    }

    // Octal → Dezimal umwandeln
    $right_mask = octdec($octal_value);

    // Action-Bit bestimmen (r=4, w=2, x=1)
    $action_mask = match (strtolower($action[0] ?? '')) {
        'r'      => 4,
        'w'      => 2,
        'e', 'x' => 1,
        default  => false,
    };

    if ($action_mask === false) {
        return false;
    }

    // Wer (User/Group/Other) → Bit-Shift bestimmen
    $shift = match (strtolower($who[0] ?? '')) {
        'u' => 6,
        'g' => 3,
        'o' => 0,
        default => false,
    };

    if ($shift === false) {
        return false;
    }

    $action_mask <<= $shift;

    return ($right_mask & $action_mask) !== 0;
}

/**
 * @brief   Function to delete a page from pageTree, from DB
 *          and its physical php file from pages directory
 *
 * @param int $page_id
 * @return
 */
function delete_page($page_id)
{
    global $admin, $database, $MESSAGE;
    // Find out more about the page
    $sSql = 'SELECT `page_id`, `menu_title`, `page_title`, `level`,
             `link`, `parent`, `modified_by`, `modified_when`
             FROM `{TP}pages` WHERE `page_id` = ?';
    $results = $database->query($sSql, [(int)$page_id]);
    if ($database->hasError()) {
        $admin->print_error($database->getError());
    }
    if ($results->numRows() == 0) {
        $admin->print_error($MESSAGE['PAGES_NOT_FOUND']);
    }
    $aData = $results->fetchRow(MYSQLI_ASSOC);
    $parent = $aData['parent'];
    $level = $aData['level'];
    $link = $aData['link'];
    $page_title = $aData['page_title'];
    $menu_title = $aData['menu_title'];
    // Get the sections that belong to the page
    $query_sections = $database->query(
        "SELECT `section_id`, `module` FROM `{TP}sections` WHERE `page_id` = ?",
        [(int)$page_id]
    );
    if ($query_sections->numRows() > 0) {
        while ($section = $query_sections->fetchRow(MYSQLI_ASSOC)) {
            // Set section id
            $section_id = $section['section_id'];
            // Include the modules delete file if it exists
            if (file_exists(WB_PATH . '/modules/' . $section['module'] . '/delete.php')) {
                include WB_PATH . '/modules/' . $section['module'] . '/delete.php';
            }
        }
    }
    // delete page from pages and sections tables
    $database->deleteRow('{TP}pages', 'page_id', $page_id);
    if ($database->hasError()) {
        $admin->print_error($database->getError());
    }
    $database->deleteRow('{TP}sections', 'page_id', $page_id);
    if ($database->hasError()) {
        $admin->print_error($database->getError());
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
 * @param string $sFilePath name of the file to read
 * @param int $size number of maximum bytes to read (0 = complete file)
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
 * @param string $sString string with Placeholder-Tokens
 * @param array $aReplace values to replace vars placeholder
 * @param string $sHem The delimiters on both sides that are being used
 *                            in PH-Tokens: '[%s]' or '{%s}'. Default is: '{{%s}}'
 * @return string  The string with replaced Placeholder Tokens
 */
function replace_vars($sString = '', &$aReplace = array(), $sHem = '{{%s}}')
{
    if (!empty($aReplace) && $sString!=null) {
        foreach ($aReplace as $sKey => $sValue) {
            $sString = str_replace(sprintf($sHem, $sKey), $sValue, $sString);
        }
    }
    return $sString;
}

/**
 * @brief   Filter directory traversal more thoroughly, thanks to hal 9000
 *
 * @param string $dir directory relative to MEDIA_DIRECTORY
 * @param bool $with_media_dir true when to include MEDIA_DIRECTORY
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

/**
 * @brief   The urlencode function and rawurlencode are mostly based on RFC 1738.
 *          However, since 2005 the current RFC in use for URIs standard is RFC 3986.
 *          Here is a function to encode URLs according to RFC 3986.
 *
 * @param string $sStr
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
 * do_regex_array
 * @brief     With this function you can preg_replace
 *            an array of RegEx commands on a string by reference
 * 
 *
 * Example usage:
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * $array = array(
 *        0 => array(
 *            'find'    => "/\=\>\n/",
 *            'replace' => "=><br /><span class=\"tab\"></span>",
 *        ),
 *        1 => array(
 *            'find'    => '/\=\>/',
 *            'replace' => "<span class=\"arrow\">=></span>",
 *        )
 *    );
 * do_regex_array($array, $content);
 *
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * @author    Christian M. Stefan  (https://www.wbEasy.de)
 * @license   http://www.gnu.org/licenses/gpl-2.0.html
 * 
 * @param  array   $aRegEx The array containing RegEx commands
 * @param  string  $str The string you want to process
 * @return string  The processed string
 *
 */
function do_regex_array(array $aRegEx, string &$str): string
{
    if (is_array($aRegEx)) {
        foreach ($aRegEx as $regex) {
            $str = preg_replace($regex['find'], $regex['replace'], $str);
        }
    }
    return $str;
}

/**
 * wbceSafePath
 * @brief Validates a custom path for WBCE and returns the safe realpath if valid.
 *
 * - Cleans the path (trim and remove control characters)
 * - Resolves realpath()
 * - Ensures the path is inside WB_PATH
 * - For files: must exist and be a regular file
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * @author    Christian M. Stefan  (https://www.wbEasy.de)
 * @license   http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @param string $path        The raw path (usually a constant value like MY_UPLOAD_PATH)
 * @param bool   $mustBeFile  true  = must be an existing file (default)
 *                            false = must be an existing directory
 * @param string $context     Context for error messages
 *
 * @return string|null        Validated realpath on success, null on failure
 */
function wbceSafePath(
    string $path,
    bool   $mustBeFile = true,
    string $context = ''
): ?string {

    if (trim($path) === '') {
        return null;
    }

    $ctx = $context !== '' ? $context . ': ' : '';

    // Clean the path
    $cleanPath = trim($path);
    $cleanPath = preg_replace('/[\x00-\x1F\x7F]/', '', $cleanPath);

    $realPath = realpath($cleanPath);
    if ($realPath === false) {
        trigger_error($ctx . 'Path does not exist or cannot be resolved.', E_USER_WARNING);
        return null;
    }

    // Type check
    if ($mustBeFile) {
        if (!is_file($realPath)) {
            trigger_error($ctx . 'Path exists but is not a regular file.', E_USER_WARNING);
            return null;
        }
    } else {
        if (!is_dir($realPath)) {
            trigger_error($ctx . 'Path exists but is not a directory.', E_USER_WARNING);
            return null;
        }
    }

    // Security: must be inside WB_PATH
    $baseReal = realpath(WB_PATH);
    if ($baseReal === false || strpos($realPath . DIRECTORY_SEPARATOR, $baseReal . DIRECTORY_SEPARATOR) !== 0) {
        trigger_error($ctx . 'Path is outside WB_PATH. Access denied for security reasons.', E_USER_WARNING);
        return null;
    }

    return $realPath;
}
/**
 * Converts a filesystem path to the corresponding URL.
 *
 * Example usage:
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * echo get_url_from_path(WB_PATH . '/modules/mymodule');
 * // or, when working from inside the directory:
 * echo get_url_from_path(__DIR__);
 * // will return: https://www.example.com/modules/mymodule
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * 
 * @param  string $path  Absolute or relative filesystem path
 * @return string        The corresponding URL under WB_URL
 */
function get_url_from_path(string $path): string
{
    // Resolve relative paths to absolute
    if (!str_starts_with($path, '/') && !str_contains($path, ':')) {
        $path = realpath($path) ?: $path;
    }

    // Normalize to forward slashes (Windows compatibility)
    $path     = str_replace('\\', '/', $path);
    $basePath = str_replace('\\', '/', rtrim(WB_PATH, '/\\'));
    $baseUrl  = rtrim(WB_URL, '/');

    // Replace base path prefix with base URL, clean up any double slashes
    $relative = ltrim(str_replace($basePath, '', $path), '/');
    return preg_replace('#(?<!:)//+#', '/', $baseUrl . '/' . $relative);
}

/**
 * @brief   Get a string between a [tagname] your string [/tagname]
 *
 * @param string $sContent The String on wich the process has to be done
 * @param string $sTagname The tag name being used
 * @return  string
 */
function get_string_between_tags($sContent, $sTagname)
{
    if (strpos($sContent, $sTagname . ']') !== false) {
        $sRegEx = "#[\s*?$sTagname\b[^>]*](.*?)[/$sTagname\b[^>]*]#s";
        if (preg_match($sRegEx, $sContent, $aMatches)) {
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
 * @param string $sHaystack the string to be checked
 * @param unspec $uNeedle can be array or single string
 * @return  bool
 */
function strposm($sHaystack, $uNeedle)
{
    if (!is_array($uNeedle)) {
        $uNeedle = array($uNeedle);
    }
    foreach ($uNeedle as $lookup) {
        if (($pos = strpos($sHaystack, $lookup)) !== false) {
            return $pos;
        }
    }
    return false;
}


function remove_special_characters($str) {
 
  // Using str_replace() function
  // to replace the word
  $res = str_replace( array( '\'','"',',',';','<','>','%','&','$','\\','/' ), '', $str);

  // Returning the result
  return $res;
}