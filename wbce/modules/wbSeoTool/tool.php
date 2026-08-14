<?php
/**
 * WBCE CMS AdminTool: wbSeoTool
 *
 * tool.php
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

Lang::loadLanguage();

I::insertCssFile(WB_URL . '/modules/wbSeoTool/assets/backend.css', 'head_late', [], 'wbseotool-backend-css');

// GET TOOL SETTINGS (shared {TP}settings table via Settings::)
$cfg = json_decode((string) Settings::get('seo_cfg', '{}'), true) ?: [];

if (!defined('REWRITE_URL') && !empty($cfg['rewriteUrl']['use'])) {
    define('REWRITE_URL', (string) $cfg['rewriteUrl']['dbString']);
}
if (!defined('KEYWORDS_CONFIG') && !empty($cfg['keywordsConfig']['use'])) {
    define('KEYWORDS_CONFIG', (string) $cfg['keywordsConfig']['wordReplace']);
}
if (!defined('USE_FLAGS') && !empty($cfg['bUseFlags'])) {
    define('USE_FLAGS', true);
}
if (!defined('USE_MENU_TITLE') && !empty($cfg['menuTitleConfig']['use'])) {
    define('USE_MENU_TITLE', true);
}
$useRemainingChars = !empty($cfg['bUseRemainingChars']);
$sCounterText = str_replace('{COUNTER_REMAINING}', ($useRemainingChars ? $TXT['COUNTER_REMAINING'] : ''), $TXT['COUNTER_STRING']);

require_once dirname(__FILE__) . '/functions.php';
?>
<noscript><?=$TXT['NOSCRIPT_MESSAGE']; ?></noscript>
<script type="text/javascript">
	var CLICK2EDIT = <?=json_encode($TXT['CLICK2EDIT']); ?>;
	var sCounterText = <?=json_encode($sCounterText); ?>;
	var iTitleCount_minimum = <?=(int) ($cfg['iTitleCount']['minimum'] ?? 30); ?>;
	var iTitleCount_optimum = <?=(int) ($cfg['iTitleCount']['optimum'] ?? 50); ?>;
	var iTitleCount_maximum = <?=(int) ($cfg['iTitleCount']['maximum'] ?? 60); ?>;
	var iDescriptionCount_minimum = <?=(int) ($cfg['iDescriptionCount']['minimum'] ?? 90); ?>;
	var iDescriptionCount_optimum = <?=(int) ($cfg['iDescriptionCount']['optimum'] ?? 150); ?>;
	var iDescriptionCount_maximum = <?=(int) ($cfg['iDescriptionCount']['maximum'] ?? 160); ?>;
	var iDescriptionCount_use = <?=!empty($cfg['iDescriptionCount']['use']) ? 1 : 0; ?>;
	var iTitleCount_use = <?=!empty($cfg['iTitleCount']['use']) ? 1 : 0; ?>;
	var STATUS_LABELS = <?=json_encode([
	    'empty'     => $TXT['STATUS_EMPTY'],
	    'short'     => $TXT['STATUS_SHORT'],
	    'optimal'   => $TXT['STATUS_OPTIMAL'],
	    'long'      => $TXT['STATUS_LONG'],
	    'off'       => $TXT['STATUS_OFF'],
	    'duplicate' => $TXT['STATUS_DUPLICATE'],
	]); ?>;
</script>
<?php

$toolUrl  = ADMIN_URL . '/admintools/tool.php?tool=' . basename(dirname(__FILE__));
$position = $_GET['pos'] ?? '';

$sFileName = ($position === 'config') ? 'modify_config' : 'view_pageTree';
$pos     = dirname(__FILE__) . '/' . $sFileName . '.php';

if (file_exists($pos)) {
    require_once($pos);
} else {
    echo "file <code>{$sFileName}.php</code> not found";
}
