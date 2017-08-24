<?php 
function doFilterShortUrl($content) {
    
    // $GLOBALS['wb']->preprocess($content); // this line is obsolete IMHO
    $sUrlStart = WB_URL.PAGES_DIRECTORY;
    $sUrlEnd = PAGE_EXTENSION;
    $sNewUrlStart = WB_URL;
    $sNewUrlEnd = '/';

    preg_match_all('~'.$sUrlStart.'(.*?)\\'.$sUrlEnd.'~', $content, $aLinks);
    foreach ($aLinks[1] as $sLink) {
        $content = str_replace(
            $sUrlStart.$sLink.$sUrlEnd, 
            $sNewUrlStart.$sLink.$sNewUrlEnd, 
            $content
        );
    }
    return $content;
}