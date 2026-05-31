<?php
declare(strict_types=1);

/**
 * CodeEditor — CodeMirror + CodeEditorToolbar integration for WBCE CMS
 *
 * Converts any <textarea id="…"> into a full-featured code editor with:
 *   - CodeMirror syntax highlighting & folding
 *   - CodeEditorToolbar (CET): font size, theme, line-wrap, fullscreen
 *   - Integrated search & replace panel
 *   - Optional AJAX save
 *
 * Part of the CodeMirror_Config module. Available globally after boot.
 *
 * @package  CodeMirror_Config
 * @author   Christian M. Stefan
 * @version  1.0.0
 *
 * Usage:
 *
 *   // Simple
 *   CodeEditor::init('my_textarea', 'php');
 *
 *   // With options
 *   CodeEditor::init('content', 'twig', [
 *       'height'    => 600,
 *       'ajax_save' => true,
 *       'ajax_url'  => WB_URL . '/modules/my_module/ajax_save.php',
 *       'ajax_data' => ['section_id' => $section_id],
 *   ]);
 *
 *   // Auto-detect syntax from file extension
 *   CodeEditor::init('content', CodeEditor::syntaxFromExt('twig'));
 */
class CodeEditor
{
    // ── State: assets loaded once per page regardless of editor count ────
    private static bool $coreLoaded = false;
    private static bool $cetLoaded  = false;

    // ── Supported syntax identifiers ─────────────────────────────────────
    private const SYNTAX_MAP = [
        // extension → [mime-string, mode-files[]]
        'php'        => ['"application/x-httpd-php"',  ['xml','css','javascript','clike','htmlmixed','php']],
        'phtml'      => ['"application/x-httpd-php"',  ['xml','css','javascript','clike','htmlmixed','php']],
        'tpl'        => ['"application/x-httpd-php"',  ['xml','css','javascript','clike','htmlmixed','php']],
        'x-php'      => ['"text/x-php"',               ['clike','php']],
        'html'       => ['"text/html"',                ['xml','css','javascript','htmlembedded','htmlmixed']],
        'htm'        => ['"text/html"',                ['xml','css','javascript','htmlembedded','htmlmixed']],
        'htt'        => ['"text/x-htt"',               ['xml','css','javascript','htmlembedded','htmlmixed']],
        'twig'       => ['{name:"twig",base:"text/html"}', ['xml','css','javascript','htmlembedded','htmlmixed','twig']],
        'css'        => ['"text/css"',                 ['css']],
        'scss'       => ['"text/x-scss"',              ['css']],
        'js'         => ['"text/javascript"',          ['javascript']],
        'javascript' => ['"text/javascript"',          ['javascript']],
        'json'       => ['"application/json"',         ['javascript']],
        'xml'        => ['"text/xml"',                 ['xml']],
        'svg'        => ['"text/xml"',                 ['xml']],
        'sql'        => ['"text/x-sql"',               ['sql']],
        'ini'        => ['"text/x-properties"',        ['properties']],
        'properties' => ['"text/x-properties"',        ['properties']],
        'md'         => ['"text/x-markdown"',          []],
        'txt'        => ['"text/plain"',               []],
    ];

    // ── Public API ────────────────────────────────────────────────────────

    /**
     * Convert a textarea into a CodeMirror editor with CodeEditorToolbar.
     *
     * @param string $textareaId  The id attribute of the <textarea>
     * @param string $syntax      Syntax identifier (e.g. 'php', 'twig', 'css')
     *                            or file extension — resolved automatically.
     * @param array  $options     Optional configuration (see below).
     *
     * Options:
     *   height      int     500     Editor height in pixels
     *   toolbar     bool    false   Show the CodeEditorToolbar
     *   line_wrap   bool    false   Line wrapping on by default
     *   readonly    bool    false   Read-only mode
     *   ajax_save   bool    false   Show AJAX save checkbox in toolbar footer
     *   ajax_url    string  ''      POST endpoint for AJAX save
     *   ajax_data   array   []      Additional POST fields (e.g. FTAN, section_id)
     *   on_save     string  ''      JS callback name called on successful save
     *   theme       string  ''      Override theme (default: from CMS settings)
     */
    public static function init(string $textareaId, string $syntax, array $options = []): void
    {
        $syntax  = self::normalizeSyntax($syntax);
        $toolbar = (bool)($options['toolbar'] ?? false);

        self::loadCore($syntax);
        if ($toolbar) {
            self::loadCET();   // CET assets only when the toolbar is actually needed
        }
        self::outputInitScript($textareaId, $syntax, $options);
    }

    /**
     * Resolve a file extension to a CodeEditor syntax identifier.
     * Safe to pass directly to init() as $syntax parameter.
     *
     * CodeEditor::init('content', CodeEditor::syntaxFromExt(pathinfo($file, PATHINFO_EXTENSION)));
     */
    public static function syntaxFromExt(string $ext): string
    {
        $ext = strtolower(ltrim($ext, '.'));
        return array_key_exists($ext, self::SYNTAX_MAP) ? $ext : 'txt';
    }

    // ── Asset loading ─────────────────────────────────────────────────────

    /**
     * Load CM core + addons + mode files. Called once per syntax per page.
     * Subsequent calls for the same syntax are deduplicated by I::insertJsFile().
     */
    private static function loadCore(string $syntax): void
    {
        $cm = self::cmUrl();

        if (!self::$coreLoaded) {
            // Core library
            I::insertJsFile([
                $cm . 'lib/codemirror.js',
                $cm . 'addon/display/fullscreen.js',
                $cm . 'addon/edit/matchbrackets.js',
                $cm . 'addon/fold/foldcode.js',
                $cm . 'addon/fold/foldgutter.js',
                $cm . 'addon/fold/brace-fold.js',
                $cm . 'addon/fold/comment-fold.js',
                $cm . 'addon/fold/xml-fold.js',
                $cm . 'addon/scroll/simplescrollbars.js',
                $cm . 'addon/mode/overlay.js',
                $cm . 'addon/mode/multiplex.js',
                $cm . 'addon/selection/active-line.js',
            ], 'BODY TOP+');

            I::insertCssFile($cm . 'lib/codemirror.css',             'HEAD TOP+');
            I::insertCssFile($cm . 'addon/fold/foldgutter.css',      'HEAD TOP+');
            I::insertCssFile($cm . 'addon/display/fullscreen.css');
            I::insertCssFile($cm . 'addon/scroll/simplescrollbars.css');

            // User theme (both loaded so JS toggle works without extra request)
            $theme = self::userTheme();
            I::insertCssFile($cm . 'theme/wbce-day.css');
            I::insertCssFile($cm . 'theme/wbce-night.css');

            // Font @font-face
            self::loadFont();

            self::$coreLoaded = true;
        }

        // Mode files — insertJsFile deduplicates, safe to call per editor
        $modeFiles = self::SYNTAX_MAP[$syntax][1] ?? [];
        $mPath     = $cm . 'mode/';
        foreach ($modeFiles as $m) {
            I::insertJsFile($mPath . $m . '/' . $m . '.js', 'BODY TOP+');
        }

        // LayoutParser overlay mode — must load after htmlmixed base
        if ($syntax === 'htt') {
            I::insertJsFile(self::cetUrl() . 'mode-htt.js', 'BODY TOP+');
        }
    }

    /**
     * Load CodeEditorToolbar JS + CSS. Once per page.
     */
    private static function loadCET(): void
    {
        if (self::$cetLoaded) return;

        $cet = self::cetUrl();

        // Tell the JS self-loader that PHP already loaded the files
        // and set CeToolbarDir to the correct CM_Config path
        $cetJs = '<script>var CET_PHP_LOADED = true; var CeToolbarDir = ' . json_encode(rtrim($cet, '/')) . ';</script>';
        I::insertJsCode($cetJs, 'BODY TOP-');

        // CSS
        I::insertCssFile($cet . 'CodeEditorToolbar.css');

        // JS — order matters: i18n before main plugin, search addons before search.js
        I::insertJsFile([
            $cet . 'i18n.js',
            $cet . 'searchcursor.js',
            $cet . 'search.js',
            $cet . 'CodeEditorToolbar.jquery.js',
        ], 'BODY BTM-');

        self::$cetLoaded = true;
    }

    // ── Init script ───────────────────────────────────────────────────────

    private static function outputInitScript(string $id, string $syntax, array $options): void
    {
        $defaults = [
            'height'    => 500,
            'toolbar'   => false,
            'line_wrap' => false,
            'readonly'  => false,
            'ajax_save' => false,
            'ajax_url'  => '',
            'ajax_data' => [],
            'on_save'   => '',
            'theme'     => '',
        ];
        $opt = array_merge($defaults, $options);

        $theme    = $opt['theme'] ?: self::userTheme();
        $mime     = self::SYNTAX_MAP[$syntax][0] ?? '"text/plain"';
        $autoGrow = (strtolower((string)$opt['height']) === 'auto');
        $heightPx = $autoGrow ? 'auto' : (int)$opt['height'] . 'px';

        // ── toolbar: false — direct CodeMirror, no CET wrapper ───────────────
        if (!(bool)$opt['toolbar']) {
            ob_start();
            ?>
<script>
(function() {
    var ta = document.getElementById(<?= json_encode($id) ?>);
    if (!ta) return;
    var editor = CodeMirror.fromTextArea(ta, {
        mode:          <?= $mime ?>,
        theme:         <?= json_encode($theme) ?>,
        lineNumbers:   false,
        lineWrapping:  <?= $opt['line_wrap'] ? 'true' : 'false' ?>,
        readOnly:      <?= $opt['readonly'] ? 'true' : 'false' ?>,
        matchBrackets: true,
    });
    <?php if (!$autoGrow): ?>
    editor.setSize(null, <?= json_encode($heightPx) ?>);
    <?php else: ?>
    editor.setSize(null, 'auto');
    editor.on('change', function() { editor.setSize(null, 'auto'); });
    <?php endif; ?>
})();
</script>
            <?php
            I::insertJsCode(ob_get_clean(), 'BODY BTM-');
            return;
        }

        // ── toolbar: true — CodeEditorToolbar (CET) plugin ───────────────────

        // JS globals the CET plugin reads (inserted at BODY TOP- so they arrive before CET JS)
        $globals = '<script>' . "\n"
            . '    var CmTheme    = ' . json_encode($theme) . ";\n"
            . '    var CmFontSize = ' . (int)self::userFontSize() . ";\n"
            . '    var CEditorCfg = JSON.parse(localStorage.getItem(\'AceCfg\')) || {};' . "\n"
            . '</script>';
        I::insertJsCode($globals, 'BODY TOP-');

        // Build ajaxData JS object
        $ajaxDataJs = '{}';
        if (!empty($opt['ajax_data']) && is_array($opt['ajax_data'])) {
            $pairs = [];
            foreach ($opt['ajax_data'] as $k => $v) {
                $pairs[] = json_encode((string)$k) . ': ' . json_encode((string)$v);
            }
            $ajaxDataJs = '{' . implode(', ', $pairs) . '}';
        }

        // CET plugin options
        $pluginOpts = [
            'initialTheme'     => $theme,
            'initialFontSize'  => self::userFontSize(),
            'initialHeight'    => $heightPx,
            'autoGrow'         => $autoGrow,
            'toolbar'          => true,
            'ajaxSaveCheckbox' => (bool)$opt['ajax_save'],
            'instanceId'       => 'ce-' . preg_replace('/[^a-z0-9_-]/i', '-', $id),
        ];

        $onSaveJs     = $opt['on_save'] ? json_encode($opt['on_save']) : 'null';
        $pluginOptsJs = json_encode($pluginOpts);

        ob_start();
        ?>
<script>
(function($) {
    $(document).ready(function() {
        var $ta = $('#<?= htmlspecialchars($id, ENT_QUOTES) ?>');
        if (!$ta.length) return;

        // Pass syntax to the CET plugin via data attribute
        $ta.attr('data-editor', '<?= htmlspecialchars($syntax, ENT_QUOTES) ?>');

        // CM mode — only set data-cm-mode for plain MIME strings.
        // Object-type modes (e.g. twig: {name,base}) must NOT be set via .attr():
        // jQuery would coerce them to "[object Object]", breaking CM mode detection.
        // Instead we leave data-cm-mode unset and rely on CET's getCMMode() fallback,
        // which already maps 'twig' → {name:'twig', base:'text/html'} correctly.
        <?php if ($mime[0] === '"'): ?>
        $ta.attr('data-cm-mode', <?= $mime ?>);
        <?php endif; ?>

        // Init CodeEditorToolbar
        var opts = <?= $pluginOptsJs ?>;

        <?php if ($opt['ajax_save'] && $opt['ajax_url']): ?>
        opts.ajaxUrl  = <?= json_encode($opt['ajax_url']) ?>;
        opts.ajaxData = <?= $ajaxDataJs ?>;
        opts.onSave   = <?= $onSaveJs ?>;
        <?php endif; ?>

        $ta.codeEditorToolbar(opts);
    });
})(jQuery);
</script>
        <?php
        I::insertJsCode(ob_get_clean(), 'BODY BTM-');
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    private static function normalizeSyntax(string $syntax): string
    {
        $s = strtolower(ltrim($syntax, '.'));
        return array_key_exists($s, self::SYNTAX_MAP) ? $s : 'txt';
    }

    private static function cmUrl(): string
    {
        static $url = null;
        if ($url === null) {
            $url = rtrim($GLOBALS['CodeMirror_dir'] ?? (WB_URL . '/modules/CodeMirror_Config/codemirror/'), '/') . '/';
        }
        return $url;
    }

    private static function cetUrl(): string
    {
        return WB_URL . '/modules/CodeMirror_Config/codemirror/cet/';
    }

    private static function userConfig(): array
    {
        static $cfg = null;
        if ($cfg === null) {
            $raw = Settings::get('cmc_cfg');
            $cfg = ($raw ? unserialize($raw) : false) ?: [
                'theme'     => 'wbce-day',
                'font'      => 'JetBrains_Mono',
                'font_size' => 15,
            ];
        }
        return $cfg;
    }

    private static function userTheme(): string
    {
        $theme = (self::userConfig())['theme'] ?? '';
        return in_array($theme, ['wbce-day', 'wbce-night'], true) ? $theme : 'wbce-night';
    }

    private static function userFontSize(): int
    {
        return (int)((self::userConfig())['font_size'] ?? 15);
    }

    private static function loadFont(): void
    {
        $cfg      = self::userConfig();
        $fontName = $cfg['font'] ?? 'JetBrains_Mono';
        $fontSize = (int)($cfg['font_size'] ?? 15);
        $cmUrl    = self::cmUrl();
        $cmPath   = str_replace(WB_URL, WB_PATH, $cmUrl);
        $fontsDir = $cmPath . 'fonts/';
        $fontsUrl = $cmUrl . 'fonts/';

        $css = '';
        if (is_dir($fontsDir)) {
            foreach (scandir($fontsDir) as $file) {
                if (!preg_match('/\.(woff2?|ttf)$/i', $file)) continue;
                $fam = pathinfo($file, PATHINFO_FILENAME);
                if ($fam !== $fontName) continue;
                $css .= "@font-face{font-family:{$fam};src:url('{$fontsUrl}{$file}');}\n";
                break;
            }
        }

        $fontFamily = ($fontName === 'default') ? 'monospace' : $fontName . ', monospace';
        $css .= ".CodeMirror{font-family:{$fontFamily};font-size:{$fontSize}px;line-height:150%;}";
        I::insertCssCode($css, 'HEAD TOP+', 'CodeEditor_font');
    }
}
