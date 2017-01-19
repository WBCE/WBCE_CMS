<?php 
require('../../config.php');
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }

$mod_dir = basename(dirname(__FILE__));
$tablename = $mod_dir;
require(WB_PATH.'/modules/'.$mod_dir .'/modify.php');

?>