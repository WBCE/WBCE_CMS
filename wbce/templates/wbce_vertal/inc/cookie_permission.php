<?php
$about_cookies_link = '<a href="http://www.aboutcookies.org/" target="_blank">';

$langArr = array();
$langArr['en'] = 'We use cookies to give you the best experience on our website. By continuing to browse the site, you are agreeing to our use of cookies.<br />'.$about_cookies_link.'About Cookies</a>|Accept';
$langArr['de'] = 'Diese Website nutzt Cookies, um bestm&ouml;gliche Funktionalit&auml;t bieten zu k&ouml;nnen.<br/>'.$about_cookies_link.'Mehr Infos</a>|OK, verstanden';






if ( isset($_GET['lang']) ) {
	$lang = strtolower($_GET['lang']);
	$lang = preg_replace("/[^a-z]+/", "", $lang);
} else {
	$lang = 'en';
}

if (isset($langArr[$lang])) {$lang_str = $langArr[$lang]; } else {$lang_str = $langArr['en'];}
$lang_strArr = explode('|',$lang_str);
$a = $lang_strArr[0]; $b = $lang_strArr[1];
$out = '<p>'.$a.'</p><a class="cookieaccepted" href="#" onclick="accept_cookie_permission(); return false">'.$b.'</a>';
echo $out;

?>
