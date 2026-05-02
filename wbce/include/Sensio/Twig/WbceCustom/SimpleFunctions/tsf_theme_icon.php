<?php
defined('WB_PATH') or exit('sorry, no sufficient privileges.');

/**
 * get Backend Theme Icon
 * get it from the DEFAULT_THEME or theme fallback
 */
if(function_exists('theme_icon') == false){
    function theme_icon($sIconName = 'none') {
        $sRetVal = '';
        $sRetVal = WB_URL.'/templates/theme_fallbacks/icons/no-icon-found.png?'.$sIconName;
        $sIconLoc = THEME_PATH.'/icons/'.$sIconName;        
        $sIconFallback = WB_PATH.'/templates/theme_fallbacks/icons/'.$sIconName;
        if(file_exists($sIconLoc)){
            $sRetVal = get_url_from_path($sIconLoc);
        } elseif (file_exists($sIconFallback)){
            $sRetVal = get_url_from_path($sIconFallback);
        } 
        return $sRetVal;
    }
}
$oTwig->addFunction(new \Twig\TwigFunction("theme_icon", 
    function ($sIconName = 'none') {
        return theme_icon($sIconName);
    }
));