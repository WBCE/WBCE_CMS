//:Shorturl
//:
//:create short url's
//:
//Shorturl - remove pages/php from links
global $wb;
$wb->preprocess($wb_page_data);
$linkstart = WB_URL.PAGES_DIRECTORY;
$linkend = PAGE_EXTENSION;
$nwlinkstart = WB_URL;
$nwlinkend = '/';

preg_match_all('~'.$linkstart.'(.*?)\\'.$linkend.'~', $wb_page_data, $links);
foreach ($links[1] as $link) {
    $wb_page_data = str_replace($linkstart.$link.$linkend, $nwlinkstart.$link.$nwlinkend, $wb_page_data);
}
return true;
