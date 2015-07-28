<?php //--------------------------------------------------------------------------

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }
	
$seealso_array = array();
$pnsavars = array('[TOPIC_ID]', '{TITLE}', '[TITLE]', '[LINK]','[SHORT_DESCRIPTION]', '[PICTURE_DIR]', '[PICTURE]');

if ($previous_link_title == $next_link_title) {$previous_link_title = '';}



if ($see_also_text != '') {
	$see_also_text = substr($see_also_text, 1, -1);
	
	if ($topic_seealso_support == '') { 
		$theq = "SELECT * FROM ".TABLE_PREFIX."mod_".$tablename." WHERE topic_id IN (".$see_also_text.") AND active > '2' $query_extra ORDER BY $sort_topics_by";
		if ($frombackend) $theq = "SELECT * FROM ".TABLE_PREFIX."mod_".$tablename." WHERE topic_id IN (".$see_also_text.") AND active > '0' ORDER BY $sort_topics_by";												
		$query_topics = $database->query($theq); // 
		
		if($query_topics->numRows() > 0) {
			while($fetchtopic = $query_topics->fetchRow()) {						 
				if ($fetchtopic['hascontent'] == 0) {continue;} // Do not show links to topics without content
				$t_id = $fetchtopic['topic_id'];
				if ($t_id == TOPIC_ID)  {continue;} //Not show this one, this should never happen, but who knows...
												
				$seealso_array[] = $t_id;												
				$topic_link = WB_URL.$topics_virtual_directory.$fetchtopic['link'].PAGE_EXTENSION;
				if ($frombackend) {$topic_link = 'modify_topic.php?page_id='.$fetchtopic['page_id'].'&section_id='.$fetchtopic['section_id'].'&topic_id='.$fetchtopic['topic_id'].'&fredit='.$fredit;}
				$topic_title = $fetchtopic['title'];
				$topic_atitle = '<a href="'.$topic_link.'">'.$topic_title.'</a>';			
				$values = array($t_id, $topic_atitle, $topic_title, $topic_link, $fetchtopic['short_description'], $picture_dir, $fetchtopic['picture']);
				$see_also_output .= str_replace($pnsavars, $values, $setting_sa_string);						
			}
			if ($see_also_output != '') {$see_also_output = '<div class="mod_topic_seealso">'.$see_also_link_title.$see_also_output.'<div class="pnsaclear"></div></div>';}			
		}
	}
	//------------------------------------------------------------------------------------------------------------------------------------------
	//FUTURE USE  Topics lists pages 	
	if ($topic_seealso_support == 'pages') {
		$theq = "SELECT page_id,link,page_title,description,visibility FROM ".TABLE_PREFIX."pages WHERE page_id IN (".$see_also_text.") AND visibility = 'public'";														
		$query_pages = $database->query($theq); // 
		
		if($query_pages->numRows() > 0) {
			while($fetchpages = $query_pages->fetchRow()) {						 
				$thepage_id = $fetchpages['page_id'];
				if ($thepage_id == $page_id) continue;
																			
				$page_link = WB_URL.PAGES_DIRECTORY.$fetchpages['link'].PAGE_EXTENSION;
				if ($frombackend) {$page_link = ADMIN_URL.'pages/modify.php?'.$thepage_id ;}
				$page_title = $fetchpages['title'];
				$page_atitle = '<a href="'.$page_link.'">'.$page_title.'</a>';			
				$values = array($thepage_id, $page_atitle, $page_title, $page_link, $fetchpages['description'],'','');
				$see_also_output .= str_replace($pnsavars, $values, $setting_sa_string);						
			}
			if ($see_also_output != '') {$see_also_output = '<div class="mod_topic_seealso">'.$see_also_link_title.$see_also_output.'<div class="pnsaclear"></div></div>';}			
		}
	}	
	//------------------------------------------------------------------------------------------------------------------------------------------
	//Topics lists Bakery	
	if ($topic_seealso_support == 'bakery') {
		$theq = "SELECT * FROM ".TABLE_PREFIX."mod_bakery_items WHERE item_id IN (".$see_also_text.") AND active > '0'";
		if ($frombackend) $theq = "SELECT * FROM ".TABLE_PREFIX."mod_bakery_items WHERE item_id IN (".$see_also_text.") AND active > '0'";												
		$query_bakery = $database->query($theq); // 
		
		if($query_bakery->numRows() > 0) {
			while($fetchbakery = $query_bakery->fetchRow()) {						 
				$item_id = $fetchbakery['item_id'];
				$field1 = $fetchbakery['definable_field_0'];
				
																
				$bakery_link = WB_URL.PAGES_DIRECTORY.$fetchbakery['link'].PAGE_EXTENSION;
				if ($frombackend) {$bakery_link = WB_URL.'/modules/bakery/modify_item.php?page_id='.$fetchbakery['page_id'].'&section_id='.$fetchbakery['section_id'].'&item_id='.$item_id ;}
				$bakery_title = $fetchbakery['title'];
				$bakery_atitle = '<a href="'.$bakery_link.'">'.$bakery_title.'</a>';			
				$values = array($item_id, $bakery_atitle, $bakery_title, $bakery_link, $fetchbakery['description'],'',$field1);
				$see_also_output .= str_replace($pnsavars, $values, $setting_sa_string);						
			}
			if ($see_also_output != '') {$see_also_output = '<div class="mod_topic_seealso">'.$see_also_link_title.$see_also_output.'<div class="pnsaclear"></div></div>';}			
		}
	}	
} //End See_also
			
//--------------------------------------------------------------------------	
//Get Previous and next topics
//--------------------------------------------------------------------------

$see_prevnext_output = '';
$see_next_output = '';
$see_prev_output = '';
$prevnext_array = array();			
if ($show_prevnext_links AND $showmax_prev_next_links > 0) { 
		
	$sort_topics_by_prev = $sort_topics_by;
	$sort_topics_by_next = get_sort_topics_by(-1 - $sort_topics); //Reversed
	/*		
	$sort_topics_by_next = ' position ASC';
	if ($sort_topics == 1) {$sort_topics_by_next =  ' published_when ASC';}
	if ($sort_topics == 2) {$sort_topics_by_next =   ' topic_score ASC';}
	if ($sort_topics == 3) {$sort_topics_by_next =  ' published_when DESC';}
	
	//Reversed?
	if ($sort_topics < 0) {
		$sort_topics_by = ' position DESC';
		if ($sort_topics == -2) {$sort_topics_by =  ' published_when DESC, posted_first DESC';}
		if ($sort_topics == -3) {$sort_topics_by =   ' topic_score DESC';}
		if ($sort_topics == -4) {$sort_topics_by =   ' published_when ASC';}
	}
	*/
	
	
	$minactive = 2;
	$minhascontent = 1;
	if ($frombackend) {$minactive = 0; $minhascontent = 0;}
	
	
	//Next Topic:					
	$theq = "SELECT * FROM ".TABLE_PREFIX."mod_".$tablename." WHERE ".$sortkrit." AND active > '$minactive' AND hascontent >= '$minhascontent' AND section_id = '$section_id'  $query_extra  ORDER BY $sort_topics_by_next LIMIT ".($showmax_prev_next_links * 2);
	
	$query_nexttopics = $database->query($theq);
	$query_nexttopicsfound = $query_nexttopics->numRows();
	
	//Previous Topics:
	if (strpos($sortkrit,'>') > 0) {
		$sortkrit = str_replace('>','<',$sortkrit);
	} else {
		$sortkrit = str_replace('<','>',$sortkrit);
	}	
	
	//Eventkalendar
	if ($sort_topics == 3) {$sortkrit .= ' AND published_when > '.time(). ' '; }
	
	$theq = "SELECT * FROM ".TABLE_PREFIX."mod_".$tablename." WHERE ".$sortkrit." AND active > '$minactive' AND hascontent >= '$minhascontent' AND section_id = '$section_id' $query_extra ORDER BY $sort_topics_by_prev LIMIT ".($showmax_prev_next_links * 2);
	$query_prevtopics = $database->query($theq);
	$query_prevtopicsfound = $query_prevtopics->numRows();
	//echo '<h1>'.$query_nexttopicsfound.'</h1>';
	
	$i = 0;
	$found = 0;
	$inext = 0;
	$iprev = 0;
	while ($i < $showmax_prev_next_links AND $found  < $showmax_prev_next_links) {
		$i++; //Limit in any case
		
		if ($inext < $query_nexttopicsfound) {			
			$fetchtopic = $query_nexttopics->fetchRow();
			$inext++;
			//echo '<h3>'.$inext.'</h3>';
			
			//if ($fetchtopic['hascontent'] == 0) {continue;} // Do not show links to topics without content									
			$t_id = $fetchtopic['topic_id'];
			if ($t_id == TOPIC_ID)  {continue;} //Not show self			
			if(in_array($t_id,$seealso_array)) {continue;} //Not show twice
	
			//OK, show it:
			$prevnext_array[] = $t_id;							
			$topic_link = WB_URL.$topics_virtual_directory.$fetchtopic['link'].PAGE_EXTENSION;
			if ($singletopic_id == $t_id) {$topic_link = $singletopic_link;}
			if ($frombackend) {$topic_link = $modifylink.$t_id;}
			$topic_title = $fetchtopic['title'];
			$topic_atitle = '<a href="'.$topic_link.'">'.$topic_title.'</a>';
			$picture = $fetchtopic['picture'];			
			
						
			$values = array($t_id, $topic_atitle, $topic_title, $topic_link, $fetchtopic['short_description'], $picture_dir, $picture);
			$see_next_output = str_replace($pnsavars, $values, $setting_pnsa_string).$see_next_output;
			$found++;
			if ($found >= $showmax_prev_next_links) {break;}
		}
		
		
		//The same with previous Topics
		if ($iprev < $query_prevtopicsfound) {			
			$fetchtopic = $query_prevtopics->fetchRow();
			$iprev++;
			
			//if ($fetchtopic['hascontent'] == 0) {continue;} // Do not show links to topics without content						
			$t_id = $fetchtopic['topic_id'];
			if ($t_id == TOPIC_ID)  {continue;} //Not show self
			if(in_array($t_id,$seealso_array)) {continue;} //Not show twice
			
			//$counter++; if ($counter > $showmax_prev_next_links) {break;}
			//OK, show it:			
			$prevnext_array[] = $t_id;							
			$topic_link = WB_URL.$topics_virtual_directory.$fetchtopic['link'].PAGE_EXTENSION;
			if ($singletopic_id == $t_id) {$topic_link = $singletopic_link;}
			if ($frombackend) {$topic_link = $modifylink.$t_id;}
			$topic_title = $fetchtopic['title'];
			$topic_atitle = '<a href="'.$topic_link.'">'.$topic_title.'</a>';
			$picture = $fetchtopic['picture'];
				
			$values = array($t_id, $topic_atitle, $topic_title, $topic_link, $fetchtopic['short_description'], $picture_dir, $picture);
			$see_prev_output .= str_replace($pnsavars, $values, $setting_pnsa_string);
			$found++;
			if ($found >= $showmax_prev_next_links) {break;}
		}
	}
	
	
	
		
	if ($see_next_output == '' AND $previous_link_title == '') {$previous_link_title = $next_link_title;}		
	if ($see_next_output != '') {$see_next_output = '<div class="mod_topic_prevnext">'.$next_link_title.$see_next_output.'<div class="pnsaclear"></div></div>';}
	if ($see_prev_output != '') {$see_prev_output = '<div class="mod_topic_prevnext">'.$previous_link_title.$see_prev_output.'<div class="pnsaclear"></div></div>';}
	
	$see_prevnext_output = $see_next_output.$see_prev_output;
	
	//Small Design Correction if there is only ONE title defined:
	if ($next_link_title == '' OR $previous_link_title == '') {
		$see_prevnext_output = str_replace('<div class="pnsaclear"></div></div><div class="mod_topic_prevnext">', '', $see_prevnext_output);	
	}
			
}
?>
			