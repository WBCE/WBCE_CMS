<?php
/**
 * WBCE CMS AdminTool: wbSeoTool
 *
 * view_pageTree.php
 * Renders the SEO page tree via PageTree::load() + Twig.
 *
 * @package     wbSeoTool
 * @author      Christian M. Stefan (https://www.wbEasy.de/)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */

// prevent this file from being accessed directly
if (!defined('WB_PATH')) exit("Cannot access this file directly " . __FILE__);

// user needs permission for admintools OR pages
if (!$admin->get_permission('admintools') || !$admin->get_permission('pages')) {
    exit("insuficient privileges");
}

require_once dirname(__FILE__) . '/functions.php';

I::insertJsFile(WB_URL . '/include/htmx/htmx.min.js', 'head_early', [], 'htmx-lib');
I::insertCssFile(WB_URL . '/modules/wbSeoTool/assets/pageTree.css', 'head_late', [], 'wbseotool-css');
I::insertJsFile(WB_URL . '/modules/wbSeoTool/assets/backend_body.js', 'body_late', [], 'wbseotool-js');

function exportPageTreeToTwig()
{
    global $admin, $database;

    // getTwig() provides L_()/Ln_() and the other core SimpleFunctions, plus
    // WB_URL, INCLUDE_URL, FTAN, and (since ADMIN_TOOL_DIR is defined here —
    // we're inside an admintools tool.php run) TOOL_NAME/TOOL_URI/ADDON_URL/
    // TOOL_ICON automatically. Language strings are read via L_('TXT:KEY') /
    // L_('TEXT:KEY') directly in the templates — nothing to pass in for that.
    $oTwig = getTwig(dirname(__FILE__) . '/twig');

    // Inline SVGs (not <img src>) so stroke="currentColor" icons (Tabler set)
    // actually pick up CSS color — externally referenced <img src="*.svg">
    // never resolves currentColor against the page's stylesheet. Module-
    // specific, not a core SimpleFunction, so it's added here.
    $oTwig->addFunction(new \Twig\TwigFunction('inline_svg', function (string $path) {
        static $cache = [];
        if (!isset($cache[$path])) {
            $cache[$path] = is_file($path) ? file_get_contents($path) : '';
        }
        return $cache[$path];
    }, ['is_safe' => ['html']]));

    $oTwig->addGlobal('ICONS_PATH', dirname(__FILE__) . '/assets');
    $oTwig->addGlobal('AJAX_URL', "../../modules/" . basename(dirname(__FILE__)) . '/ajax/save.php');
    if (defined("USE_FLAGS")) {
        $oTwig->addGlobal('USE_FLAGS', USE_FLAGS);
    }
    if (defined("KEYWORDS_CONFIG")) {
        $oTwig->addGlobal('KEYWORDS_CONFIG', KEYWORDS_CONFIG);
    }
    if (defined("REWRITE_URL")) {
        $oTwig->addGlobal('REWRITE_URL', REWRITE_URL);
    }
    if (defined("USE_MENU_TITLE")) {
        $oTwig->addGlobal('USE_MENU_TITLE', USE_MENU_TITLE);
    }

    $oTemplate = $oTwig->load("pageTree.twig");

    $oTemplate->display([
        'pages' => seoPageTree($admin, $database),
    ]);
}
exportPageTreeToTwig();
