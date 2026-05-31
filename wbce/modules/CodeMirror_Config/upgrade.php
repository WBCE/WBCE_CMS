<?php
/**
 * @file       upgrade.php
 * @category   admintool
 * @package    CodeMirror_Config
 * @author     Christian M. Stefan (https://www.wbeasy.de)
 * @license    http://www.gnu.org/licenses/gpl.html
 * @platform   WBCE CMS 1.7.0
 */

// Must include code to stop this file from being accessed directly
defined('WB_PATH') or die("This file can't be accessed directly!");

// remove files and directories that are not needed any longer
$obsoleteFilesAndDirs = [
    '/initialize.php', // changed to initialize_be.php 
];
foreach($obsoleteFilesAndDirs as $rec){
    $path = __DIR__.$rec;
    $signal = removePath($path, 0, 0);
    echo(sprintf($MESSAGE[$signal], $rec)).'<br>';
}