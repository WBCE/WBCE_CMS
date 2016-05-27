<?php 
// All of those framework files has been replaced 
// now if a filre loads one of this Libs multiple times the 
// include_once stops the script from throwing errors 
// for classes this file can be even completely empty as 
// the autoloader normally takes care 
include_once (__dir__."/includes/".basename(__FILE__));
