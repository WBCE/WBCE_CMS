<?php

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

//=====================================================================
// Some basic settings you might want to change

//you can define a list of groups.
//$topics_groups = array('group2'=>2,'group3'=>3,'group4'=>4);

$activedefault = 4; //4 = public, 1 = registered users only
$restrict2picdir = 1; //0 no, 1: Restrict movement to sections with the same picture dir

$extrafield_1_name = ''; // If these fields have a name, you can use them
$extrafield_2_name = '';
$extrafield_3_name = '';



$noadmin_nooptions = 1; //0: no, 1: only the admin may change settings.

//=====================================================================

//Some values you wont change
$fredit_default = 1; //1:frontend, 0: backend;
$use_commenting = 1; //0 = no, 1 = yes, -1 = no and dont even show options


//Advanced setting:
$serializedelimiter = '$_$';
$create_topics_accessfiles = 1;
$topics_comment_cookie = 100; //Seconds, Lifetime of the cookie

//Support other Modules:
$topic_seealso_support = '';


$topics_directory = PAGES_DIRECTORY.'/topics/'; // starts and ends with "/"
$topics_directory_depth = '../../';
$topics_search_directory = '/topics/';

//Use this, if your topics directory is besides the pages directory
/*
$topics_directory = '/topics/'; // starts and ends with "/"
$topics_directory_depth = '../';
$topics_search_directory = '/../topics/';
*/

$topics_virtual_directory = $topics_directory;





//Authors
$authorsgroup = 0; //0: Dont care about authors. >0: the group, which is restricted in editing topics.  Other groupnumbers are NOT restricted.
$author_can_change_position = false; //May change position AND/OR publishing date
$author_trust_rating = 0; //1: Can do nearly everything .... 5 can do nearly nothing; (NOTE values beetween 1 and 5 are NOT used, = 5



$paramdelimiter = '&amp;'; //use '&amp;' or '&' in special cases.

?>