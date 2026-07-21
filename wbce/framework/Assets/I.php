<?php
/**
 * framework/Assets/I.php — backward-compatibility shim
 * 
 * @package    WBCE\Assets
 * @author     Christian M. Stefan
 * @copyright  2025-2026 Christian M. Stefan
 * @copyright  2026 WBCE CMS
 * @since      WBCE 1.7.0
 * @license    GNU/GPL v2 https://www.gnu.org/licenses/gpl-2.0.html
 * @author     Christian M. Stefan 
 * 
 * This file provides the I:: alias and the procedural wrapper functions
 * so all existing call sites (modules, templates, Twig helpers) continue
 * to work without any changes.
 *
 * Usage: nothing to do — the autoloader loads this file when I:: is first
 * referenced.  AssetQueue is loaded first (explicit require below), then
 * class_alias() makes I a full alias of AssetQueue.
 * 
 */


// I:: is a complete alias of AssetQueue — all static calls, instanceof checks,
// and new I() (though the constructor is private) resolve to AssetQueue.
class_alias('AssetQueue', 'I');

// ── Procedural aliases ────────────────────────────────────────────────────────
//
// Global functions kept for backward compatibility with modules that call
// insertJsFile() / insertCssFile() etc. without the I:: prefix.
// Signatures are identical to the old Insert/I versions.
// ─────────────────────────────────────────────────────────────────────────────

if (!function_exists('insertJsFile')) {
    function insertJsFile($uFileLoc = '', $sDomPos = 'body_late', $sID = false): void
    {
        I::insertJsFile($uFileLoc, $sDomPos, [], is_string($sID) ? $sID : '');
    }
}

if (!function_exists('insertCssFile')) {
    function insertCssFile($uFileLoc = '', $sDomPos = 'head_late', $sID = false, $sMedia = ''): void
    {
        $attrs = ($sMedia !== '' && $sMedia !== false) ? ['media' => (string)$sMedia] : [];
        I::insertCssFile($uFileLoc, $sDomPos, $attrs, is_string($sID) ? $sID : '');
    }
}

if (!function_exists('insertJsCode')) {
    function insertJsCode($sCode = '', $sDomPos = 'body_late', $sID = ''): void
    {
        I::insertJsCode($sCode, $sDomPos, is_string($sID) ? $sID : '');
    }
}

if (!function_exists('insertCssCode')) {
    function insertCssCode($sCode = '', $sDomPos = 'head_late', $sID = ''): void
    {
        I::insertCssCode($sCode, $sDomPos, is_string($sID) ? $sID : '');
    }
}

if (!function_exists('loadPlugin')) {
    function loadPlugin(string $path, string $cssPos = 'head_late', string $jsPos = 'body_late'): mixed
    {
        return I::loadPlugin($path, $cssPos, $jsPos);
    }
}

if (!function_exists('insertWebFont')) {
    function insertWebFont(string $url, string $alias = '', string $format = 'woff2'): void
    {
        I::insertWebFont($url, $alias, $format);
    }
}

if (!function_exists('insertFont')) {
    function insertFont(string|array $sources, array $options = []): void
    {
        I::insertFont($sources, $options);
    }
}
