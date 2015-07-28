<?php
	if ($eventplaceholders == true) {			
		$the_startdate = explode('.', gmdate("d.m.Y.D.M.w.n", $topic['published_when']));
		if (isset($MOD_TOPICS['EVENT_DAYNAMES'])) {
			$the_startdate[3] = $MOD_TOPICS['EVENT_DAYNAMES'][$the_startdate[5]]; 
		}
		if (isset($MOD_TOPICS['EVENT_MONTHNAMES'])) {
			$the_startdate[4] = $MOD_TOPICS['EVENT_MONTHNAMES'][$the_startdate[6]]; 
		}		
		
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
		
		//Wer hier spezielle Würste gebraten haben will, muss sie sich selber machen ;-)
		$event_placeholders = array(
			'[EVENT_START_DATE]'	=> $posted_publ_date, 
			'[EVENT_STOP_DATE]'		=> $the_stopdatetf, 
			'[EVENT_START_DAY]'		=> $the_startdate[0], 
			'[EVENT_START_MONTH]'	=> $the_startdate[1], 
			'[EVENT_START_YEAR]'	=> $the_startdate[2], 
			'[EVENT_START_DAYNAME]'	=> $the_startdate[3], 
			'[EVENT_START_MONTHNAME]'=> $the_startdate[4], 
			'[EVENT_START_TIME]'	=> $posted_publ_time, 
			'[EVENT_STOP_DAY]'		=> $the_stopdate[0], 
			'[EVENT_STOP_MONTH]'	=> $the_stopdate[1], 
			'[EVENT_STOP_YEAR]'		=> $the_stopdate[2], 
			'[EVENT_STOP_DAYNAME]'	=> $the_stopdate[3], 
			'[EVENT_STOP_MONTHNAME]'=> $the_stopdate[4], 
			'[EVENT_STOP_TIME]'		=> $the_stopdatetime,
		);
	
		$setting_topic_header = str_replace(array_keys($event_placeholders), array_values($event_placeholders), $setting_topic_header);
		$setting_topic_footer = str_replace(array_keys($event_placeholders), array_values($event_placeholders), $setting_topic_footer);
		$setting_topic_block2 = str_replace(array_keys($event_placeholders), array_values($event_placeholders), $setting_topic_block2);		
	}
	
	$placeholders = array(	
			'[SECTION_ID]'			=> $section_id,
			'[SECTION_TITLE]'		=> $section_title,
			'[SECTION_DESCRIPTION]'	=> $section_description,  
			'[TOPIC_ID]'			=> TOPIC_ID, 
			'[TITLE]'				=> $topic['title'], 
			'[SHORT_DESCRIPTION]'	=> $topic['short_description'], 
			'[TOPIC_SHORT]'			=> $topic_short, 
			'[TOPIC_EXTRA]'			=> $topic_extra, 
			'[META_DESCRIPTION]'	=> $topic['description'], 
			'[META_KEYWORDS]'		=> $topic['keywords'], 
			'{SEE_ALSO}'			=> $see_also_output, 
			'{SEE_PREVNEXT}'		=> $see_prevnext_output,  
			'[BACK]'				=> $page_link, 
			'[PICTURE_DIR]'			=> $picture_dir, 
			'[PICTURE]'				=> $picture, 
			'{PICTURE}'				=> $picture_tag, 
			'{PICTURE}'				=> $picture_tag, 
			'[ADDITIONAL_PICTURES]'	=> $additional_pictures,
			
			'{FULL_TOPICS_LIST}'	=> $topics_linkslist,
			
			// XTRA FIELDS
			'[XTRA1]'				=> $txtr1, 
			'[XTRA2]'				=> $txtr2, 
			'[XTRA3]'				=> $txtr3, 
			
			'[COMMENTSCOUNT]'		=> $comments_count, 
			'[COMMENTSCLASS]'		=> $commentsclass,
			'[TOPIC_SCORE]'			=> $topic_score, 
			'[EDITLINK]'			=> $edit_link,
			'[ACTIVE]'				=> $topic['active'], 
			'[MODI_DATE]'			=> $posted_modi_date, 
			'[MODI_TIME]'			=> $posted_modi_time, 
			'[PUBL_DATE]'			=> $posted_publ_date, 
			'[PUBL_TIME]'			=> $posted_publ_time, 
			'[USER_ID]'				=> $uid, 
			'[USER_MODIFIEDINFO]'	=> $user_changed_info, 
			'[ALLCOMMENTSLIST]'		=> $allcomments, 
			'[COMMENTFRAME]'		=> $commentframe,			
			
			// user (only if $user_arr is set
			'[USER_NAME]'			=> (isset($user_arr)) ? $user_arr['username'] : '', 
			'[USER_DISPLAY_NAME]'	=> (isset($user_arr)) ? $user_arr['display_name'] : '', 
			'[USER_EMAIL]'			=> (isset($user_arr)) ? $user_arr['email'] : '' 			
			);
	
	// Print topic header	
	echo str_replace(array_keys($placeholders), array_values($placeholders), $setting_topic_header);
	
	// Print Topic Long content
	echo $topic_long;
		
	// Print topic footer
	echo str_replace(array_keys($placeholders), array_values($placeholders), $setting_topic_footer);

	// Print Comments?
	if ( strpos($checkwhatstring, '[ALLCOMMENTSLIST]') === false )
		echo  $allcomments; 
		
	// Print Comment Form?
	if ( strpos($checkwhatstring, '[COMMENTFRAME]') === false )
		echo  $commentframe; 
	
	// Topic Block2
	$topic_block2 = str_replace(array_keys($placeholders), array_values($placeholders), $setting_topic_block2);
	define("TOPIC_BLOCK2", $topic_block2); //define always to prevent from double-Topics
?>	