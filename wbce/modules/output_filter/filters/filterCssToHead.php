<?php
/**
 * moves all css definitions from <body> into <head> section
 * @param string $content
 * @return string
 */
	function doFilterCssToHead($content) {
		// move css definitions into head section
		$pattern1 = '/(?:<body.*?)(<link[^>]*?\"text\/css\".*?\/>)/si';
		$pattern2 = '/(?:<body.*?)(<style[^>]*?\"text\/css\"[^>]*?>.*?<\/style>)/si';
		while(preg_match($pattern1, $content, $matches)==1) {
		// loop through all linked CSS
			$insert = $matches[1];
			$content = str_replace($insert, '', $content);
			$insert = "\n".$insert."\n</head>\n<body";
			$content = preg_replace('/<\/head>.*?<body/si', $insert, $content);
		}
		while(preg_match($pattern2, $content, $matches)==1) {
		// loop through all inline CSS
			$insert = $matches[1];
			$content = str_replace($insert, '', $content);
			$insert = "\n".$insert."\n</head>\n<body";
			$content = preg_replace('/<\/head>.*?<body/si', $insert, $content);
		}
		return $content;
	}
