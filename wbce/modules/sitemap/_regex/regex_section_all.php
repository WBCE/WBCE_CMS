<?php defined("WB_PATH") or die(); 
return array(
	'subchapters' => array(
		'find'      => '/(>)(\d+\.\d+)/s',
		'replace'   => '$1<span class="subchapter">$2 </span>',
	),  
	'subchapters2' => array(
		'find'      => '/(\<\/span\>)(\-)(\d+\.\d+)/s',
		'replace'   => '$1 $2<span class="subchapter">$3 </span> ',
	), 	
	'chapters' => array(
		'find'      => '/\>Chapter (.*?):/s',
		'replace'   => '><small class="chapter">Chapter ${1}:</small>',
	), 
);