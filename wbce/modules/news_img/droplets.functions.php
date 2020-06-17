<?php
/**
 * Rework of import droplet functions from the droplets module
 * [n] the php internal ZipArchive Class is now being used 
 * [n] splited function if ZIP import and single php file import 
 * [+] single php droplet files may be installed now using e.g.
 *       importDropletFromFile(WB_PATH.'/modules/myModules/myDroplet.php);
 * [+] you can check for existing droplets using
 *       isDroplet('myDroplet');
 *
 */

if (!function_exists('importDropletFromZip')) {
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
}

if (!function_exists('importDropletFromFile')) {
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
                        $found = $database->get_one("SELECT * FROM `" . TABLE_PREFIX . "mod_droplets` WHERE name='$name'");
                        if ($found && $found > 0) {
                                $stmt = 'REPLACE';
                                $id   = $found;
                        }
                        // execute
                        $result              = $database->query("$stmt INTO `" . TABLE_PREFIX . "mod_droplets` VALUES(
				'$id', '$name', '$code', '$description', '" . time() . "', '" . $admin->get_user_id() . "', 1, 0, 0, 0, '$usage'
			)");
                        $aReturn['imported'] = $name;
                        if ($database->is_error()) {
                                $aReturn['error'] = $database->get_error();
                        }
                }
                return $aReturn;
        }
}


if (!function_exists('isDroplet')) {
        /**
         * simple check if Droplet is already installed
         * isDroplet 
         * @param string Droplet Name
         */
        function isDroplet($sDropletName)
        {
                $tmp = $GLOBALS['database']->get_one("SELECT `id` FROM `" . TABLE_PREFIX . "mod_droplets` 
				WHERE `name` = '" . $sDropletName . "'");
                return (is_numeric($tmp)) ? intval($tmp) : false;
        }
}