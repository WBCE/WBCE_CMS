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

/**
 * Extracts the value of a variable assignment from a PHP code string.
 *
 * Note: This function uses token_get_all() instead of a simple regular expression.
 *
 * Why it is better than the old regex version:
 * - Much more robust: correctly handles escaped quotes, comments, whitespace,
 *   and many edge cases that break simple regex patterns.
 * - Understands real PHP syntax (ignores comments, does not get confused by
 *   semicolons or quotes inside strings).
 * - Safer and more reliable when scanning module config files or upgrade scripts.
 *
 * @param  string  $search               Name of the variable (without the $ sign)
 * @param  string  $data                 The PHP code as a string
 * @param  bool    $striptags            Strip HTML tags from the value? (default: true)
 * @param  bool    $convert_to_entities  Convert special characters to HTML entities? (default: true)
 * @return string| false                 The extracted value or false if not found / not a simple string
 */
function get_variable_content($search, $data, $striptags = true, $convert_to_entities = true)
{
    // Prepend <?php so token_get_all() parses the content as PHP
    $tokens = token_get_all('<?php ' . $data);

    $i = 0;
    $count = count($tokens);

    while ($i < $count) {
        // Look for the variable $search
        if (is_array($tokens[$i]) 
            && $tokens[$i][0] === T_VARIABLE 
            && $tokens[$i][1] === '$' . $search) 
        {
            // Skip whitespace and the equals sign
            $i++;
            while ($i < $count 
                && ((is_array($tokens[$i]) && $tokens[$i][0] === T_WHITESPACE) 
                || $tokens[$i] === '=')) 
            {
                $i++;
            }

            // Check if we have a string (single or double quoted)
            if (is_array($tokens[$i]) && $tokens[$i][0] === T_CONSTANT_ENCAPSED_STRING) {
                // Remove the surrounding quotes
                $value = trim($tokens[$i][1], "'\"");

                // Optional: strip HTML tags
                if ($striptags) {
                    $value = strip_tags($value);
                }

                // Optional: convert special characters to HTML entities
                if ($convert_to_entities) {
                    $value = htmlentities($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                }

                return $value;
            }
        }
        $i++;
    }

    // Variable not found or value is not a simple string
    return false;
}

/**
 * @brief  get the version of specified Addon based on DB entry or info.php file
 *
 * @param string $sAddonDirname like saved in addons.directory
 * @param bool $bSource true reads from database, false from info.php
 * @return string  the version as string, if not found returns null
 */

function get_module_version($sAddonDirname, $bSource = true)
{
    global $database;
    $sAddonVersion = null;
    if ($bSource != true) {
        $version = $database->fetchValue(
            'SELECT `version` FROM `{TP}addons` WHERE `directory` = ?',
            [$sAddonDirname]
        );
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
function get_modul_version(...$args) {
    return get_module_version(...$args);
}


/**
 * @brief  Load module into DB
 *
 * @param string $sModulePath
 * @param bool $bInstall
 * @return
 */
function load_module($sModulePath, $bInstall = false)
{
    global $database, $admin, $MESSAGE;
    $retVal = array();
    if (is_dir($sModulePath) && file_exists($sModulePath . '/info.php')) {
        require($sModulePath . '/info.php');
        if (isset($module_name)) {
            if (!isset($module_license)) {
                $module_license = 'unknown'; // we should not assume license
                                             // type when it's not set.
            }
            if (!isset($module_platform) && isset($module_designed_for)) {
                $module_platform = $module_designed_for;
            }
            if (!isset($module_function) && isset($module_type)) {
                $module_function = $module_type;
            }
            $module_function = strtolower($module_function);
            $aData = [
                'type'        => 'module',
                'name'        => $module_name        ?? '',
                'directory'   => $module_directory   ?? '',
                'description' => $module_description ?? '',
                'function'    => $module_function    ?? '',
                'version'     => $module_version     ?? '',
                'platform'    => $module_platform    ?? '',
                'author'      => $module_author      ?? '',
                'license'     => $module_license     ?? '',
            ];
            // Check that it doesn't already exist
            if ($database->fetchValue(
                "SELECT COUNT(*) FROM `{TP}addons` WHERE `type` = 'module' AND `directory` = ?",
                [$module_directory]
            )) {
                $retVal[] = $database->upsertRow('{TP}addons', 'directory', $aData);
            } else {
                $retVal[] = $database->insertRow('{TP}addons', $aData);
            }
            // Run installation script
            if ($bInstall == true) {                
                if (file_exists($sModulePath . '/install.php')) {
                    require($sModulePath . '/install.php');
                    $retVal[] = isset($msg) ?: 'Info ' . $module_name;
                }
            }
        }
    }
    return $retVal;
}

/**
 * @brief  Load template into DB
 *
 * @param string $sTemplatePath
 * @return
 */
function load_template($sTemplatePath)
{
    global $database, $admin;
    $retVal = false;
    if (is_dir($sTemplatePath) && file_exists($sTemplatePath . '/info.php')) {
        require($sTemplatePath . '/info.php');
        if (isset($template_name)) {
            if (!isset($template_license)) {
                $template_license = 'unknown'; // we should not assume license
                                               // type when it's not set.
            }
            if (!isset($template_platform) && isset($template_designed_for)) {
                $template_platform = $template_designed_for;
            }
            if (!isset($template_function)) {
                $template_function = 'template';
            }
            if (!isset($template_description)) {
                $template_description = 'no description available';
            }
            $aData = array(
                'type'        => 'template',
                'name'        => $template_name        ?? '',
                'directory'   => $template_directory   ?? '',
                'description' => $template_description ?? '',
                'function'    => $template_function    ?? '',
                'version'     => $template_version     ?? '',
                'platform'    => $template_platform    ?? '',
                'author'      => $template_author      ?? '',
                'license'     => $template_license     ?? '',
            );
            // Check if it doesn't already exist
            if ($database->fetchValue(
                "SELECT COUNT(*) FROM `{TP}addons` WHERE `type` = 'template' AND `directory` = ?",
                [$template_directory]
            )) {
                $retVal = $database->upsertRow('{TP}addons', 'directory', $aData);
            } else {
                $retVal = $database->insertRow('{TP}addons', $aData);
            }
        }
    }
    return $retVal;
}


/**
 * @brief  Load language into DB
 *
 * @param string $sFilePath
 * @return array
 */
function load_language($sFilePath)
{
    global $database, $admin;
    $retVal = false;
    if (file_exists($sFilePath) && preg_match('#^([A-Z]{2}.php)#', basename($sFilePath))) {
        // require($sFilePath);  it's to large
        // read contents of the template language file into string
        $data = @file_get_contents(WB_PATH . '/languages/' . str_replace('.php', '', basename($sFilePath)) . '.php');
        // use regular expressions to fetch the content of the variable from the string
        $language_code        = preg_replace('/^.*([a-zA-Z]{2})\.php$/si', '\1', $sFilePath);
        $language_name        = get_variable_content('language_name',     $data, false, false);
        $language_author      = get_variable_content('language_author',   $data, false, false);
        $language_version     = get_variable_content('language_version',  $data, false, false);
        $language_platform    = get_variable_content('language_platform', $data, false, false);
        $language_description = get_variable_content('language_platform', $data, false, false);
        if (isset($language_name)) {
            if (!isset($language_license)) {
                $language_license = 'GNU General Public License';
            }
            if (!isset($language_platform) && isset($language_designed_for)) {
                $language_platform = $language_designed_for;
            }

            $aData = array(
                'type'        => 'language',
                'description' => '',
                'directory'   => $language_code     ?? '',
                'name'        => $language_name     ?? '',
                'version'     => $language_version  ?? '',
                'platform'    => $language_platform ?? '',
                'author'      => $language_author   ?? '',
                'license'     => $language_license  ?? '',
            );
            // Check that it doesn't already exist
            if ($database->fetchValue(
                "SELECT COUNT(*) FROM `{TP}addons` WHERE `type` = 'language' AND `directory` = ?",
                [$language_code]
            )) {
                $retVal = $database->upsertRow('{TP}addons', 'directory', $aData);
            } else {
                $retVal = $database->insertRow('{TP}addons', $aData);
            }
        }
    }
    return $retVal;
}

/**
 * @brief  Upgrade Module Data in DB
 *
 * @param string $sModulePath
 * @param bool $bUpgrade
 * @return
 */
function upgrade_module($sModulePath, $bUpgrade = false)
{
    global $database, $admin, $MESSAGE;
    if (file_exists($sModulePath . '/info.php')) {
        require $sModulePath . '/info.php';
        if (isset($module_name)) {
            // Check that the module does already exist
            if ($database->fetchValue(
                "SELECT COUNT(*) FROM `{TP}addons` WHERE `directory` = ?",
                [$module_directory]
            )) {
                // Update in DB
                $aUpdate = array(
                    'directory'   => $module_directory   ?? '',
                    'version'     => $module_version     ?? '',
                    'description' => $module_description ?? '',
                    'author'      => $module_author      ?? '',
                    'license'     => (!isset($module_license)) ? 'GNU General Public License' : $module_license,
                    'platform'    => (!isset($module_platform) && isset($module_designed_for)) ? $module_designed_for : $module_platform,
                    'function'    => strtolower((!isset($module_function) && isset($module_type)) ? $module_type : $module_function),
                );
                $database->upsertRow('{TP}addons', 'directory', $aUpdate);
                if ($database->hasError()) {
                    $admin->print_error($database->getError());
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
 * @brief   Function to create directories
 *
 * @param string $dir_name
 * @param string $dir_mode
 * @param bool $recursive
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
 * @brief   recursively remove a non empty directory and all its contents
 *
 * @param string $sDirPath Full path to the directory
 * @param bool $empty true if you want the folder just emptied, but not deleted
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
 * @param string $sDirPath Full path to the directory
 *                                from this dir the recursion will start
 * @param bool $show_hidden if set to TRUE also hidden dirs (.dir) will be shown
 * @return  array
 */
function directory_list($sDirPath, $show_hidden = false)
{
    $result_list = array();
    if (is_dir($sDirPath)) {
        $dir = dir($sDirPath); // Open the directory
        while (false !== $entry = $dir->read()) { // loop through the directory
            if ($entry == '.' || $entry == '..') {
                continue;
            } // Skip pointers
            if ($entry[0] == '.' && $show_hidden == false) {
                continue;
            } // Skip hidden files
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
 * @param string $sDirPath Full path to the directory
 * @param string $file_mode
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
            if ($entry[0] == '.') {
                continue;
            }
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
 * @param string $root Set a absolute rootpath as string.
 *                         If root is empty the current path will be scan.
 * @param string $search Set a search pattern for files,
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
 * @param string $sDirPath Full path to the directory
 * @param array $skip
 * @param bool $show_hidden
 * @return  array
 *
 */
function file_list($sDirPath, $skip = array(), $show_hidden = false)
{
    $result_list = array();
    if (is_dir($sDirPath)) {
        $dir = dir($sDirPath); // Open the directory
        while (false !== ($entry = $dir->read())) { // loop through the directory
            if ($entry == '.' || $entry == '..') {
                continue;
            } // Skip pointers
            if ($entry[0] == '.' && $show_hidden == false) {
                continue;
            } // Skip hidden files
            if (sizeof($skip) > 0 && in_array($entry, $skip)) {
                continue;
            } // Check if we to skip anything else
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