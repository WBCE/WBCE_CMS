<?php
/**
 * @file       upgrade.php
 * @category   admintool
 * @package    addon_monitor
 * @author     Christian M. Stefan (https://www.wbeasy.de)
 * @license    http://www.gnu.org/licenses/gpl.html
 * @platform   WBCE CMS 1.7.0
 */

// Must include code to stop this file from being accessed directly
defined('WB_PATH') or die("This file can't be accessed directly!");

// remove files and directories that are not needed any longer
$obsoleteFilesAndDirs = [
    '/css',           // css moved to backend.css
    '/skel',          // directory renamed to `twig`
    '/icons',         // no icons anymore
    '/plugins',       // no need for bloated jQuery anymore
    '/tool_icon.png', // of no use in WBCE
];
foreach($obsoleteFilesAndDirs as $rec){
    $path = __DIR__.$rec;
    $signal = removePath($path, 0, 0);
    echo(sprintf($MESSAGE[$signal], $rec)).'<br>';
}