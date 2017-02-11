<?php

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

//new in topics 2.8 
//you can define a list of groups.
//$topics_groups = array('group2'=>2,'group3'=>3,'group4'=>4);

$activedefault = 4;

$use_pictures = 1; // 0: disable pictures, 1: enable pictures - Don't forget to also modify settings.
$picture_dir = MEDIA_DIRECTORY.'/topics-pictures'; //Default picture directory.
$usepicturechooser = 1; //0 none, 1: AJAX overlay, 2:Option-field;
$restrict2picdir = 1; //0 no, 1: Restrict movement to sections with the same picture dir

$fredit_default = 1; //1:frontend, 0: backend;

//Obsolete
$use_extra_wysiwyg = 0; //0 or 1: , a third WYSIWYG Editor.
//-----------------------------------------------------------


$extrafield_1_name = 'Picture Link'; // If these fields have a name, you can use them
$extrafield_2_name = '';
$extrafield_3_name = '';



$allow_change_link = true; //If a user can change the topic-URL
$use_getfrom = true;  // Allow to fetch settings from other sections
$use_presets = true; // Allow to fetch settings from the presets directory

$use_commenting = 1; //0 = no, 1 = yes, -1 = no and dont even show options


$allow_global_settings_change = 1; //0 = no, 1 = selectable, 2: always save settings global.

$topics_comment_cookie = 100; //Seconds, Lifetime of the cookie

$noadmin_nooptions = 1; //0: no, 1: only the admin may change settings.

//Authors
$authorsgroup = 0; //0: Dont care about authors. >0: the group, which is restricted in editing topics.  Other groupnumbers are NOT restricted.
$author_can_change_position = false; //May change position AND/OR publishing date
$author_trust_rating = 0; //1: Can do nearly everything .... 5 can do nearly nothing; (NOTE values beetween 1 and 5 are NOT used, = 5
$topics_use_plain_text = 0; //0 WYSIWYG, 1: Plaintext only , 2: future


$paramdelimiter = '&amp;'; //use '&amp;' or '&' in special cases.

// Default values:
//See also, previous, next Links: ----------------------------------------------------
$showmax_prev_next_links = 5; // how much previous/next links are shown.

if (isset($MOD_TOPICS)) {
	$see_also_link_title = '<h4>'.$MOD_TOPICS['SEE_ALSO_FRONTEND'].'</h4>';
	$next_link_title = '<h4>'.$MOD_TOPICS['SEE_NEXT_POST'].'</h4>';
	$previous_link_title = '<h4>'.$MOD_TOPICS['SEE_PREV_POST'].'</h4>';
}
//------------------------------------------------------------------------------------


if (isset($section_id)) $section_title = 'Section'.$section_id; //Default section title, if not given one
$section_description = ''; //Like the news-group-description. Default, if not given one


$commenting = '2';
$default_link = '3';
$use_captcha = true;
$sort_comments = '0';


//Advanced setting:

$serializedelimiter = "$_$";
$create_topics_accessfiles = 1;

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

?>
