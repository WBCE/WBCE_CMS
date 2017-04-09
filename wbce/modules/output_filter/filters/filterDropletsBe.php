<?php
/**
 * execute droplets
 * @param string $content
 * @return string 
 */
	function doFilterDropletsBe($content)
	{
		if(file_exists(WB_PATH .'/modules/droplets/droplets.php')) {
			include_once(WB_PATH .'/modules/droplets/droplets.php');
			if(function_exists('evalDroplets')) {
				$content = evalDroplets($content, 'backend');
			}
		}
		return $content;
	}
