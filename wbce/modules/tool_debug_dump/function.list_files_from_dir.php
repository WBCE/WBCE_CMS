<?php 
/**
 * Return a list (array) of files from a specific directory
 * @author      Christian M. Stefan (https://www.wbEasy.de)
 * @copyright   Christian M. Stefan 
 * @version     0.0.1 
 * @date        10.01.2023
 * 
 * @param       string $sDirPath   // Location of the files to be listed
 * @param       mixed  $mType      // may be String or Array
 *                                    string: css, js, php, twig . . . 
 *                                    array: ['css', 'twig'] ...
 * @param       bool   $bGroup     // whether to group the list by file type
 * @return      array
 */
defined('WB_PATH') or die();
if(!function_exists('list_files_from_dir')){
    function list_files_from_dir($sDirPath = "", $mType = "", $bGroup = false): array {
        $aList = [];

        if($sDirPath == '' or $mType == ''){
            trigger_error('$sDirPath or $sType must be set');
            return $aList;
        }

        if(is_string($mType)) $mType = array($mType);

        foreach($mType as $sType){
            $sExt = '.'.$sType; 
            $iExtLen = strlen($sExt);
            foreach(scandir($sDirPath) as $k => $file){
                if(substr($file, -$iExtLen) != $sExt) continue;
                if($bGroup)
                    $aList[$sType][] = $file;
                else 
                    $aList[] = $file;
            }    
        }
        return $aList;
    }
}