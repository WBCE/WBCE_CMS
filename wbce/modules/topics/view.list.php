<?php
// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }
if (!isset($tablename))  { exit("No tables defined"); }

$thumb =''; 
$autoarchive_action = 0;
$ok = false;
if ($autoarchiveArr != 0) {
	$autoarchive_action = $autoarchiveArr[0];
	if ($autoarchive_action > 0) {
		$autoarchive_section = $autoarchiveArr[1];
		$autoarchive_page_id = $autoarchiveArr[2];
		
		if (($autoarchive_action * $autoarchive_section * $autoarchive_page_id) != 0) {
			$query_others = $database->query("SELECT page_id FROM ".TABLE_PREFIX."mod_".$tablename."_settings WHERE section_id = '".$autoarchive_section."'");
			if($query_others->numRows() == 1) { 
				$others = $query_others->fetchRow();			
				if ($autoarchive_page_id == $others['page_id']) { 
					$ok = true;
					if ($autoarchive_action == 3) {$limit_sql = '';}
				}
			}
		}
	}
	if ($ok == false) {echo '<h1>Could not enable AutoArchive</h1>';}
}	
if ($ok == false) { $autoarchive_action = 0; }	


// Query posts
// Get total number of topics

$theq = "SELECT * FROM ".TABLE_PREFIX."mod_".$tablename." WHERE ".$sectionquery." AND ".$qactive.$query_extra;
//echo $theq;
$query_total_num = $database->query($theq);
$total_num = $query_total_num->numRows();


	
// Create previous and next links
$previous_link = '';
$next_link = '';

//Care about autoarchive: Dont limit if 3 (after max entries, for those max entries should be moved
if ($autoarchive_action == 3)  {$autoarchive_entry = $setting_topics_per_page; $setting_topics_per_page = 0;}

if( $setting_topics_per_page != 0) {
	if ($showoffset > 0) { 
		$previous_link = '<a href="?p='.($showoffset-$setting_topics_per_page).'">'.$TEXT['PREVIOUS'].'</a>'; 
		if ($showoffset <= $setting_topics_per_page) { 
			// Get page info
			$query_page = $database->query("SELECT link FROM ".TABLE_PREFIX."pages WHERE page_id = '".PAGE_ID."'");
			//if($query_page->numRows() < 1) { exit('Page not found'); }	
			$page = $query_page->fetchRow();
			$page_link = page_link($page['link']);
			$previous_link = '<a href="'.$page_link.'">'.$TEXT['PREVIOUS'].'</a>';  			
		}
	}
	if ($showoffset+$setting_topics_per_page < $total_num) {$next_link = '<a href="?p='.($showoffset+$setting_topics_per_page).'">'.$TEXT['NEXT'].'</a>'; }
}
$prev_next_pages = '';	
if ($previous_link.$next_link != '') { 
	$omax = $showoffset+$setting_topics_per_page; if ($omax > $total_num) {$omax = $total_num;}
	$prev_next_pages = '<div class="prev_next_pages">'. $previous_link .' | '. ($showoffset + 1).'-'. $omax .' / '.$total_num. ' | '.$next_link.'</div>';
}

	
//Finaly: Do the query:
			
$theq = "SELECT * FROM ".TABLE_PREFIX."mod_".$tablename." WHERE ".$sectionquery." AND ". $qactive.$query_extra." ORDER BY ".$sort_topics_by.$limit_sql;
$query_topics = $database->query($theq);
$num_topics = $query_topics->numRows();

		
$setting_header = $settings_fetch['header'];
$setting_topics_loop = $settings_fetch['topics_loop'];
$setting_footer = $settings_fetch['footer'];
$checkwhatstring = $setting_header.$setting_footer;

$makejumplist = 0;
$jumplinks = '';
if ( strpos($checkwhatstring, '{JUMP_LINKS_LIST') !== false) {
	$makejumplist = 1;
	if ( strpos($checkwhatstring, '{JUMP_LINKS_LIST_PLUS}') !== false) {
		$makejumplist = 2;
		$setting_header = str_replace('{JUMP_LINKS_LIST_PLUS}', '{JUMP_LINKS_LIST}', $setting_header); 
		$setting_footer = str_replace('{JUMP_LINKS_LIST_PLUS}', '{JUMP_LINKS_LIST}', $setting_footer); 
	}
}


if ( $makeeditlink == true AND strpos($setting_topics_loop, '[EDITLINK]') !== false ) { $makelisteditlink = 1;} else { $makelisteditlink = 0;}

$get_long_content = 0;
if ( strpos($setting_topics_loop, '[CONTENT_LONG]') !== false AND $long_textareaheight > 0) {
	$get_long_content = 1;
	$setting_topics_loop = str_replace('[CONTENT_LONG_FIRST]', '', $setting_topics_loop); 
}
$get_extra_content = 0;
if ( strpos($setting_topics_loop, '[CONTENT_EXTRA]') !== false AND $extra_textareaheight > 0) {$get_extra_content = 1;}

$eventplaceholders = false;
if ( strpos($setting_topics_loop, '[EVENT_') !== false) {$eventplaceholders = true;}

$pictureplaceholders = false;
if ( strpos($setting_topics_loop, 'PICTURE') !== false) {$pictureplaceholders = true;}
if ( strpos($setting_topics_loop, 'THUMB') !== false) {$pictureplaceholders = true;}


//Are there are PlaceHolders for Users in the settings?, so check them:
$get_user_changed_info = false;
if ( strpos($setting_topics_loop, '[USER_') !== false ) { 	
	// Get user's username, display name, email, and id - needed for insertion into post info
	$users = array();
	$query_users = $database->query("SELECT user_id,username,display_name,email FROM ".TABLE_PREFIX."users");
	if($query_users->numRows() > 0) {
		while($user = $query_users->fetchRow()) {
			// Insert user info into users array
			$user_id = $user['user_id'];
			$users[$user_id]['username'] = $user['username'];
			$users[$user_id]['display_name'] = $user['display_name'];
			$users[$user_id]['email'] = $user['email'];
		}
	}
	
	if($query_users->numRows() > 0 AND strpos($setting_topics_loop, '[USER_MODIFIEDINFO]') !== false) {	
		$get_user_changed_info = true;	
	}
}



if ($use_commenting_settings == 1) {$commenting = $settings_fetch['commenting'];}
//if ($commenting < 0) {$use_commenting = -1;} //Outdated 2016


	
//-------------------------------------------------------------------------------------------	
// List topics
	

$output = '';
if($num_topics > 0) {
	$counter = 0;
	while($topic = $query_topics->fetchRow()) {				
		$uid = $topic['posted_by']; // User who last modified the post
						
		if ($uid == 0) continue; //Never saved
		
		$t_id = $topic['topic_id'];
		$active =  $topic['active'];
			
		$counter++;
		// Workout date and time of last modified post
		$thet =  $topic['posted_modified'];
		$posted_modi_date = gmdate(DATE_FORMAT, $thet);
		$posted_modi_time = gmdate(TIME_FORMAT, $thet);
		
		$thetp =  $topic['published_when'];
		if ($thetp == 0) {$thetp =  $topic['posted_first'];}
		$posted_publ_date = gmdate(DATE_FORMAT, $thetp);
		$posted_publ_time =  gmdate("H:i",$thetp);
		
		$thetu =  $topic['published_until'];
		
		// Work-out the topic link				
		$topic_link = WB_URL.$topics_virtual_directory.$topic['link'].PAGE_EXTENSION;				
		if(isset($_GET['p']) AND $showoffset > 0) { $topic_link .= '?p='.$showoffset; } // If the link wasnt on the first page
			
		// Title + Link:
		$title = $topic['title'];
		
		//----------------------------------------------------------------------------------
		//Criteria for autoarchive:
		if ($autoarchive_action > 0)  { 
		 
			$doarchive = false;
		
			if ($autoarchive_action == 1)  { //after startTime
				if ($t > $thetp AND $thetp != 0 ) { $doarchive = true;}
			}
			if ($autoarchive_action == 2)  { //after stopTime
				if ($t > $thetu AND $thetu != 0) { $doarchive = true; }
			}
			if ($autoarchive_action == 3)  { //after max entries			
				if ($counter > $autoarchive_entry ) { $doarchive = true; }			
			}
		
			if ($doarchive == true) {
				$filename = WB_PATH.$topics_directory.$topic['link'].PAGE_EXTENSION;					
				topics_archive_file ($filename, $t_id, $autoarchive_section, $autoarchive_page_id);
				$theq = "UPDATE ".TABLE_PREFIX."mod_".$tablename." SET page_id = '".$autoarchive_page_id."', section_id = '".$autoarchive_section."' WHERE topic_id = '".$t_id."'";
				$database->query($theq);
				
				$title = 'Archived: '.$title;
				
				//continue;
			}			
		}		
		//----------------------------------------------------------------------------------
		
		$hascontent = $topic['hascontent'];
						
		if($hascontent < 1) { 
			$titleplus = $title; $readmorelink = ''; $href = ' '; //$topic_link='#'; 
		} else { 
			$titleplus = '<a href="'.$topic_link.'">'.$title.'</a>';  $readmorelink = '<div class="tp_readmore"><a href="'.$topic_link.'">'.$TEXT['READ_MORE'].'</a></div>';
			$href = ' href="'.$topic_link.'" ';
		}
		
		
		if ($makejumplist > 0) {		
			$jumplinks .= '<li><a href="#jumptid'.$t_id.'">'.$title.'</a>';
			if ($makejumplist > 1 AND $topic['short_description'] != '' ) {$jumplinks .= '<br/><span>'.$topic['short_description'].'</span>';}
			$jumplinks .= "</li>\n";
		}
		
				
		//Handle Pictures and Thumbs:				
		$picture = $topic['picture'];
		//$thumb = $picture;
		$picture_tag = '';
		$thumb_tag = '';
		
		if ( $pictureplaceholders == true AND $picture != '') {
			$picture .= $refreshstring;			
			if (substr($picture, 0, 7) == 'http://') {
				//external file:
				$picture_tag = '<img class="tp_pic tp_pic'.$page_id.'" src="'.$picture.'" alt="'.$title.'" title="'.$title.'"/>';
				$thumb = '<img style="max-width:'.$w_thumb.'px;" class="tp_thumb_external tp_thumb tp_thumb'.$page_id.'" src="'.$picture.'" alt="" />';
			} else {
				if ($picture_dir != '') {			
					$picture_tag = '<img class="tp_pic tp_pic'.$page_id.'" src="'.$picture_dir.'/'.$picture.'" alt="'.$title.'" />';
					if ($zoomclass != '') {
						//Check if there is a picture in folder "zoom"
						$zoompic = WB_PATH.$settings_fetch['picture_dir'].'/zoom/'.$picture;			
						if (file_exists($zoompic)) { $picture_tag = '<a href="'.$picture_dir.'/zoom/'.$picture.'" target="_blank" class="'.$zoomclass.'">'.$picture_tag.'</a>'; }		
					}
				}
				$thumb = '<img class="tp_thumb tp_thumb'.$page_id.'" src="'.$picture_dir.'/thumbs/'.$picture.'" alt="'.$title.'" />';
			
			}
			$thumb_tag = $thumb;
			if ($hascontent > 0) {$thumb_tag = '<a href="'.$topic_link.'">'.$thumb.'</a>';}		
		}
		
		$topic_short = '';
		if ($short_textareaheight > 0) { $topic_short=$topic['content_short']; } 
		if ($short_textareaheight < -10) { $topic_short=nl2br($topic['content_short']); } 
		
		$topic_long = '';	
		if ($get_long_content == 1) {		
			$topic_long = $topic['content_long'];
			if ($long_textareaheight < -10) { $topic_long=nl2br($topic_long); } 
		} 
		
		//Check if the first long content should be displayed
		$topic_long_first = '';
		if ($counter == 1 AND $showoffset == 0 AND $topic_long == '' AND strpos($setting_topics_loop, '[CONTENT_LONG_FIRST]') !== false ) {
			$topic_long_first = $topic['content_long'];
			if ($long_textareaheight < -10) { $topic_long_first=nl2br($topic_long); } 
			$readmorelink = '';					
		}
		
		
		$topic_extra = '';
		if ($get_extra_content == 1) {
			$topic_extra = $topic['content_extra'];
			if ($extra_textareaheight < -10) { $topic_extra=nl2br($topic_extra); }

		} else {
			
		}
		
		$comments_count = 0;
		$commentsclass = 0;
		$commentsclass_class = '';
		if ($use_commenting >= 0) {
			$dorefresch = 1;
			if(isset($topic['comments_count'])) {
				$comments_count = $topic['comments_count'];			
				if ($comments_count < 0) { $dorefresch = 2;} else { $dorefresch = 0;}
			}
			if ($dorefresch > 0) {	
				$theq = "SELECT * FROM ".TABLE_PREFIX."mod_".$tablename."_comments WHERE topic_id = '".$t_id."' AND commented_when < '".$minimum_commentedtime."' AND active>'0'";	
				$query_comments = $database->query($theq);
				$comments_count = $query_comments->numRows();
			}
			if ($dorefresch > 1) {
				$theq = "UPDATE ".TABLE_PREFIX."mod_".$tablename." SET comments_count = '".$comments_count."' WHERE topic_id = '".$t_id."'";
				$database->query($theq);		
			}
		
			$commentsclass_class = ' ';
			if ($comments_count > 0) {
				$commentsclass = topics_commentsclass ($comments_count);
				$commentsclass_class = ' mod_topic_comments'.$commentsclass;
			}
			/*			
			if ($comments_count > 0) {$commentsclass = 1;
				if ($comments_count > 2) {$commentsclass = 2;
					if ($comments_count > 5) {$commentsclass = 3;
						if ($comments_count > 8) {$commentsclass = 4;}
					}
				}
				$commentsclass_class = ' mod_topic_comments'.$commentsclass;
			}
			*/
				
		}	//END: if ($use_commenting < 0)	
		
		
		//Placeholder [EDITLINK]
		//Eigentlich muesste bei einem Topic-Master noch ueberprueft werden, ob der User Berechtigung fuer die angegebene page_id hat. 
		//Das bremst aber zu stark ein und wird ohnehin beim Aufruf ueberprueft.
		$edit_link = '';
		if ($authoronly) {						
			$makelisteditlink = false;
			$authors = $topic['authors'];		
			$pos = strpos ($authors,','.$user_id.',');
			if ($pos !== false){$makelisteditlink = true;}	
		}
		if ($makelisteditlink == 1) { $edit_link = '<a class="tp_editlink" target="_blank" href="'.WB_URL.'/modules/'.$mod_dir.'/modify_topic.php?page_id='.$topic['page_id'].$paramdelimiter.'section_id='.$topic['section_id'].$paramdelimiter.'topic_id='.$t_id.$paramdelimiter.'fredit='.$fredit.'"></a>'; }


		$user_changed_info = '';
		if ($get_user_changed_info) {		
			$modified_byArr = explode(',',$topic['modified_by']);
			$user_idmod = $modified_byArr[(count($modified_byArr) - 1)];			
			if ($user_idmod != $uid AND $user_idmod > 0)  {
				$user_changed_info = '<span class="tp_modified">'.$MOD_TOPICS['LAST_MODIFIED'] .' '.$users[$user_idmod]['display_name'];
				if ($thet > $thetp) {$user_changed_info .= ' '. $MOD_TOPICS['MODIFIED_DATE'].' '.$posted_modi_date.' '. $MOD_TOPICS['MODIFIED_TIME'].' '.$posted_modi_time;}
				$user_changed_info .= '</span>';
			}
		}
		
		$count2 = $counter % 2; if ($count2 == 0) {$count2 = 2;}
		$count12 = $counter % 12; if ($count12 == 0) {$count12 = 12;}
		$classes = $mod_dir.'_loop mod_topic_loop mod_topic_active'.$active.$commentsclass_class.' tpcount-'.$counter.' tpcount2-'.$count2.' tpcount12-'.$count12; 
		
		
					
		// Replace vars with values
		$vars = array('[TOPIC_ID]', '[TITLE]', '{TITLE}', '[SHORT_DESCRIPTION]', '[TOPIC_SHORT]', '[LINK]', '[MODI_DATE]', '[MODI_TIME]', '[PUBL_DATE]', '[PUBL_TIME]', '[READ_MORE]', '[ACTIVE]', '[PICTURE_DIR]', '[PICTURE]', '{PICTURE}', '[THUMB]', '{THUMB}', '[COUNTER]', '[COUNTER2]','[EDITLINK]','[XTRA1]', '[XTRA2]', '[XTRA3]', '[COMMENTSCOUNT]', '[COMMENTSCLASS]', '[CLASSES]', '[HREF]');
		$values = array($t_id, $title, $titleplus, $topic['short_description'], $topic_short, $topic_link, $posted_modi_date, $posted_modi_time, $posted_publ_date, $posted_publ_time, $readmorelink, $active, $picture_dir, $picture, $picture_tag, $thumb, $thumb_tag, $counter, ($counter % 2), $edit_link, $topic['txtr1'], $topic['txtr2'], $topic['txtr3'], $comments_count, $commentsclass, $classes, $href);
		$listrow = str_replace($vars, $values, $setting_topics_loop);
		
		if (isset($users)) {	
			$vars = array('[USER_ID]', '[USER_NAME]', '[USER_DISPLAY_NAME]', '[USER_EMAIL]', '[USER_MODIFIEDINFO]');
			$values = array($uid, $users[$uid]['username'], $users[$uid]['display_name'], $users[$uid]['email'], $user_changed_info);
			$listrow = str_replace($vars, $values, $listrow);
		}
		
		
		if ($eventplaceholders == true) {			
			$the_startdate = explode('.',gmdate("d.m.Y.D.M.w.n",$topic['published_when']));
			if (isset($MOD_TOPICS['EVENT_DAYNAMES'])) {$the_startdate[3] = $MOD_TOPICS['EVENT_DAYNAMES'][$the_startdate[5]]; }
			if (isset($MOD_TOPICS['EVENT_MONTHNAMES'])) {$the_startdate[4] = $MOD_TOPICS['EVENT_MONTHNAMES'][$the_startdate[6]]; }		
			
			$thetp =  $topic['published_until'];	
			if ($thetp == 0 OR $thetp == $topic['published_when'] ) {			
				$the_stopdate= array('','','','','','','','');
				$the_stopdatetime = '';
				$the_stopdatetf = '';
			} else {			
				$the_stopdate= explode('.',gmdate("d.m.Y.D.M.w.n",$thetp));
				if (isset($MOD_TOPICS['EVENT_DAYNAMES'])) {$the_stopdate[3] = $MOD_TOPICS['EVENT_DAYNAMES'][$the_stopdate[5]]; }
				if (isset($MOD_TOPICS['EVENT_MONTHNAMES'])) {$the_stopdate[4] = $MOD_TOPICS['EVENT_MONTHNAMES'][$the_stopdate[6]]; }			
				
				$the_stopdatetf = gmdate(DATE_FORMAT, $thetp);
				$the_stopdatetime = gmdate("H:i",$thetp);
			}			
				
			$vars = array('[EVENT_START_DATE]', '[EVENT_STOP_DATE]','[EVENT_START_DAY]', '[EVENT_START_MONTH]', '[EVENT_START_YEAR]', '[EVENT_START_DAYNAME]', '[EVENT_START_MONTHNAME]', '[EVENT_START_TIME]','[EVENT_STOP_DAY]', '[EVENT_STOP_MONTH]', '[EVENT_STOP_YEAR]', '[EVENT_STOP_DAYNAME]', '[EVENT_STOP_MONTHNAME]', '[EVENT_STOP_TIME]');
			$values = array($posted_publ_date, $the_stopdatetf, $the_startdate[0], $the_startdate[1], $the_startdate[2], $the_startdate[3], $the_startdate[4], $posted_publ_time, $the_stopdate[0], $the_stopdate[1], $the_stopdate[2], $the_stopdate[3], $the_stopdate[4], $the_stopdatetime);
			$listrow = str_replace($vars, $values, $listrow);
		}
		
		if ($get_extra_content == 1) { $listrow = str_replace('[CONTENT_EXTRA]', $topic_extra, $listrow); }
		if ($get_long_content == 1) { 
			$listrow = str_replace('[CONTENT_LONG]', $topic_long, $listrow); 
		} else {
			 $listrow = str_replace('[CONTENT_LONG_FIRST]', $topic_long_first, $listrow); 
		}
		
		
		$output .= $listrow;
	}
	
} else {
	$ntp = WB_PATH.'/modules/'.$mod_dir.'/inc/no_topics.inc.php';
	if (file_exists($ntp)) {
		include($ntp);
	} else {
		$output .= $TEXT['NONE_FOUND'];
	}	
}



if ($jumplinks != '') {$jumplinks = '<ul class="topics_jumplinks">'.$jumplinks."\n</ul>\n"; }
		
// Print header
echo  str_replace(array('[SECTION_TITLE]','[SECTION_DESCRIPTION]','[PICTURE_DIR]','{PREV_NEXT_PAGES}','[PREVIOUS_LINK]','[NEXT_LINK]','[TOTALNUM]', '{JUMP_LINKS_LIST}'), array($section_title,$section_description,$picture_dir,$prev_next_pages,$previous_link,$next_link,$total_num, $jumplinks ), $setting_header);

echo $output;

// Print footer
echo  str_replace(array('[SECTION_TITLE]','[SECTION_DESCRIPTION]','[PICTURE_DIR]','{PREV_NEXT_PAGES}','[PREVIOUS_LINK]','[NEXT_LINK]','[TOTALNUM]', '{JUMP_LINKS_LIST}'), array($section_title,$section_description,$picture_dir,$prev_next_pages,$previous_link,$next_link,$total_num, $jumplinks ), $setting_footer);

?>