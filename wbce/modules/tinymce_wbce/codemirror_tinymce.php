<?php
/**
 * tinymce_wbce — codemirror_tinymce.php
 * CodeMirror 5 bridge — source code editor popup for TinyMCE.
 * Loads the CM5 assets directly (no WBCE I:: template mechanism).
 * @author  Slugger & Claude KI
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
?><!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= L_('TXT:SOURCE_CODE') ?></title>

    <?php if ($hasCM): ?>
    <!-- Load the CodeMirror 5 assets directly -->
    <link rel="stylesheet" href="<?= $cmBase ?>lib/codemirror.css">
    <link rel="stylesheet" href="<?= $cmBase ?>addon/fold/foldgutter.css">
    <link rel="stylesheet" href="<?= $cmBase ?>addon/display/fullscreen.css">
    <link rel="stylesheet" href="<?= $cmBase ?>theme/wbce-day.css">
    <link rel="stylesheet" href="<?= $cmBase ?>theme/wbce-night.css">
    <?php endif; ?>

    <style>
    * { box-sizing: border-box; }
    html, body { margin: 0; padding: 0; height: 100%; overflow: hidden; }
    #wbcm_textarea { width: 100%; }

    /* CM5 takes the full height minus the actions bar */
    .CodeMirror {
        height: calc(100vh - 42px) !important;
        font-size: 14px;
        line-height: 1.5;
    }
    .CodeMirror-scroll { height: calc(100vh - 42px) !important; }

    /* Fallback textarea */
    #wbcm_textarea:not(.cm-applied) {
        height: calc(100vh - 42px);
        width: 100%;
        font-family: monospace;
        font-size: 13px;
        padding: 8px;
        border: 0;
        resize: none;
        display: block;
    }

    /* Actions Bar */
    .cm-actions {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 42px;
        padding: 6px 10px;
        background: #f0f0f0;
        border-top: 1px solid #ccc;
        display: flex;
        gap: 8px;
        align-items: center;
        z-index: 9999;
    }
    .cm-btn {
        padding: 5px 16px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 13px;
        font-weight: bold;
    }
    .cm-btn-ok     { background: #3c6ea5; color: #fff; }
    .cm-btn-ok:hover { background: #2d5585; }
    .cm-btn-cancel { background: #6c757d; color: #fff; }
    .cm-btn-cancel:hover { background: #5a6268; }
    .cm-hint { font-size: 11px; color: #888; margin-left: 8px; }
    </style>
</head>
<body>

<textarea id="wbcm_textarea" name="wbcm_textarea"></textarea>

<div class="cm-actions">
    <button class="cm-btn cm-btn-ok" onclick="wbcmApply()">
        ✓ <?= L_('TXT:APPLY') ?>
    </button>
    <button class="cm-btn cm-btn-cancel" onclick="window.close()">
        <?= L_('TXT:CANCEL') ?>
    </button>
    <span class="cm-hint">
        <?= ($hasCM ? 'CodeMirror 5 · ' : '') . L_('TXT:CM_SHORTCUT_HINT') ?>
    </span>
</div>

<?php if ($hasCM): ?>
<!-- CM5 scripts, loaded directly -->
<script src="<?= $cmBase ?>lib/codemirror.js"></script>
<script src="<?= $cmBase ?>mode/xml/xml.js"></script>
<script src="<?= $cmBase ?>mode/css/css.js"></script>
<script src="<?= $cmBase ?>mode/javascript/javascript.js"></script>
<script src="<?= $cmBase ?>mode/htmlmixed/htmlmixed.js"></script>
<script src="<?= $cmBase ?>addon/edit/matchbrackets.js"></script>
<script src="<?= $cmBase ?>addon/selection/active-line.js"></script>
<script src="<?= $cmBase ?>addon/fold/foldcode.js"></script>
<script src="<?= $cmBase ?>addon/fold/foldgutter.js"></script>
<script src="<?= $cmBase ?>addon/fold/xml-fold.js"></script>
<script src="<?= $cmBase ?>addon/fold/brace-fold.js"></script>
<?php endif; ?>

<script>
(function() {
    var cbName = <?= json_encode($cbName) ?>;
    var theme  = <?= json_encode($theme) ?>;
    var editor = null;

    // Fetch the content from the opener window
    function getContent() {
        if (window.opener && cbName && typeof window.opener[cbName + '_get'] === 'function') {
            return window.opener[cbName + '_get']();
        }
        return '';
    }

    // Simple HTML beautifier
    function beautify(html) {
        if (!html) return '';
        // Indentation: new line + indent after each opening tag
        var result = html
            .replace(/></g, '>\n<')
            .replace(/^\s+|\s+$/gm, '')
            .split('\n');
        var indent = 0;
        var out = [];
        result.forEach(function(line) {
            if (!line.trim()) return;
            // Closing tag → decrease indentation
            if (/^<\//.test(line)) indent = Math.max(0, indent - 1);
            out.push('  '.repeat(indent) + line);
            // Opening tag without an immediate close → increase indentation
            if (/^<[^\/!]/.test(line) && !/\/>$/.test(line) && !/<\//.test(line)) indent++;
        });
        return out.join('\n');
    }

    // Init after DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        var ta = document.getElementById('wbcm_textarea');
        var content = beautify(getContent());
        ta.value = content;

        <?php if ($hasCM): ?>
        // Initialise CodeMirror
        editor = CodeMirror.fromTextArea(ta, {
            mode:           'text/html',
            theme:          theme,
            lineNumbers:    true,
            lineWrapping:   true,
            matchBrackets:  true,
            styleActiveLine: true,
            foldGutter:     true,
            gutters:        ['CodeMirror-linenumbers', 'CodeMirror-foldgutter'],
            extraKeys: {
                'Ctrl-Enter': function() { wbcmApply(); },
                'Escape':     function() { window.close(); }
            }
        });
        ta.classList.add('cm-applied');
        editor.refresh();
        <?php endif; ?>
    });

    // Apply
    window.wbcmApply = function() {
        var content;
        if (editor) {
            content = editor.getValue();
        } else {
            var ta = document.getElementById('wbcm_textarea');
            content = ta ? ta.value : '';
        }

        if (window.opener && cbName && typeof window.opener[cbName + '_set'] === 'function') {
            window.opener[cbName + '_set'](content);
        } else if (window.opener) {
            window.opener.postMessage({
                mceAction: 'codeUpdate',
                content: content
            }, window.location.origin);
        }
        window.close();
    };

    // Keyboard shortcuts (Fallback ohne CM5)
    <?php if (!$hasCM): ?>
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') { wbcmApply(); }
        if (e.key === 'Escape') { window.close(); }
    });
    <?php endif; ?>
}());
</script>

</body>
</html>
