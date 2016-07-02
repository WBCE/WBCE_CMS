<?php 
// All of those framework files has been replaced 
// now if a file loads one of this Libs multiple times the 
// include_once stops the script from throwing errors 
// for classes this file can be even completely empty as 
// the autoloader normally takes care 

include_once (__dir__."/classes/".basename(__FILE__));
trigger_error('Manual include of framework files '.__file__.' is no longer needed you simply can rely on the autoloader and go for $object= new ClassName; ', E_USER_DEPRECATED);


