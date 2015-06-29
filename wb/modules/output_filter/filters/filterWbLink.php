<?php
/*
 * replace all "[wblink{page_id}]" with real links
 * @param string &$content : reference to global $content
 * @return void
 * @history 100216 17:00:00 optimise errorhandling, speed, SQL-strict
 */
	function doFilterWbLink($content)
	{
		global $database, $wb;
		$replace_list = array();
		$pattern = '/\[wblink([0-9]+)\]/isU';
		if(preg_match_all($pattern,$content,$ids))
		{
			foreach($ids[1] as $key => $page_id) {
				$replace_list[$page_id] = $ids[0][$key];
			}
			foreach($replace_list as $page_id => $tag)
			{
				$sql = 'SELECT `link` FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.(int)$page_id;
				$link = $database->get_one($sql);
				if(!is_null($link)) {
					$link = $wb->page_link($link);
					$content = str_replace($tag, $link, $content);
				}
			}
		}
		return $content;
	}
