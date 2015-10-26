<?php
/**
 * moves all css definitions from <body> into <head> section
 * @param string $content
 * @return string
 */
function doFilterCssToHead($sContent) {
	// move css definitions into head section
	$sPattern1 = '/(?:<body.*?)(<link[^>]*?\"text\/css\".*?\/>)/si';
	$sPattern2 = '/(?:<body.*?)(<style[^>]*?\"text\/css\"[^>]*?>.*?<\/style>)/si';
	$aInsert = array();
	while(preg_match($sPattern1, $sContent, $aMatches)) {
		$aInsert[] = $aMatches[1];
		$sContent = str_replace($aMatches[1], '', $sContent); 
	}
	while(preg_match($sPattern2, $sContent, $aMatches)) {
		$aInsert[] = $aMatches[1];
		$sContent = str_replace($aMatches[1], '', $sContent);
	}
	$aInsert = array_unique($aInsert);
	if(sizeof($aInsert) > 0) {
		$sInsert = "\n".implode("\n", $aInsert)."\n</head>\n<body";
		$sContent = preg_replace('/<\/head>.*?<body/si', $sInsert, $sContent, 1);
	}
	return $sContent;
}
