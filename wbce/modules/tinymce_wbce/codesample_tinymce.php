<?php
/**
 * tinymce_wbce — codesample_tinymce.php
 * CodeMirror 5 bridge — code SAMPLE editor popup for the wbce_codesample plugin.
 * Unlike codemirror_tinymce.php (whole-document source view), this edits a single
 * fenced code block: a language dropdown drives CM5's syntax mode, and the popup
 * returns { code, language } to the opener. The code is stored verbatim (no HTML
 * beautify) inside <pre class="language-x"><code>…</code></pre>.
 * @license GNU GPL2
 */

$configPath = realpath(dirname(__FILE__) . '/../../config.php');
if (!$configPath || !file_exists($configPath)) { die('Access denied'); }
require_once $configPath;

require_once WB_PATH . '/framework/class.admin.php';
$admin = new Admin('Pages', 'pages_modify', false, false);
if (!$admin->is_authenticated()) { die('Access denied'); }

Lang::loadLanguage(__DIR__); // languages/EN.php + active language

$lang   = (defined('LANGUAGE') && strtoupper(LANGUAGE) === 'DE') ? 'de' : 'en';
$cbName = $_GET['callback'] ?? '';
if (!preg_match('/^[a-zA-Z0-9_]+$/', $cbName)) { $cbName = ''; }

// CM5 paths
$cmBase  = WB_URL . '/modules/CodeMirror_Config/codemirror/';
$cmPath  = WB_PATH . '/modules/CodeMirror_Config/codemirror/';
$hasCM   = file_exists($cmPath . 'lib/codemirror.js');

// CM5 theme from settings
$theme = 'wbce-night';
if ($hasCM) {
    require_once WB_PATH . '/framework/Settings.php';
    $cmcCfg = Settings::get('cmc_cfg');
    if ($cmcCfg) {
        $cmcArr = @unserialize($cmcCfg);
        $theme  = $cmcArr['theme'] ?? 'wbce-night';
    }
}

// Offered languages: prism token => [label, CM5 mode]. All modes ship with
// CodeMirror_Config (mode/{htmlmixed,css,javascript,php,twig,sql,xml}).
$languages = [
    'markup'     => ['HTML',       'htmlmixed'],
    'css'        => ['CSS',        'css'],
    'javascript' => ['JavaScript', 'javascript'],
    'json'       => ['JSON',       'application/json'],
    'php'        => ['PHP',        'application/x-httpd-php'],
    'twig'       => ['Twig',       'twig'],
    'sql'        => ['SQL',        'text/x-sql'],
    'xml'        => ['XML',        'xml'],
    'ini'        => ['INI',        'text/x-ini'],
];
?><!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= L_('TXT:CODESAMPLE_TITLE') ?></title>

    <?php if ($hasCM): ?>
    <link rel="stylesheet" href="<?= $cmBase ?>lib/codemirror.css">
    <link rel="stylesheet" href="<?= $cmBase ?>theme/wbce-day.css">
    <link rel="stylesheet" href="<?= $cmBase ?>theme/wbce-night.css">
    <?php endif; ?>

    <style>
    * { box-sizing: border-box; }
    html, body { margin: 0; padding: 0; height: 100%; overflow: hidden; font-family: Helvetica, Arial, sans-serif; }

    .cs-top {
        position: fixed; top: 0; left: 0; right: 0; height: 44px; z-index: 9999;
        display: flex; align-items: center; gap: 8px; padding: 6px 10px;
        background: #f0f0f0; border-bottom: 1px solid #ccc;
    }
    .cs-top label { font-size: 12px; color: #555; }
    .cs-top select { padding: 5px 8px; border: 1px solid #bbb; border-radius: 4px; font-size: 13px; }

    .CodeMirror {
        height: calc(100vh - 44px - 42px) !important;
        margin-top: 44px; font-size: 14px; line-height: 1.5;
    }
    #cs_textarea:not(.cm-applied) {
        height: calc(100vh - 44px - 42px); margin-top: 44px; width: 100%;
        font-family: monospace; font-size: 13px; padding: 8px; border: 0; resize: none; display: block;
    }

    .cm-actions {
        position: fixed; bottom: 0; left: 0; right: 0; height: 42px; padding: 6px 10px;
        background: #f0f0f0; border-top: 1px solid #ccc; display: flex; gap: 8px; align-items: center; z-index: 9999;
    }
    .cm-btn { padding: 5px 16px; border: none; border-radius: 3px; cursor: pointer; font-size: 13px; font-weight: bold; }
    .cm-btn-ok { background: #3c6ea5; color: #fff; }
    .cm-btn-ok:hover { background: #2d5585; }
    .cm-btn-cancel { background: #6c757d; color: #fff; }
    .cm-btn-cancel:hover { background: #5a6268; }
    .cm-hint { font-size: 11px; color: #888; margin-left: 8px; }
    </style>
</head>
<body>

<div class="cs-top">
    <label for="cs_language"><?= L_('TXT:CODESAMPLE_LANGUAGE') ?></label>
    <select id="cs_language">
        <?php foreach ($languages as $val => [$label, $mode]): ?>
        <option value="<?= htmlspecialchars($val) ?>" data-mode="<?= htmlspecialchars($mode) ?>"><?= htmlspecialchars($label) ?></option>
        <?php endforeach; ?>
    </select>
</div>

<textarea id="cs_textarea" name="cs_textarea"></textarea>

<div class="cm-actions">
    <button class="cm-btn cm-btn-ok" onclick="csApply()">✓ <?= L_('TXT:INSERT') ?></button>
    <button class="cm-btn cm-btn-cancel" onclick="window.close()"><?= L_('TXT:CANCEL') ?></button>
    <span class="cm-hint"><?= ($hasCM ? 'CodeMirror 5 · ' : '') . L_('TXT:CM_SHORTCUT_HINT') ?></span>
</div>

<?php if ($hasCM): ?>
<script src="<?= $cmBase ?>lib/codemirror.js"></script>
<script src="<?= $cmBase ?>mode/xml/xml.js"></script>
<script src="<?= $cmBase ?>mode/css/css.js"></script>
<script src="<?= $cmBase ?>mode/javascript/javascript.js"></script>
<script src="<?= $cmBase ?>mode/htmlmixed/htmlmixed.js"></script>
<script src="<?= $cmBase ?>mode/clike/clike.js"></script>
<script src="<?= $cmBase ?>mode/php/php.js"></script>
<script src="<?= $cmBase ?>mode/sql/sql.js"></script>
<script src="<?= $cmBase ?>mode/twig/twig.js"></script>
<script src="<?= $cmBase ?>mode/properties/properties.js"></script>
<script src="<?= $cmBase ?>addon/edit/matchbrackets.js"></script>
<script src="<?= $cmBase ?>addon/selection/active-line.js"></script>
<?php endif; ?>

<script>
(function () {
    var cbName = <?= json_encode($cbName) ?>;
    var theme  = <?= json_encode($theme) ?>;
    var editor = null;
    var select = document.getElementById('cs_language');

    function currentMode() {
        var opt = select.options[select.selectedIndex];
        return opt ? opt.getAttribute('data-mode') : 'htmlmixed';
    }

    // Pull { code, language } from the opener
    function getData() {
        if (window.opener && cbName && typeof window.opener[cbName + '_get'] === 'function') {
            return window.opener[cbName + '_get']() || {};
        }
        return {};
    }

    document.addEventListener('DOMContentLoaded', function () {
        var ta   = document.getElementById('cs_textarea');
        var data = getData();
        ta.value = data.code || '';
        if (data.language) { select.value = data.language; }

        <?php if ($hasCM): ?>
        editor = CodeMirror.fromTextArea(ta, {
            mode:            currentMode(),
            theme:           theme,
            lineNumbers:     true,
            lineWrapping:    true,
            matchBrackets:   true,
            styleActiveLine: true,
            extraKeys: {
                'Ctrl-Enter': function () { csApply(); },
                'Escape':     function () { window.close(); }
            }
        });
        ta.classList.add('cm-applied');
        // Live-switch the syntax mode when the language changes
        select.addEventListener('change', function () { editor.setOption('mode', currentMode()); });
        setTimeout(function () { editor.refresh(); }, 30);
        <?php else: ?>
        select.addEventListener('change', function () {});
        <?php endif; ?>
    });

    window.csApply = function () {
        var code = editor ? editor.getValue() : (document.getElementById('cs_textarea').value || '');
        var payload = { code: code, language: select.value };
        if (window.opener && cbName && typeof window.opener[cbName + '_set'] === 'function') {
            window.opener[cbName + '_set'](payload);
        } else if (window.opener) {
            window.opener.postMessage({ mceAction: 'codeSampleUpdate', payload: payload }, window.location.origin);
        }
        window.close();
    };

    <?php if (!$hasCM): ?>
    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') { csApply(); }
        if (e.key === 'Escape') { window.close(); }
    });
    <?php endif; ?>
}());
</script>

</body>
</html>
