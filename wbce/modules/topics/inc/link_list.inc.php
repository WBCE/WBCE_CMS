<?php


// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }
						


//--------------------------------------------------------------------------------------------------------------------------	

$topics_linkslist = '';

if (defined('TOPIC_ID')) {		
	$theq = "SELECT topic_id,title,short_description,link,position FROM ".TABLE_PREFIX."mod_".$tablename." WHERE section_id = '".$section_id."' AND ". $qactive.$query_extra." AND hascontent > 0 ORDER BY ".$sort_topics_by;
	$query_topics = $database->query($theq);
	$num_topics = $query_topics->numRows();
	
	
	if($num_topics > 0) {
		
		$ppath = WB_URL.$topics_virtual_directory;
		//if ($topics_virtual_directory == $topics_directory AND defined('TOPIC_ID')) {$ppath = '';} //a relative link will be enough
		
		$counter = 0;
		while($mtopic = $query_topics->fetchRow()) {
			$counter++;			
			$mt_id = $mtopic['topic_id'];
			if ($mt_id  == $singletopic_id) {
				$mtopic_link = $singletopic_link;
			} else {
			// Work-out the topic link				
				$mtopic_link = $ppath.$mtopic['link'].PAGE_EXTENSION;
			}											
			$mtitlelink = '<li'; if (TOPIC_ID == $mt_id) {$mtitlelink .= ' class="tp_listactive"';} $mtitlelink .='><div><a href="'.$mtopic_link.'">'.$mtopic['title'].'</a>';
			if ($mtopic['short_description'] != '') $mtitlelink .= '<br/><span>'.$mtopic['short_description'].'</span>';
			$topics_linkslist .= $mtitlelink.'</div></li>';
		}
	}
	if ($topics_linkslist != '') $topics_linkslist = '
	<ul class="topic_menu">'.$topics_linkslist.'</ul>
	';
}
	
?>
	