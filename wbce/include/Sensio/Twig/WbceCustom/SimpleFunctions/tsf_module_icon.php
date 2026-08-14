<?php
defined('WB_PATH') or exit('sorry, no sufficient privileges.');

/**
 * get a module's icon (from its info.php $module_icon), ready to print
 * — either a Font Awesome <i> tag or raw inline <svg>…</svg> markup.
 *
 * For the CURRENT admin tool's own icon, prefer the TOOL_ICON global
 * (set automatically by getTwig() from ADMIN_TOOL_DIR) — this function is
 * for looking up the icon of an arbitrary OTHER module, e.g. when listing
 * multiple tools (see admin/admintools/index.php).
 */
if (function_exists('module_icon') == false) {
    function module_icon($sModDir, $sDefaultIcon = 'fa fa-hat') {
        $oEngine = isset($GLOBALS['wb']) ? $GLOBALS['wb'] : $GLOBALS['admin'];
        return $oEngine->render_module_icon($sModDir, $sDefaultIcon);
    }
}
$oTwig->addFunction(new \Twig\TwigFunction("module_icon",
    function ($sModDir, $sDefaultIcon = 'fa fa-hat') {
        return module_icon($sModDir, $sDefaultIcon);
    }
));
