<?php
/**
 * WBCE CMS AdminTool: tool_debug_dump
 * 
 * @platform    WBCE CMS 1.7.x and higher
 * @package     modules/tool_debug_dump
 * @author      Christian M. Stefan (https://www.wbEasy.de)
 * @copyright   Christian M. Stefan (2026)
 * @license     GNU/GPL2
 */

// prevent this file from being accessed directly
defined('WB_PATH') or die(header('Location: ../index.php'));

if(defined('DEBUG_DUMP_CFG') && hasSystemPermission('admintools') === true){

    // ── 1) Get Config and set URL and PATH ───────────────────────────────────────────────
    $cfg = unserialize(DEBUG_DUMP_CFG);
    $fontsPath = WB_PATH.'/modules/CodeMirror_Config/codemirror/fonts';
    $fontsUrl  = get_url_from_path($fontsPath);
    // ── 2) Get Font File ─────────────────────────────────────────────────────────────────────
    // get the current font

    require_once __DIR__ . '/function.list_files_from_dir.php';
    $fontsArray  = list_files_from_dir($fontsPath, ['woff2', 'woff']);
    foreach ($fontsArray as $file) {
        if (pathinfo($file, PATHINFO_FILENAME) === $cfg['font']) {
            $currentFontFile = $file;
            break;
        }
    }

    // ── 3 Load assets ──────────────────────────────────────────────────────────────────────
    if(isset($cfg['run']) && $cfg['run'] == true){
        if(!function_exists('debug_dump')){
            require_once __DIR__.'/function.debug_dump.php';
        }
        // Assets werden NICHT sofort injiziert — debug_dump() übernimmt das
        // beim ersten echten Aufruf (lazy injection via static guard).
        $GLOBALS['_debug_dump_assets'] = [
            'css'      => get_url_from_path(__DIR__) . '/css/debug_dump_' . $cfg['theme'] . '.css',
            'js'       => get_url_from_path(__DIR__) . '/js/debug_dump.js',
            'font_css' => "
        @font-face {
            font-family: " . $cfg['font'] . ";
            src: url(" . $fontsUrl . "/" . $currentFontFile . ");
        }
        .dd-pre {
            font-family: " . $cfg['font'] . " !important;
            font-size: " . $cfg['font_size'] . "px !important;
            max-height: " . $cfg['height'] . "px;
        }",
        ];
    }
}

// fallback function if debug_dump is disabled and for visitors w/o admin rights
if(!function_exists('debug_dump')){
    function debug_dump($mVar, $sHeading = '', $bUse_var_dump = 0, $mTwig = 0):void { 
        return;            
    }
}
    
// ── Initialize the dump() function ──────────────────────────────────────────────────────────────────────
// this is the new shorthand for exactly the same behavior as debug_dump()
if (!function_exists('dump') && function_exists('debug_dump')) {
    function dump(...$args) {
        debug_dump(...$args);
    }
}

/**
 * Check if current user has a specific system permission.
 * 
 * This initialize layer is context agnostic and neither $wb nor $admin is set.
 * Therefore we default to the $_SESSION array itself in order to check whether
 * or not the user has the necessary SYSTEM_PERMISSIONS.
 * 
 */
function hasSystemPermission(string $permission): bool
{
    return isset($_SESSION['SYSTEM_PERMISSIONS'])
        && in_array($permission, (array)$_SESSION['SYSTEM_PERMISSIONS']);
}


