<?php
/**
 * framework/Assets/AssetQueue.php — class AssetQueue
 *
 * @package    WBCE\Assets
 * @author     Norbert Heimsath    (original Insert/I from which parts have been maintained)
 * @author     Christian M. Stefan (AssetQueue architecture, single-class refactor)
 * @copyright  2025-2026 Christian M. Stefan
 * @copyright  2026 WBCE CMS
 * @since      WBCE 1.7.0
 * @license    GNU/GPL v2 https://www.gnu.org/licenses/gpl-2.0.html
 * @author     Christian M. Stefan 
 * 
 * Unified asset & head-injection queue for WBCE CMS.
 * Canonical class name is AssetQueue.  The backward-compatible I:: alias
 * is provided by framework/Assets/I.php (shim) via class_alias().
 *
 * ── What changed vs the old Insert/I pair ─────────────────────────────────────
 *
 *   • No placeholder system — assets are injected by finding </title>, </head>,
 *     <body …>, </body> directly. Templates no longer need <!--(PH)…--> comments.
 *     addPlaceholdersToDom() strips any legacy comments left in old templates.
 *
 *   • Body scanner — <link>, <style>, <script>, <meta>, <title> tags that end up
 *     in the body with an asset-pos="…" attribute are automatically moved to the
 *     correct head/body anchor. Modules can tag their own tags instead of calling
 *     the queue API.
 *
 *   • CSS/JS combining + minification (insertCssBundle / insertJsBundle).
 *
 *   • Smart meta/title replace — finds the existing tag in <head> and replaces
 *     it in-place rather than appending near a placeholder.
 *
 *   • HTML queue — insertHtmlCode() for raw HTML blocks in the body.
 *
 * ── Position names ─────────────────────────────────────────────────────────────
 *
 *       Old WBCE aliases accepted
 *   |------------------|--------------------------------------|
 *   | Canonical        | Legacy                               |
 *   | New (preferred)  | Old (still accepted aliases)         |
 *   |------------------|--------------------------------------|
 *   | `head_early`     | `HEAD TOP`, `HEAD TOP+`, `HEAD TOP-` |
 *   | `head_middle`    | `HEAD+`                              |
 *   | `head_middle`    | `KEY+`, `DESC+`                      |
 *   | `head_late`      | `HEAD BTM`, `HEAD BTM+`, `HEAD BTM-` |
 *   | `head_late`      | `HEAD-`, `HEAD`                      |
 *   | `head_late`      | `HEAD MODFILES`, `CSS HEAD MODFILES` |
 *   | `body_early`     | `BODY TOP`, `BODY TOP+`, `BODY TOP-` |
 *   | `body_early`     | `BODY+`                              |
 *   | `body_late`      | `BODY BTM`, `BODY BTM+`, `BODY BTM-` |
 *   | `body_late`      | `BODY-`, `BODY`                      |
 *   | `body_late`      | `BODY MODFILES`, `JS BODY MODFILES`  |
 *   |------------------|--------------------------------------|
 *
 *    Shorthands: 'head'  → head_late   'body'   → body_late
 *                'early' → head_early  'middle' → head_middle
 *                'late'  → head_late (css) or body_late (js)
 *
 * ── Static API ─────────────────────────────────────────────────────────────────
 *
 *   I::insertCssFile('{MODULES}/foo/foo.css')
 *   I::insertCssFile('{MODULES}/foo/print.css', 'head_late', ['media' => 'print'])
 *   I::insertJsFile('{MODULES}/foo/foo.js')
 *   I::insertJsFile('{MODULES}/foo/lib.js', 'head_early')
 *   I::insertCssBundle(['{MODULES}/a/a.css', '{MODULES}/b/b.css'], 'theme')
 *   I::insertJsBundle(['{MODULES}/a/a.js',   '{MODULES}/b/b.js'],  'app')
 *   I::insertCssCode('.foo { color: red }')
 *   I::insertJsCode('window.x = 1')
 *   I::insertHtmlCode('<div class="banner">…</div>', 'body_early', 'cookie-notice')
 *   I::insertMeta('description', 'My page about chairs')
 *   I::insertMeta('<meta property="og:title" content="…">', 'replace')
 *   I::insertMetaTag(['name' => 'keywords', 'content' => 'a, b, c'])   // compat
 *   I::insertTitle('My Page Title')
 *   I::addUrlToken('{MY_MOD}', WB_URL . '/modules/my_mod')
 *   I::remove('js', 'jquery')
 *   I::clearCache()
 *
 * ── WBCE compat methods (unchanged call signature) ────────────────────────────
 *
 *   I::doFilter($content)           — OutputFilter entry point, returns string
 *   I::addPlaceholdersToDom($html)  — strips legacy <!--(PH)…--> comments
 *   I::resetTitle()
 *   I::delJs($id) / I::delCss($id)
 *   I::getQueueArray(…)
 *
 * ── asset-pos HTML attribute (body scanner) ────────────────────────────────────
 *
 *   <link rel="stylesheet" href="foo.css" asset-pos="head_early">
 *   <style asset-pos="head_early">.hero { … }</style>
 *   <script src="app.js" asset-pos="body_late"></script>
 *   <script asset-pos="body_early">window.init = true;</script>
 *
 *   <script> tags WITHOUT asset-pos are left in place.
 *
 * ── Configuration constants ────────────────────────────────────────────────────
 *
 *   Set these in var/config_constants.php as needed.
 *   All optional — sane defaults apply when not defined. 
 * 
 *  |---------------------|---------------|---------------|----------------------------------------------------------|
 *  | Constant            | Example value | Default       | Description                                              |
 *  |---------------------|---------------|---------------|----------------------------------------------------------|
 *  | MINIFY_CSS          | true          | false (off)   | Minifies CSS assets (bundles and individual files).      |
 *  |---------------------|---------------|---------------|----------------------------------------------------------|
 *  | MINIFY_JS           | true          | false (off)   | Minifies JS assets (bundles and individual files).       |
 *  |                     |               |               | Uses `matthiasmullie/minify` if present in `include/`;   |
 *  |                     |               |               | otherwise uses the built-in regex minifier.              |
 *  |---------------------|---------------|---------------|----------------------------------------------------------|
 *  | ASSET_MINIFY_DEBUG  | true          | false (off)   | **Admin only.** Disables both minification and bundling  |
 *  |                     |               |               | so browser DevTools show the original source files.      |
 *  |                     |               |               | Other visitors still receive the normal optimized output.|   
 *  |---------------------|---------------|---------------|----------------------------------------------------------|
 *  | MINIFY_USE_SUFFIX   | false         | true          | When `false`, cached files omit the `.min` suffix .      |  
 *  |---------------------|---------------|---------------|----------------------------------------------------------|
 *  | ASSET_CACHE_BUSTING | true          | false (off)   | Appends the file mod time (`?mtime`) to every asset URL. |
 *  |---------------------|---------------|---------------|----------------------------------------------------------|
 *  | WBCE_DEBUG          | true          | false (off)   | Enables `console.error()` output for administrators.     |
 *  |---------------------|---------------|---------------|----------------------------------------------------------|
 *  | MINIFY_ASSETS_DIR   | /abs/path/    | cache/assets/ | Absolute path to the cache directory for minified files  |
 *  |                     |               |               | and bundles.                                             |
 *  |---------------------|---------------|---------------|----------------------------------------------------------|
 */

// Register matthiasmullie/minify namespace.
WbAuto::AddPsr4('MatthiasMullie\\Minify',        INCLUDE_PATH . '/matthiasmullie/minify/src');
WbAuto::AddPsr4('MatthiasMullie\\PathConverter', INCLUDE_PATH . '/matthiasmullie/path-converter/src');

final class AssetQueue
{
    // ── Singleton ──────────────────────────────────────────────────────────────

    private static ?self $instance = null;

    private function __construct()
    {
        $wbPath = defined('WB_PATH') ? WB_PATH : '';

        $this->cacheDir = defined('MINIFY_ASSETS_DIR')
            ? rtrim(MINIFY_ASSETS_DIR, '/\\') . DIRECTORY_SEPARATOR
            : rtrim($wbPath, '/\\') . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR;
        if (!is_dir($this->cacheDir)) {
            if (!mkdir($this->cacheDir, 0755, true) && !is_dir($this->cacheDir)) {
                error_log('I (AssetQueue): cannot create cache directory: ' . $this->cacheDir);
            }
        }

        // Font cache directory — created lazily (only when insertWebFont is used)
        $this->fontCacheDir = rtrim($wbPath, '/\\')
            . DIRECTORY_SEPARATOR . 'cache'
            . DIRECTORY_SEPARATOR . 'fonts'
            . DIRECTORY_SEPARATOR;

        // Use matthiasmullie/minify if present, otherwise fall back to built-in regex minifier.
        // Mullie correctly handles regex literals in JS and rewrites CSS url() paths.
        if (class_exists(\MatthiasMullie\Minify\CSS::class, true)
            && class_exists(\MatthiasMullie\Minify\JS::class, true)
        ) {
            $this->cssMinifier = fn(string $css): string => (new \MatthiasMullie\Minify\CSS($css))->minify();
            $this->jsMinifier  = fn(string $js):  string => (new \MatthiasMullie\Minify\JS($js))->minify();
        } else {
            $this->cssMinifier = [$this, 'defaultMinifyCss'];
            $this->jsMinifier  = [$this, 'defaultMinifyJs'];
            // Warn once at boot when JS minification is requested but Mullie is
            // absent. The built-in fallback cannot handle line comments safely —
            // it would collapse newlines and turn every // into a file-swallowing
            // comment. The fallback only strips block comments + trims whitespace.
            if (defined('MINIFY_JS') && MINIFY_JS) {
                $includePath = defined('INCLUDE_PATH') ? INCLUDE_PATH : 'include/';
                error_log(
                    'I (AssetQueue): MINIFY_JS is enabled but MatthiasMullie\\Minify\\JS '
                  . 'is not installed. The built-in JS fallback strips block comments '
                  . 'and trims whitespace only — line comments (//…) are preserved. '
                  . 'Install matthiasmullie/minify into ' . $includePath . ' for full minification.'
                );
            }
        }
        $this->debug       = defined('WBCE_DEBUG') && WBCE_DEBUG;

        // MINIFY_CSS / MINIFY_JS enable minification per asset type.
        // MINIFY_ASSETS is a shorthand that enables both at once.
        // ASSET_MINIFY_DEBUG disables minification AND bundling for the logged-in admin only —
        // all other visitors continue to receive the minified/bundled versions.
        $adminDebug          = (defined('ASSET_MINIFY_DEBUG') && ASSET_MINIFY_DEBUG) && $this->isAdmin();
        $this->useMinifyCss  = !$adminDebug && (defined('MINIFY_CSS') && MINIFY_CSS);
        $this->useMinifyJs   = !$adminDebug && (defined('MINIFY_JS')  && MINIFY_JS);

        $this->urlTokens = $this->buildUrlTokens();
    }

    private function __clone() {}

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // ── Instance state ────────────────────────────────────────────────────────

    /** queue[position][] = ['type' => …, 'item' => …, 'attrs' => […], 'id' => …] */
    private array  $queue      = [];
    /** Dedup map — 'type|rawUrl' → true; fast first-pass dedup by exact URL string */
    private array  $seen       = [];
    /** Dedup map — 'type|/abs/path' → true; catches same file under different URL forms
     *  (token syntax vs full URL, root vs assets/ path, etc.) */
    private array  $seenPaths  = [];
    /** {TOKEN} → URL map, populated in __construct, extensible via addUrlToken() */
    private array  $urlTokens  = [];

    /** Tracks already-loaded plugin paths. Value is the return value of plugin.php (or true for json-only plugins). */
    private array       $loadedPlugins = [];

    private string      $cacheDir;
    private string      $fontCacheDir;
    private ?FontCache  $fontCache = null;
    private bool        $debug;
    private bool   $useMinifyCss;
    private bool   $useMinifyJs;
    private mixed  $jsMinifier;
    private mixed  $cssMinifier;

    // ── Minifier overrides ────────────────────────────────────────────────────

    public static function setJsMinifier(callable $fn): void
    {
        self::getInstance()->jsMinifier = $fn;
    }

    public static function setCssMinifier(callable $fn): void
    {
        self::getInstance()->cssMinifier = $fn;
    }

    // ── Default minifiers ─────────────────────────────────────────────────────

    private function defaultMinifyJs(string $js): string
    {
        // ── Conservative fallback: only strip block comments + trim whitespace ─
        //
        // Line comments (//…) are intentionally NOT stripped: a regex cannot
        // distinguish a real // comment from // inside a string literal or regex
        // pattern without a full JS tokenizer.
        //
        // CRITICAL: whitespace must NOT be collapsed across newlines.
        // Collapsing \n → space would convert every // comment into one that
        // swallows the entire remainder of the file:
        //
        //   var x = 1; // initialise     →  var x = 1; // initialise var y = 2;
        //   var y = 2;
        //
        // This fallback is intentionally limited. Install matthiasmullie/minify
        // in include/ for safe, real JS minification (block comments, dead code,
        // whitespace, and mangling).
        $js = preg_replace('/\/\*[\s\S]*?\*\//', '',  $js);  // block comments only
        $js = preg_replace('/^[ \t]+|[ \t]+$/m', '',  $js);  // trim each line (no \n)
        $js = preg_replace('/[ \t]{2,}/',        ' ', $js);  // collapse inline spaces
        $js = preg_replace('/^\h*\n/m',          '',  $js);  // drop blank lines
        return trim($js);
    }

    private function defaultMinifyCss(string $css): string
    {
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css); // comments
        $css = str_replace(["\r\n", "\r", "\n", "\t"], '', $css);       // newlines / tabs
        $css = preg_replace('/\s*([{};,:])\s*/', '$1', $css);           // around punctuation
        $css = preg_replace('/\s+/', ' ', $css);                        // remaining spaces
        return trim($css);
    }

    // ── Public static API — File loading ─────────────────────────────────────

    /**
     * Queue a CSS file.
     *
     *   I::insertCssFile('{MODULES}/foo/foo.css')
     *   I::insertCssFile('{MODULES}/foo/print.css', 'head_late', ['media' => 'print'])
     *   I::insertCssFile(['{MODULES}/a/a.css', '{MODULES}/b/b.css'])   // array
     *
     * @param string|array $source   File URL or array of file URLs.
     * @param string       $position Target position (new or legacy name).
     * @param array        $attrs    Extra <link> attributes, e.g. ['media' => 'print'].
     * @param string       $id       Optional dedup/removal key.
     */
    public static function insertCssFile(
        string|array $source,
        string       $position = 'head_late',
        array|string $attrs    = [],
        string       $id       = ''
    ): void {
        // Backward compat: old API passed $id as 3rd arg, $media as 4th
        if (is_string($attrs)) {
            $id    = $attrs;
            $attrs = [];
        }
        $inst = self::getInstance();
        foreach ((array)$source as $file) {
            $file = trim((string)$file);
            if ($file === '') continue;
            $inst->enqueue('css', $file, $inst->normalizePos($position, 'css'), $attrs, $id);
        }
    }

    /**
     * Queue a JavaScript file.
     *
     *   I::insertJsFile('{MODULES}/foo/foo.js')
     *   I::insertJsFile('{MODULES}/foo/lib.js', 'head_early')
     *   I::insertJsFile('{MODULES}/foo/mod.js', 'head_early', ['type' => 'module'])
     *   I::insertJsFile('{MODULES}/foo/mod.js', 'body_late',  ['defer' => true])
     *
     * @param string|array $source   File URL or array of file URLs.
     * @param string       $position Target position (new or legacy name).
     * @param array        $attrs    Extra <script> attributes.
     * @param string       $id       Optional dedup/removal key.
     */
    public static function insertJsFile(
        string|array $source,
        string       $position = 'body_late',
        array|string $attrs    = [],
        string       $id       = ''
    ): void {
        // Backward compat: old API passed $id as 3rd arg
        if (is_string($attrs)) {
            $id    = $attrs;
            $attrs = [];
        }
        $inst = self::getInstance();
        foreach ((array)$source as $file) {
            $file = trim((string)$file);
            if ($file === '') continue;
            $pos = $inst->normalizePos($position, 'js');
            $inst->enqueue('js', $file, $pos, $attrs, $id);
        }
    }

    /**
     * Queue a combined (merged + file-cached) CSS bundle.
     * Sources are concatenated, optionally minified, and written to cache/assets/.
     * The cache is invalidated automatically when any source file changes (mtime).
     *
     *   I::insertCssBundle(['{MODULES}/a/a.css', '{MODULES}/b/b.css'], 'admin-ui')
     */
    /**
     * Queue a combined (merged + file-cached) CSS bundle.
     * Remote/CDN sources are extracted and queued individually after the bundle.
     * When MINIFY_ASSETS_DEBUG is active, bundling is skipped entirely and each
     * source is queued as a plain insertCssFile() so DevTools shows file names.
     *
     *   I::insertCssBundle(['{TEMPLATE}/css/a.css', '{TEMPLATE}/css/b.css'], 'my-bundle')
     *   I::insertCssBundle([...], 'my-bundle', 'head_early')
     */
    public static function insertCssBundle(
        array  $sources,
        string $identifier,
        string $position = 'head_late',
        array  $attrs    = []
    ): void {
        if (empty($sources)) return;
        $inst = self::getInstance();
        $pos  = $inst->normalizePos($position, 'css');

        // Debug mode (admin only): skip bundling, load every source individually
        if ((defined('MINIFY_ASSETS_DEBUG') && MINIFY_ASSETS_DEBUG) && $inst->isAdmin()) {
            foreach ($sources as $src) {
                $src = trim((string)$src);
                if ($src !== '') $inst->enqueue('css', $src, $pos, $attrs, '');
            }
            return;
        }

        [$local, $remote] = $inst->splitLocalRemote($sources);

        if (!empty($local)) {
            $url = $inst->buildCombined('css', $local, $identifier);
            if ($url !== null) {
                $inst->markCombinedSources('css', $local);
                $inst->enqueue('css', $url, $pos, $attrs, $identifier);
            }
        }

        // CDN sources: queue individually after the bundle (document order)
        foreach ($remote as $src) {
            $inst->enqueue('css', $src, $pos, [], '');
        }
    }

    /**
     * Queue a combined (merged + file-cached) JS bundle.
     * Remote/CDN sources are extracted and queued individually after the bundle.
     * When MINIFY_ASSETS_DEBUG is active, bundling is skipped entirely.
     *
     *   I::insertJsBundle(['{MODULES}/a/a.js', '{MODULES}/b/b.js'], 'app')
     */
    public static function insertJsBundle(
        array  $sources,
        string $identifier,
        string $position = 'body_late',
        array  $attrs    = []
    ): void {
        if (empty($sources)) return;
        $inst = self::getInstance();
        $pos  = $inst->normalizePos($position, 'js');

        // Debug mode (admin only): skip bundling, load every source individually
        if ((defined('MINIFY_ASSETS_DEBUG') && MINIFY_ASSETS_DEBUG) && $inst->isAdmin()) {
            foreach ($sources as $src) {
                $src = trim((string)$src);
                if ($src !== '') {
                    $inst->enqueue('js', $src, $pos, $attrs, '');
                }
            }
            return;
        }

        [$local, $remote] = $inst->splitLocalRemote($sources);

        if (!empty($local)) {
            $url = $inst->buildCombined('js', $local, $identifier);
            if ($url !== null) {
                $inst->markCombinedSources('js', $local);
                $inst->enqueue('js', $url, $pos, $attrs, $identifier);
            }
        }

        // CDN sources: queue individually after the bundle (document order)
        foreach ($remote as $src) {
            $inst->enqueue('js', $src, $pos, [], '');
        }
    }


    // ── Public static API — Google Fonts (local caching) ─────────────────────

    /**
     * Self-host a web font: download CSS + font files once into cache/fonts/,
     * rewrite all url() references to local paths, then queue the local CSS at
     * head_early — no visitor IP ever reaches external font servers.
     *
     * Works with any CSS-based font provider:
     *   Google Fonts, Bunny Fonts, Fontshare, Fontsource CDN, custom CDNs …
     * Google Fonts and Bunny Fonts use UA-based format selection; all other
     * providers are fetched with a neutral UA and return woff2 directly.
     *
     * Cache key = md5($url . '|' . $format).  Changing the URL (e.g. adding a
     * weight) produces a new key and triggers a fresh download on the next load.
     * Old files are kept until I::clearFontCache() is called.
     *
     * Admin force-refresh: CTRL+F5 while logged in re-downloads the font.
     * cURL fallback: when cURL is unavailable the original URL is queued
     * directly (fonts still load, GDPR benefit is lost).
     *
     *   I::insertWebFont('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');
     *   I::insertWebFont('https://fonts.bunny.net/css?family=inter:400,600');
     *   I::insertWebFont('https://api.fontshare.com/v2/css?f[]=satoshi@400,700&display=swap');
     *   I::insertWebFont($url, 'MainFont');              // only 'MainFont' in output, original name suppressed
     *   I::insertWebFont($url, 'MainFont', 'woff2-unicode');
     *   I::insertWebFont('<link href="https://…" rel="stylesheet">'); // full tag accepted
     *
     * @param string $url    Font CSS URL (any provider) or a complete <link> tag.
     * @param string $alias  Optional semantic font-family alias (e.g. 'MainFont').
     *                       When set, only the alias @font-face blocks are output —
     *                       the original provider font-family name is suppressed.
     *                       To have both names, call the method twice (once without alias).
     * @param string $format UA hint for format selection: 'woff2' (default),
     *                       'woff2-unicode', 'woff', 'ttf'.
     */
    public static function insertWebFont(
        string $url,
        string $alias  = '',
        string $format = 'woff2'
    ): void {
        self::getInstance()->enqueueWebFont($url, $alias, $format);
    }

    /**
     * Queue a @font-face rule for one or more directly hosted font files.
     * Handles token expansion ({TEMPLATE}, {MODULES}, …) and optional preload hints.
     *
     *   // Preload only — no @font-face (family omitted)
     *   I::insertFont('{TEMPLATE}/fonts/inter-400.woff2');
     *
     *   // @font-face with custom family name
     *   I::insertFont(
     *       '{TEMPLATE}/fonts/inter.woff2',
     *       ['family' => 'MainFont', 'weight' => '400', 'display' => 'swap']
     *   );
     *
     *   // Multiple formats (woff2 preferred, woff as fallback)
     *   I::insertFont(
     *       [
     *           '{TEMPLATE}/fonts/inter.woff2' => 'woff2',
     *           '{TEMPLATE}/fonts/inter.woff'  => 'woff',
     *       ],
     *       ['family' => 'MainFont', 'weight' => '400 700', 'preload' => true]
     *   );
     *
     *   // Variable font
     *   I::insertFont(
     *       '{TEMPLATE}/fonts/inter-variable.woff2',
     *       ['family' => 'MainFont', 'weight' => '100 900', 'display' => 'swap']
     *   );
     *
     * @param string|array $sources  Path/URL to font file, or [path => format, …] map.
     * @param array        $options  Recognised keys: family, weight (default '400'),
     *                               style (default 'normal'), display (default 'swap'),
     *                               preload (bool, default false).
     */
    public static function insertFont(string|array $sources, array $options = []): void
    {
        self::getInstance()->enqueueFont($sources, $options);
    }

    /**
     * Load a plugin by directory path (relative to WB_PATH).
     *
     * Reads plugin.json from the plugin directory and queues all declared CSS
     * and JS files.  Required plugins (declared under "require") are loaded first.
     * Each plugin is loaded at most once per request — duplicate calls and circular
     * require chains are silently ignored.
     *
     * Cache busting (ASSET_CACHE_BUSTING) is applied automatically to every file.
     *
     *   plugin.json format:
     *   {
     *       "css":     ["wbeSelect.css"],
     *       "js":      ["wbeSelect.jquery.js"],
     *       "require": ["include/jquery-slim"]
     *   }
     *
     *   plugin.php format (for plugins needing runtime setup, e.g. locale, date format):
     *   The file handles its own asset loading via I:: and returns an array of values
     *   the caller may need (or returns nothing). loadPlugin() returns that array.
     *   If plugin.php exists, plugin.json is ignored for that plugin directory.
     *
     *   // Caller:
     *   extract(I::loadPlugin('include/date_time_picker'));
     *   // → $fp_locale_key, $fp_dateFormat, $fp_php_format now in scope
     *
     *   I::loadPlugin('include/wbeSelect');
     *   I::loadPlugin('include/wbeSelect', 'head_early');           // CSS position override
     *   I::loadPlugin('include/wbeSelect', 'head_late', 'head_early'); // JS in head
     *
     * @param string $path    Plugin directory relative to WB_PATH (e.g. 'include/wbeSelect').
     * @param string $cssPos  Queue position for CSS files (default: 'head_late').
     * @param string $jsPos   Queue position for JS files  (default: 'body_late').
     * @return mixed          Return value of plugin.php, or null for json-only plugins.
     */
    public static function loadPlugin(
        string $path,
        string $cssPos = 'head_late',
        string $jsPos  = 'body_late'
    ): mixed {
        return self::getInstance()->enqueuePlugin(trim($path, '/'), $cssPos, $jsPos);
    }

    /**
     * Load a TEMPLATE's own asset manifest — templates/<tpl>/assets.php,
     * returning one array — instead of a template hand-writing a dozen
     * I::insert*()/register_frontend_modfiles() calls in its own <head>.
     *
     * $only restricts WHICH asset types get queued. Every entry in the
     * manifest — plain css/js files, bundles, webfonts, the 'jquery' flag,
     * AND the active modules' own frontend CSS/JS modfiles this method
     * also queues (see below) — is exactly one of three types: 'css',
     * 'js', 'fonts'. Pass null (default) to load everything, the way a
     * template's own index.php normally would; pass e.g. ['css', 'fonts']
     * to load only those — the Style Guide Manager preview uses this to
     * skip a template's JS (and jQuery, and modules' own frontend JS)
     * entirely, since a static widget preview never needs to be
     * interactive in the first place.
     *
     *   assets.php (templates/<tpl>/assets.php) — plain `return [...]`, no
     *   side effects, so it stays introspectable/filterable instead of
     *   being an opaque script (contrast with plugin.php, which is code):
     *
     *   return [
     *       // Same array-OR-position-keyed-object shape as plugin.json's
     *       // own 'css'/'js' — a flat list uses the 2nd arg below as
     *       // position, an object picks per group.
     *       'css' => ['head_late' => ['site.css']],
     *       'js'  => ['body_late' => ['site.js']],
     *       // Combined into ONE <link>/<script> via insertCssBundle()/
     *       // insertJsBundle() — most templates want their CSS bundled.
     *       'bundles' => [
     *           ['type' => 'css', 'id' => 'vendor', 'position' => 'head_late', 'files' => ['vendor/a.css', 'vendor/b.css']],
     *       ],
     *       // Passed straight through to insertWebFont($url, $alias, $format).
     *       'fonts' => [
     *           ['url' => 'https://fonts.googleapis.com/css2?family=Spectral:...'],
     *           ['url' => 'https://fonts.googleapis.com/css2?family=Hanken+Grotesk:...', 'alias' => 'MainSans'],
     *       ],
     *       // Opt-in ONLY — register_frontend_modfiles('jquery') is never
     *       // implied by loading CSS/JS at all, unlike modules' own
     *       // frontend modfiles below (those always come along).
     *       'jquery' => true,
     *   ];
     *
     *   // Template's own index.php — replaces its old manual <head> calls:
     *   I::loadTemplateAssets(__DIR__);
     *
     *   // A partial consumer (e.g. the VES Style Guide Manager):
     *   I::loadTemplateAssets(WB_PATH . '/templates/' . $tpl, ['css', 'fonts']);
     *
     * @param string     $dir  Template directory (absolute — e.g. __DIR__
     *                         from the template's own index.php).
     * @param array|null $only Asset types to include ('css'/'js'/'fonts');
     *                         null = all (the template's own normal case).
     */
    public static function loadTemplateAssets(string $dir, ?array $only = null): void
    {
        self::getInstance()->enqueueTemplateAssets(rtrim($dir, '/\\'), $only);
    }

    /**
     * Wipe the entire font cache directory.
     * The next call to insertWebFont() triggers a fresh download.
     */
    public static function clearFontCache(): void
    {
        self::getInstance()->getFontCache()->clearAll();
    }

    // ── Public static API — Inline code ──────────────────────────────────────

    /**
     * Queue inline CSS code.  No <style> wrapper needed.
     *
     *   I::insertCssCode('.hero { display:none }')
     *   I::insertCssCode(':root { --color: #f00 }', 'head_early')
     */
    public static function insertCssCode(
        string $code,
        string $position = 'head_late',
        string $id       = ''
    ): void {
        $code = trim($code);
        if ($code === '') return;
        $inst = self::getInstance();
        $inst->enqueue('inline_css', $code, $inst->normalizePos($position, 'css'), [], $id);
    }

    /**
     * Queue inline JavaScript code.  No <script> wrapper needed.
     * If the code is already wrapped in <script>…</script> tags the wrapper is
     * stripped automatically — buildHtml() adds its own wrapper, and some legacy
     * callers pass pre-wrapped code.
     *
     *   I::insertJsCode('window.WB_URL = "' . WB_URL . '";', 'head_late')
     */
    public static function insertJsCode(
        string $code,
        string $position = 'body_late',
        string $id       = ''
    ): void {
        $code = trim($code);
        if ($code === '') return;
        // Strip <script>…</script> wrapper when present so buildHtml() doesn't
        // double-wrap.  Use a greedy match on .* so a single pair is consumed
        // even when the inner code contains </script> as an escaped string.
        if (preg_match('/^<script[^>]*>(.*)<\/script>$/is', $code, $m)) {
            $code = trim($m[1]);
        }
        if ($code === '') return;
        $inst = self::getInstance();
        $inst->enqueue('inline_js', $code, $inst->normalizePos($position, 'js'), [], $id);
    }

    /**
     * Queue a raw HTML block for injection into the body.
     * Position must be body_early or body_late; head positions are silently
     * corrected to body_early.
     *
     *   I::insertHtmlCode('<div class="cookie-banner">…</div>', 'body_early', 'cookie')
     *   I::insertHtmlCode('<div id="modal"></div>',              'body_late')
     */
    public static function insertHtmlCode(
        string $code,
        string $position = 'body_early',
        string $id       = ''
    ): void {
        $code = trim($code);
        if ($code === '') return;
        $inst = self::getInstance();
        $inst->enqueue('html', $code, $inst->normalizePos($position, 'html'), [], $id);
    }

    // ── Public static API — Meta & Title ─────────────────────────────────────

    /**
     * Queue a <meta> tag for head injection.
     *
     * ── Shorthand: name + content (always replace) ───────────────────────────
     *   I::insertMeta('description', 'My page about chairs')
     *   I::insertMeta('robots',      'noindex, nofollow')
     *   I::insertMeta('author',      'Jane Smith')
     *
     * ── Full tag ─────────────────────────────────────────────────────────────
     *   I::insertMeta('<meta property="og:title" content="…">')
     *   I::insertMeta('<meta property="og:image" content="…">', 'add')
     *   I::insertMeta('<meta name="robots" content="noindex">',  'replace', 'head_early')
     *
     * $action  'replace' — find existing <meta> with same name/property/http-equiv
     *                      and swap in-place; insert at $position if not found (default)
     *          'add'     — always insert at $position even if a matching tag exists
     */
    public static function insertMeta(
        string $nameOrTag,
        string $contentOrAction = 'replace',
        string $position        = 'head_middle'
    ): void {
        $nameOrTag = trim($nameOrTag);
        if ($nameOrTag === '') return;

        if (!str_starts_with($nameOrTag, '<')) {
            // Shorthand form: first arg is meta name, second is content value
            $tag    = '<meta name="'
                    . htmlspecialchars($nameOrTag,       ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
                    . '" content="'
                    . htmlspecialchars($contentOrAction, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
                    . '">';
            $action = 'replace';
        } else {
            $tag    = $nameOrTag;
            $action = $contentOrAction;
        }

        $inst = self::getInstance();
        $inst->enqueue('meta', $tag, $inst->normalizePos($position, 'meta'), ['action' => $action], '');
    }

    /**
     * Queue a page <title> for head injection.
     *
     *   I::insertTitle('My Page — Site Name')
     *
     * $actionOrOverwrite  'replace' / true  — replace existing <title> in-place (default)
     *                     'add'     / false — insert a new <title> at $position
     */
    public static function insertTitle(
        string     $text,
        bool|string $actionOrOverwrite = 'replace',
        string     $position           = 'head_middle'
    ): void {
        $text = trim($text);
        if ($text === '') return;

        // Accept legacy bool $bOverwrite parameter
        $action = is_bool($actionOrOverwrite)
            ? 'replace'           // both true and false → replace (concat semantics gone)
            : $actionOrOverwrite;

        $inst = self::getInstance();
        $inst->enqueue('title', $text, $inst->normalizePos($position, 'title'), ['action' => $action], '');
    }

    // ── WBCE compat — old array-based meta API ────────────────────────────────

    /**
     * Legacy array-based meta insertion.  Kept for module compatibility.
     * The array keys become HTML attributes; 'setname', 'overwrite', 'append',
     * 'setsave', 'position' are silently ignored.
     *
     *   I::insertMetaTag(['name' => 'description', 'content' => 'Hello'])
     *   I::insertMetaTag(['http-equiv' => 'refresh', 'content' => '0;url=…'])
     *   I::insertMetaTag(['charset' => 'UTF-8'])
     */
    public static function insertMetaTag(array $data): void
    {
        if (empty($data)) return;

        // Special case: charset only — <meta charset="…">
        if (isset($data['charset']) && $data['charset'] !== '') {
            $tag = '<meta charset="' . htmlspecialchars($data['charset'], ENT_QUOTES, 'UTF-8') . '">';
            self::insertMeta($tag, 'replace', 'head_middle');
            return;
        }

        $skip = ['setname', 'overwrite', 'append', 'setsave', 'position'];
        $tag  = '<meta';
        foreach ($data as $k => $v) {
            if (in_array($k, $skip, true)) continue;
            $tag .= ' ' . htmlspecialchars((string)$k, ENT_QUOTES, 'UTF-8')
                 .  '="' . htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8') . '"';
        }
        $tag .= '>';

        self::insertMeta($tag, 'replace', 'head_middle');
    }

    // ── WBCE compat — URL tokens ──────────────────────────────────────────────

    /**
     * Register an additional {TOKEN} → URL replacement pair.
     *
     *   I::addUrlToken('{MY_MODULE}', WB_URL . '/modules/my_module')
     *
     * The registered token can then be used in any file URL:
     *   I::insertCssFile('{MY_MODULE}/assets/style.css')
     */
    public static function addUrlToken(string $token, string $url): bool
    {
        if ($token === '' || $url === '') return false;
        self::getInstance()->urlTokens[$token] = rtrim($url, '/');
        return true;
    }

    // ── Queue management ──────────────────────────────────────────────────────

    /**
     * Remove a previously queued entry by type and id.
     * Only entries enqueued with an explicit $id can be removed.
     *
     *   I::remove('js',  'jquery')
     *   I::remove('css', 'fontawesome')
     */
    public static function remove(string $type, string $id): void
    {
        if ($id === '') return; // guard against accidental mass removal
        $inst = self::getInstance();
        foreach ($inst->queue as &$entries) {
            $entries = array_values(
                array_filter($entries, fn($e) => !($e['type'] === $type && $e['id'] === $id))
            );
        }
        unset($entries);
    }

    /**
     * Wipe the entire asset cache directory (bundles, minified files, hash
     * sidecars, and any leftover .tmp.* files from interrupted atomic writes).
     *
     * Uses removePath($dir, true) — empties the directory without deleting the
     * folder itself. The __construct() recreates the directory automatically on
     * the next request if it has been removed externally.
     */
    public static function clearCache(): void
    {
        $inst = self::getInstance();
        if (!is_dir($inst->cacheDir)) return;
        if (function_exists('removePath')) {
            removePath(rtrim($inst->cacheDir, '/\\'), true);
        } else {
            // Fallback when functions.php is not loaded (e.g. unit tests)
            foreach (glob($inst->cacheDir . '*') ?: [] as $file) {
                if (is_file($file)) unlink($file);
            }
        }
    }

    // ── WBCE compat — delete aliases ─────────────────────────────────────────

    /** Remove a queued JS entry by id.  Alias for I::remove('js', $id). */
    public static function delJs(string $id): void
    {
        self::remove('js', $id);
    }

    /** Remove a queued CSS entry by id.  Alias for I::remove('css', $id). */
    public static function delCss(string $id): void
    {
        self::remove('css', $id);
    }

    /** Remove all queued <title> entries. */
    public static function resetTitle(): void
    {
        $inst = self::getInstance();
        foreach ($inst->queue as &$entries) {
            $entries = array_values(array_filter($entries, fn($e) => $e['type'] !== 'title'));
        }
        unset($entries);
    }

    /**
     * Inspect the current queue (simplified legacy compat).
     *
     * @param string $setName   Ignored in new implementation (kept for signature compat).
     * @param string $queueType 'js', 'css', 'meta', 'html', … — filters by type if set.
     * @param string $domPos    Position name, or 'All' for everything.
     * @param mixed  $default   Returned when queue is empty or position not found.
     */
    public static function getQueueArray(
        string $setName   = '',
        string $queueType = '',
        string $domPos    = 'All',
        mixed  $default   = false
    ): mixed {
        $inst = self::getInstance();
        if (empty($inst->queue)) return $default;

        if (strtolower($domPos) === 'all') {
            return $inst->queue;
        }

        $pos = $inst->normalizePos($domPos, $queueType ?: 'js');
        $entries = $inst->queue[$pos] ?? null;

        if ($entries === null) return $default;

        if ($queueType !== '') {
            $entries = array_values(array_filter($entries, fn($e) => $e['type'] === $queueType));
        }

        return $entries ?: $default;
    }

    // ── Main processing entry point ───────────────────────────────────────────

    /**
     * Scan $content for misplaced/attributed assets, then inject all queued
     * assets at the correct DOM anchor positions.
     *
     * Called by the output filter.  Content is passed by reference.
     *
     * On error: always logs to error_log.  When WBCE_DEBUG is on AND the visitor
     * is a logged-in admin, also outputs a console.error block before </body>.
     */
    public static function process(string &$content): bool
    {
        $inst = self::getInstance();
        try {
            // Strip legacy <!--(PH) … --> placeholders unconditionally so they
            // never appear in output regardless of which modules are active.
            $content = preg_replace('/[ \t]*<!--\(PH\)\s[^-]*-->(\r?\n)?/', '', $content) ?? $content;
            $inst->scan($content);
            $inst->inject($content);
            $inst->reset();
            return true;
        } catch (Throwable $e) {
            error_log(sprintf(
                'I (AssetQueue) error: %s in %s on line %d',
                $e->getMessage(), $e->getFile(), $e->getLine()
            ));
            if ($inst->debug && $inst->isAdmin()) {
                $inst->injectDebugScript($content, $e);
            }
            $inst->reset();
            return false;
        }
    }

    /**
     * WBCE compat: OutputFilter calls doFilter($content) and expects a string.
     * Delegates to process() and returns the modified content.
     */
    public static function doFilter(string $content): string
    {
        self::process($content);
        return $content;
    }

    /**
     * WBCE compat: was called to inject <!--(PH)…--> placeholders into the
     * template before assembly.  No longer needed — this pass only strips any
     * legacy placeholder comments that may remain in old templates so they do
     * not appear in the rendered source.
     */
    public static function addPlaceholdersToDom(string $content): string
    {
        // Remove old <!--(PH) … --> markers — anchor injection does not need them.
        return preg_replace('/[ \t]*<!--\(PH\)\s[^-]*-->(\r?\n)?/', '', $content) ?? $content;
    }

    // ── Internal: admin check & debug output ──────────────────────────────────

    private function isAdmin(): bool
    {
        return isset($_SESSION['USER_ID'])
            && is_numeric($_SESSION['USER_ID'])
            && (int)$_SESSION['USER_ID'] > 0;
    }

    private function injectDebugScript(string &$content, Throwable $e): void
    {
        $msg   = addslashes($e->getMessage());
        $file  = addslashes(basename($e->getFile()));
        $line  = $e->getLine();
        $trace = addslashes(str_replace(["\r\n", "\n", "\r"], '\n', $e->getTraceAsString()));

        $script = "\n<script>\n"
                . "/* I/AssetQueue debug — admin only, WBCE_DEBUG=true */\n"
                . "console.error('[I] ' + '$msg\\n'"
                . " + 'File: $file  Line: $line\\n'"
                . " + 'Stack:\\n$trace');\n"
                . "</script>\n";

        $pos = strripos($content, '</body>');
        if ($pos !== false) {
            $content = substr_replace($content, $script, $pos, 0);
        } else {
            $content .= $script;
        }
    }

    // ── Combine / cache ───────────────────────────────────────────────────────

    /**
     * Merge $sources into a single cached file, return its public URL.
     *
     * Cache validity: identifier + all source filenames + their mtimes → md5 hash.
     * A .hash sidecar file holds the signature; mismatch or absence triggers rebuild.
     * Returns null if all sources are missing or the cache write fails.
     */
    /**
     * Split a sources array into [local[], remote[]] based on URL scheme.
     * Remote = http://, https://, // — these cannot be bundled.
     */
    private function splitLocalRemote(array $sources): array
    {
        $local = $remote = [];
        foreach ($sources as $src) {
            $src = trim((string)$src);
            if ($src === '') continue;
            $resolved = strtr($src, $this->urlTokenMap());
            if ($this->isExternal($resolved) && $this->urlToLocalPath($resolved) === null) {
                $remote[] = $src;
            } else {
                $local[] = $src;
            }
        }
        return [$local, $remote];
    }

    /**
     * Derive a human-readable cache filename for a minified individual asset.
     * Pattern: {addon}-{inner/path/as-dashes}.min.{ext}
     *
     *   modules/mymodule/assets/backend_custom.css → mymodule-assets-backend_custom.min.css
     *   templates/wbcetik/css/main.css             → wbcetik-css-main.min.css
     *   templates/wbcetik/owl-carousel/owl.carousel.css → wbcetik-owl-carousel-owl-carousel.min.css
     */
    private function buildMinifiedCacheName(string $absPath): string
    {
        $wbPath = defined('WB_PATH') ? rtrim(WB_PATH, '/\\') : '';
        $ext    = strtolower(pathinfo($absPath, PATHINFO_EXTENSION));

        // Make relative to WB_PATH
        $rel = $absPath;
        if ($wbPath !== '' && str_starts_with($absPath, $wbPath)) {
            $rel = ltrim(substr($absPath, strlen($wbPath)), '/\\');
        }
        $rel   = str_replace('\\', '/', $rel);
        $parts = explode('/', $rel);

        // Identify addon name (second segment under modules/ or templates/)
        if (count($parts) >= 2 && in_array($parts[0], ['modules', 'templates'], true)) {
            $addonName  = $parts[1];
            $innerParts = array_slice($parts, 2);
        } else {
            $addonName  = $parts[0] ?? 'asset';
            $innerParts = array_slice($parts, 1);
        }

        // Build slug: each inner directory + filename-without-extension
        $slugParts = [];
        foreach ($innerParts as $i => $part) {
            $slugParts[] = ($i === count($innerParts) - 1)
                ? pathinfo($part, PATHINFO_FILENAME)  // strip extension from last segment
                : $part;
        }

        $slug = implode('-', array_filter([$addonName, ...$slugParts]));
        $slug = (string)preg_replace('/[^a-zA-Z0-9_\-]/', '-', $slug);
        $slug = (string)preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        $useSuffix = !(defined('MINIFY_USE_SUFFIX') && MINIFY_USE_SUFFIX === false);
        return $useSuffix ? $slug . '.min.' . $ext : $slug . '.' . $ext;
    }

    /**
     * If MINIFY_ASSETS is active and the file is local, minify it and return the
     * URL of the cached minified copy.  Returns the original URL on any failure
     * or when the file is remote / minification is off.
     */
    private function maybeMinifyFile(string $type, string $originalItem): string
    {
        if (!($type === 'css' ? $this->useMinifyCss : $this->useMinifyJs)) return $originalItem;

        $absPath = $this->resolveLocalPath($originalItem);
        if ($absPath === null) return $originalItem;   // remote or not found

        // Skip files that are already minified — re-minifying breaks regex literals in JS
        // and produces no benefit for CSS. Detected by .min.ext or -min.ext in filename.
        $basename = basename($absPath);
        if (preg_match('/[\.\-]min\.(css|js)$/i', $basename)) {
            return $originalItem;
        }

        // Skip files that already live in the cache directory (e.g. combined bundles).
        // Bundles are already minified during buildCombined() and must not be re-processed.
        $absNorm   = str_replace('\\', '/', $absPath);
        $cacheDirNorm = str_replace('\\', '/', rtrim($this->cacheDir, '/\\'));
        if (str_starts_with($absNorm, $cacheDirNorm . '/')) {
            return $originalItem;
        }


        $cacheName = $this->buildMinifiedCacheName($absPath);
        $cachePath = $this->cacheDir . $cacheName;

        // Cache hit: minified file exists and is at least as new as source
        if (is_file($cachePath) && filemtime($cachePath) >= filemtime($absPath)) {
            return $this->cacheFileUrl($cachePath);
        }

        // Prefer Mullie (file-path API) — fall back to naive minifier on any error
        $ok = false;
        try {
            if ($type === 'css' && class_exists(\MatthiasMullie\Minify\CSS::class, false)) {
                (new \MatthiasMullie\Minify\CSS($absPath))->minify($cachePath);
                $ok = is_file($cachePath) && filesize($cachePath) > 0;
            } elseif ($type === 'js' && class_exists(\MatthiasMullie\Minify\JS::class, false)) {
                (new \MatthiasMullie\Minify\JS($absPath))->minify($cachePath);
                $ok = is_file($cachePath) && filesize($cachePath) > 0;
            }
        } catch (\Throwable $e) {
            if ($this->debug) error_log("I (minify/mullie): " . $e->getMessage());
            $ok = false;
        }

        if (!$ok) {
            $content = file_get_contents($absPath);
            if ($content === false) return $originalItem;
            $minifier = $type === 'css' ? $this->cssMinifier : $this->jsMinifier;
            $minified = (string)call_user_func($minifier, $content);
            // Atomic write via temp file + rename — same rationale as buildCombined().
            $tmpPath = $cachePath . '.tmp.' . getmypid();
            if (file_put_contents($tmpPath, $minified, LOCK_EX) === false) {
                @unlink($tmpPath);
                if ($this->debug) error_log("I (minify): cache write failed: $cachePath");
                return $originalItem;
            }
            if (!rename($tmpPath, $cachePath)) {
                @unlink($tmpPath);
                if ($this->debug) error_log("I (minify): cache rename failed: $cachePath");
                return $originalItem;
            }
        }

        return is_file($cachePath) ? $this->cacheFileUrl($cachePath) : $originalItem;
    }

    /**
     * Rewrite relative url() references in a CSS string to absolute URLs.
     *
     * When a CSS file is bundled into cache/assets/, relative paths like
     * url('../fonts/icon.woff') would break because the base directory changes.
     * This method resolves each relative reference against the source file's
     * directory and emits an absolute URL rooted at WB_URL.
     *
     * Skipped automatically: data URIs, absolute URLs (http/https), protocol-
     * relative URLs (//), and root-relative URLs (/).
     *
     * @param string $css         Raw CSS content of the source file.
     * @param string $sourceAbsPath  Absolute filesystem path of the source file.
     */
    /**
     * Rewrite relative url() references in a CSS string to absolute URLs.
     *
     * When a CSS file is bundled into cache/assets/, relative paths like
     * url('../fonts/icon.woff') break because the base directory changes.
     * Uses pure string normalisation — no realpath(), no file_exists() — so
     * URLs are rewritten even when the referenced file does not exist locally
     * (e.g. a font served only in production).
     *
     * Skipped: data URIs, absolute URLs (http/https), protocol-relative (//),
     * root-relative (/), and empty references.
     */
    private function rewriteCssUrls(string $css, string $sourceAbsPath): string
    {
        // Normalise separators once; consistent on both Windows and Linux
        $sourceDir = str_replace('\\', '/', dirname($sourceAbsPath));
        $wbPath    = str_replace('\\', '/', rtrim(defined('WB_PATH') ? WB_PATH : '', '/\\'));
        $wbUrl     = rtrim(defined('WB_URL') ? WB_URL : '', '/');

        return (string)preg_replace_callback(
            '/url\(\s*([\'"]?)((?!data:)(?!https?:\/\/)(?!\/\/)(?!\/).*?)\1\s*\)/i',
            function (array $m) use ($sourceDir, $wbPath, $wbUrl): string {
                $quote = $m[1];
                $ref   = trim($m[2]);

                if ($ref === '') return $m[0];

                // Regex backtracking can leave the opening quote in $ref instead of $quote
                // when the lookahead inside group 2 rejected the first attempt (e.g. data: URIs
                // matched as unquoted). Bail out for any data: URI regardless of quoting.
                if (preg_match('/^[\'"]?data:/i', $ref)) return $m[0];

                // Split query string / fragment from path component
                $queryFrag = '';
                if (preg_match('/^([^?#]*)([?#].*)$/s', $ref, $pf)) {
                    $pathOnly  = $pf[1];
                    $queryFrag = $pf[2];
                } else {
                    $pathOnly = $ref;
                }

                if ($pathOnly === '') return $m[0];

                // Stack-based .. resolution — no filesystem access required
                $combined = $sourceDir . '/' . str_replace('\\', '/', $pathOnly);
                $parts    = explode('/', $combined);
                $stack    = [];
                foreach ($parts as $part) {
                    if ($part === '..') {
                        array_pop($stack);
                    } elseif ($part !== '.' && $part !== '') {
                        $stack[] = $part;
                    }
                }

                // Restore leading slash (Linux); Windows drive letter stays as first element
                $absPath = (str_starts_with($combined, '/') ? '/' : '') . implode('/', $stack);

                if ($wbPath === '' || !str_starts_with($absPath, $wbPath)) {
                    return $m[0]; // outside WB_PATH — leave unchanged
                }

                $relUrl = ltrim(substr($absPath, strlen($wbPath)), '/');
                return 'url(' . $quote . $wbUrl . '/' . $relUrl . $queryFrag . $quote . ')';
            },
            $css
        ) ?? $css;
    }

    private function buildCombined(string $type, array $sources, string $identifier): ?string
    {
        $useMinify = $type === 'css' ? $this->useMinifyCss : $this->useMinifyJs;
        $sig = $identifier . '|minify=' . ($useMinify ? '1' : '0');
        foreach ($sources as $file) {
            $sig .= '|' . $file;
            $path = $this->resolveLocalPath($file);
            $sig .= '|' . ($path ? filemtime($path) : 0);
        }
        $hash = md5($sig);
        $ext  = $type === 'css' ? 'css' : 'js';

        $cacheFile = $this->cacheDir . 'combined_' . $identifier . ($useMinify ? '.min' : '') . '.' . $ext;
        $hashFile  = $cacheFile . '.hash';

        // Cache hit: both files exist and sidecar matches current signature
        if (is_file($cacheFile) && is_file($hashFile)
            && trim((string)file_get_contents($hashFile)) === $hash
        ) {
            return $this->cacheFileUrl($cacheFile);
        }

        // Build — prefer Mullie (file-path API handles CSS url() rewriting automatically)
        // when minification is enabled. Always fall back to manual concatenation on error.
        $mullieOk = false;
        if ($useMinify) {
            try {
                if ($type === 'css' && class_exists(\MatthiasMullie\Minify\CSS::class, false)) {
                    $minifier  = new \MatthiasMullie\Minify\CSS();
                    $hasSource = false;
                    foreach ($sources as $file) {
                        $path = $this->resolveLocalPath($file);
                        if ($path) { $minifier->add($path); $hasSource = true; }
                        elseif ($this->debug) error_log("I (combine): source not found: $file");
                    }
                    if ($hasSource) {
                        $minifier->minify($cacheFile); // writes file + rewrites url() paths
                        $mullieOk = is_file($cacheFile) && filesize($cacheFile) > 0;
                    }
                } elseif ($type === 'js' && class_exists(\MatthiasMullie\Minify\JS::class, false)) {
                    $minifier  = new \MatthiasMullie\Minify\JS();
                    $hasSource = false;
                    foreach ($sources as $file) {
                        $path = $this->resolveLocalPath($file);
                        if ($path) { $minifier->add($path); $hasSource = true; }
                        elseif ($this->debug) error_log("I (combine): source not found: $file");
                    }
                    if ($hasSource) {
                        $minifier->minify($cacheFile);
                        $mullieOk = is_file($cacheFile) && filesize($cacheFile) > 0;
                    }
                }
            } catch (\Throwable $e) {
                if ($this->debug) error_log("I (combine/mullie): " . $e->getMessage());
                $mullieOk = false;
            }
        }

        if (!$mullieOk) {
            // Fallback: manual concatenation + optional naive minification.
            // For CSS: rewriteCssUrls() ensures url() paths stay valid in the bundle.
            $combined = '';
            foreach ($sources as $file) {
                $path = $this->resolveLocalPath($file);
                if ($path) {
                    $content = (string)file_get_contents($path);
                    if ($type === 'css') {
                        $content = $this->rewriteCssUrls($content, $path);
                    }
                    $trimmed = trim($content);
                    if ($trimmed !== '') {
                        $combined .= ($type === 'js' ? rtrim($trimmed, ';') . ";\n" : $trimmed . "\n");
                    }
                } elseif ($this->debug) {
                    error_log("I (combine): source not found: $file");
                }
            }
            if ($combined === '') return null;

            $output = $useMinify
                ? call_user_func($type === 'css' ? $this->cssMinifier : $this->jsMinifier, $combined)
                : $combined;

            // Atomic write: write to a temp file then rename to the final path.
            // rename() is atomic on POSIX (same filesystem); on Windows it is
            // near-atomic. Prevents concurrent readers from seeing a partial file.
            $tmpFile = $cacheFile . '.tmp.' . getmypid();
            if (file_put_contents($tmpFile, $output, LOCK_EX) === false) {
                @unlink($tmpFile);
                if ($this->debug) error_log("I (combine): cache write failed: $cacheFile");
                return null;
            }
            if (!rename($tmpFile, $cacheFile)) {
                @unlink($tmpFile);
                if ($this->debug) error_log("I (combine): cache rename failed: $cacheFile");
                return null;
            }
        }

        if (!is_file($cacheFile)) return null;

        // Write hash sidecar only after successful cache write.
        // LOCK_EX ensures the hash is written atomically so concurrent readers
        // never see a partial / empty hash that triggers a spurious cache rebuild.
        file_put_contents($hashFile, $hash, LOCK_EX);

        return $this->cacheFileUrl($cacheFile);
    }

    private function cacheFileUrl(string $cacheFile): string
    {
        $wbUrl  = defined('WB_URL')  ? rtrim(WB_URL,  '/') : '';
        $wbPath = defined('WB_PATH') ? rtrim(WB_PATH, '/') : '';

        // Normalise to forward slashes before URL construction (critical on Windows)
        $cacheNorm = str_replace('\\', '/', $cacheFile);
        $pathNorm  = str_replace('\\', '/', $wbPath);

        // Return a clean URL — cache busting is applied uniformly by resolveUrl()
        // so it is never added twice when the cache URL passes through resolveUrl().
        return $pathNorm !== '' ? str_replace($pathNorm, $wbUrl, $cacheNorm) : $cacheNorm;
    }

    // ── Web Fonts — delegates to FontCache ───────────────────────────────────

    private function enqueueWebFont(string $url, string $alias, string $format): void
    {
        $url = trim($url);
        if ($url === '') return;

        // Accept complete <link> tags — extract the href value
        if (stripos($url, 'href=') !== false) {
            if (preg_match('/\bhref=["\']([^"\']+)["\']/i', $url, $m)) {
                $url = $m[1];
            }
        }

        $cache = $this->getFontCache();

        // Admin + CTRL+F5 → force re-download on next request
        if ($this->isAdmin()
            && isset($_SERVER['HTTP_CACHE_CONTROL'])
            && strtolower(trim((string)$_SERVER['HTTP_CACHE_CONTROL'])) === 'no-cache'
        ) {
            $cache->forceRefresh($url, $format, $alias);
        }

        $result = $cache->resolve($url, $format, $alias);
        $id     = 'wfont-' . md5($url . '|' . $format);

        // Always enqueue the cached CSS file as a <link>.
        // Without alias: hash-named file, original font-family names.
        // With alias:    alias-named file (e.g. poppins.css), font-family already
        //                substituted in the file itself — no separate <style> block needed.
        $this->enqueue('css', $result['cssUrl'], 'head_early', [], $id);
    }

    private function enqueueFont(string|array $sources, array $options): void
    {
        $family  = (string)($options['family']  ?? '');
        $weight  = (string)($options['weight']  ?? '400');
        $style   = (string)($options['style']   ?? 'normal');
        $display = (string)($options['display'] ?? 'swap');
        $preload = (bool)  ($options['preload'] ?? false);

        // Normalize to [url => format] map
        if (is_string($sources)) {
            $ext    = strtolower(pathinfo($sources, PATHINFO_EXTENSION));
            $fmtMap = ['woff2' => 'woff2', 'woff' => 'woff', 'ttf' => 'truetype', 'otf' => 'opentype'];
            $sources = [$sources => $fmtMap[$ext] ?? 'woff2'];
        }

        // Resolve tokens ({TEMPLATE}, {MODULES}, …) and build final [url => format] map
        $resolved = [];
        foreach ($sources as $src => $fmt) {
            $url = $this->resolveUrl((string)$src);
            if ($url !== null) {
                $resolved[$url] = (string)$fmt;
            }
        }

        if ($resolved === []) return;

        // Preload hint for the first (preferred) source
        if ($preload) {
            $firstUrl = array_key_first($resolved);
            $firstFmt = reset($resolved);
            $hint = '<link rel="preload" href="' . htmlspecialchars($firstUrl, ENT_QUOTES) . '"'
                  . ' as="font" type="font/' . $firstFmt . '" crossorigin>';
            $this->enqueue('html', $hint, 'head_early', [], 'preload-' . md5($firstUrl));
        }

        // No family = preload only, no @font-face needed
        if ($family === '') return;

        $srcParts = [];
        foreach ($resolved as $url => $fmt) {
            $srcParts[] = 'url(' . $url . ') format(\'' . $fmt . '\')';
        }

        $escaped = str_replace("'", "\\'", $family);
        $css = "@font-face {\n"
             . "  font-family: '" . $escaped . "';\n"
             . "  font-weight: " . $weight . ";\n"
             . "  font-style: " . $style . ";\n"
             . "  font-display: " . $display . ";\n"
             . "  src: " . implode(",\n       ", $srcParts) . ";\n"
             . "}";

        $this->enqueue('inline_css', $css, 'head_early', [], 'font-' . md5($family . '|' . $weight . '|' . $style));
    }

    private function enqueuePlugin(string $path, string $cssPos, string $jsPos): mixed
    {
        if (isset($this->loadedPlugins[$path])) return $this->loadedPlugins[$path];

        $dir = rtrim(defined('WB_PATH') ? WB_PATH : '', '/\\')
             . DIRECTORY_SEPARATOR
             . str_replace('/', DIRECTORY_SEPARATOR, $path);

        // plugin.php takes precedence over plugin.json — handles its own asset loading
        $phpFile = $dir . DIRECTORY_SEPARATOR . 'plugin.php';
        if (is_file($phpFile)) {
            $result = $this->includePlugin($phpFile);
            $this->loadedPlugins[$path] = $result;
            return $result;
        }

        $manifest = $dir . DIRECTORY_SEPARATOR . 'plugin.json';
        if (!is_file($manifest)) {
            if ($this->debug) error_log("I::loadPlugin: no plugin.php or plugin.json in $path");
            $this->loadedPlugins[$path] = null;
            return null;
        }

        $raw = file_get_contents($manifest);
        if ($raw === false) { $this->loadedPlugins[$path] = null; return null; }

        $config = json_decode($raw, true);
        if (!is_array($config)) {
            if ($this->debug) error_log("I::loadPlugin: invalid plugin.json in $path");
            $this->loadedPlugins[$path] = null;
            return null;
        }

        $this->loadedPlugins[$path] = null;

        $baseUrl = rtrim(defined('WB_URL') ? WB_URL : '', '/') . '/' . $path;

        // Dependencies first — depth-first, deduplication via $loadedPlugins
        foreach ((array)($config['require'] ?? []) as $dep) {
            $this->enqueuePlugin(trim((string)$dep, '/'), $cssPos, $jsPos);
        }

        // Queue CSS and JS — each accepts two forms:
        //   Array form:  ["file.css"]               → all files at the default position
        //   Object form: {"head_late": ["file.css"]} → explicit position per group
        $this->enqueuePluginAssets('css', $config['css'] ?? [], $baseUrl, $cssPos);
        $this->enqueuePluginAssets('js',  $config['js']  ?? [], $baseUrl, $jsPos);

        return null;
    }

    /**
     * Implementation behind loadTemplateAssets() — see that method's own
     * doc comment for the assets.php format and the $only filter contract.
     */
    private function enqueueTemplateAssets(string $dir, ?array $only): void
    {
        $wantTypes = $only !== null ? array_flip($only) : null;
        $want = static fn (string $type): bool => $wantTypes === null || isset($wantTypes[$type]);

        $manifestFile = $dir . DIRECTORY_SEPARATOR . 'assets.php';
        $config = is_file($manifestFile) ? $this->includePlugin($manifestFile) : null;
        if (!is_array($config)) {
            $config = [];
        }

        // Same "derive the URL root from the given path" approach
        // enqueuePlugin() uses for plugins — no {TEMPLATE}-token dependency,
        // since $dir isn't necessarily the ACTIVE template (the Style Guide
        // Manager passes an arbitrary $tpl's directory, which may differ
        // from the TEMPLATE constant of the request it's running under).
        $wbPath  = rtrim(defined('WB_PATH') ? WB_PATH : '', '/\\');
        $wbUrl   = rtrim(defined('WB_URL')  ? WB_URL  : '', '/');
        $relDir  = str_starts_with($dir, $wbPath) ? substr($dir, strlen($wbPath)) : '';
        $baseUrl = $wbUrl . '/' . ltrim(str_replace('\\', '/', $relDir), '/');

        // Plain css/js files — reuses the EXACT same helper (and therefore
        // the exact same array-or-position-object manifest shape) plugin.json
        // already uses for its own 'css'/'js' keys.
        if ($want('css')) {
            $this->enqueuePluginAssets('css', $config['css'] ?? [], $baseUrl, 'head_late');
        }
        if ($want('js')) {
            $this->enqueuePluginAssets('js', $config['js'] ?? [], $baseUrl, 'body_late');
        }

        // Bundles — each declares its OWN type, filtered the same way as
        // everything else; a CSS bundle is not excluded just because 'js'
        // is absent from $only, and vice versa.
        foreach ((array) ($config['bundles'] ?? []) as $bundle) {
            if (!is_array($bundle)) {
                continue;
            }
            $type = strtolower((string) ($bundle['type'] ?? 'css'));
            if ($type !== 'css' && $type !== 'js') {
                continue;
            }
            if (!$want($type)) {
                continue;
            }
            $files = [];
            foreach ((array) ($bundle['files'] ?? []) as $file) {
                $file = trim((string) $file);
                if ($file !== '') {
                    $files[] = $baseUrl . '/' . ltrim($file, '/');
                }
            }
            if ($files === []) {
                continue;
            }
            $id  = (string) ($bundle['id'] ?? md5($baseUrl . '|' . implode(',', $files)));
            $pos = (string) ($bundle['position'] ?? ($type === 'js' ? 'body_late' : 'head_late'));
            if ($type === 'js') {
                self::insertJsBundle($files, $id, $pos);
            } else {
                self::insertCssBundle($files, $id, $pos);
            }
        }

        // Webfonts — passed straight through to insertWebFont(), including
        // the optional alias/format (same rename mechanism insertWebFont()
        // itself already offers; see that method's own doc comment).
        if ($want('fonts')) {
            foreach ((array) ($config['fonts'] ?? []) as $font) {
                if (!is_array($font) || empty($font['url'])) {
                    continue;
                }
                self::insertWebFont(
                    (string) $font['url'],
                    (string) ($font['alias']  ?? ''),
                    (string) ($font['format'] ?? 'woff2')
                );
            }
        }

        // Active modules' own frontend CSS/JS — what every template's
        // <head> already calls via register_frontend_modfiles('css'/'js')
        // today, folded in here so a template needs one call instead of
        // three. Same $only gate. jQuery core is the one thing that is
        // NEVER implied just by loading JS — a template opts in explicitly
        // via the manifest's 'jquery' flag, and even then only fires when
        // 'js' itself is actually being loaded (a caller like the Style
        // Guide Manager, which asks for ['css','fonts'] only, must never
        // get jQuery even if the template's own manifest requests it).
        $wb = $GLOBALS['wb'] ?? null;
        if (is_object($wb) && method_exists($wb, 'registerModfiles')) {
            if ($want('css')) {
                $wb->registerModfiles('css', 'frontend');
            }
            if ($want('js')) {
                $wb->registerModfiles('js', 'frontend');
                if (!empty($config['jquery'])) {
                    $wb->registerModfiles('jquery', 'frontend');
                }
            }
        }
    }

    /**
     * Include a plugin.php in an isolated scope (no local variables leak in).
     * Only constants and static classes are accessible inside plugin.php.
     * Returns whatever the file returns (typically an array or null).
     */
    private function includePlugin(string $absPath): mixed
    {
        return (static function (string $__path): mixed {
            return include $__path;
        })($absPath);
    }

    /**
     * Queue one asset type (css or js) from a plugin manifest entry.
     * Handles both the simple array form and the position-keyed object form.
     */
    private function enqueuePluginAssets(string $type, mixed $entry, string $baseUrl, string $defaultPos): void
    {
        if (!is_array($entry) || $entry === []) return;

        if (array_is_list($entry)) {
            // Simple form: ["file.css", "other.css"]
            foreach ($entry as $file) {
                $this->enqueuePluginFile($type, (string)$file, $baseUrl, $defaultPos);
            }
        } else {
            // Object form: {"head_late": ["file.css"], "body_late": ["other.js"]}
            foreach ($entry as $pos => $files) {
                foreach ((array)$files as $file) {
                    $this->enqueuePluginFile($type, (string)$file, $baseUrl, (string)$pos);
                }
            }
        }
    }

    private function enqueuePluginFile(string $type, string $file, string $baseUrl, string $pos): void
    {
        $url      = $baseUrl . '/' . ltrim($file, '/');
        $resolved = $this->resolveUrl($url);
        if ($resolved !== null) {
            $this->enqueue($type, $resolved, $this->normalizePos($pos, $type), [], '');
        }
    }

    /** Lazy getter — FontCache is only instantiated when fonts are actually used. */
    private function getFontCache(): FontCache
    {
        return $this->fontCache ??= new FontCache(
            cacheDir: $this->fontCacheDir,
            wbPath:   defined('WB_PATH') ? WB_PATH : '',
            wbUrl:    defined('WB_URL')  ? WB_URL  : '',
            debug:    $this->debug,
        );
    }

    // ── Scanner ───────────────────────────────────────────────────────────────

    /**
     * Scan the <body> portion of $content for assets that belong in the head
     * (or a specific body anchor) and queue them for injection.
     *
     * Only <script src="…"> tags with an explicit asset-pos attribute are moved;
     * all other inline <script> tags are left exactly where they are.
     */
    private function scan(string &$content): void
    {
        // Pre-pass: resolve legacy <!--(MOVE) POSITION -->…<!--(END)--> blocks.
        // Must run before head/body split because MOVE blocks may appear anywhere.
        $this->processMoveBlocks($content);

        // Pre-pass: resolve <asset-group pos="…">…</asset-group> blocks.
        // Runs on full content so groups in template partials outside <body> are caught.
        $this->processAssetGroups($content);

        // Split at end of opening <body> tag so we only scan the body.
        // Require </head> as well: if it's absent we cannot inject relocated CSS
        // into the head, and removing the <link> tags without reinserting them
        // would silently discard the stylesheets.  When the page has no proper
        // head/body structure (partial HTML, AJAX snippet, admin tool fragment)
        // we leave all tags exactly where they are.
        $bodyOpen = stripos($content, '<body');
        if ($bodyOpen === false) return;

        $bodyTagClose = strpos($content, '>', $bodyOpen);
        if ($bodyTagClose === false) return;

        if (stripos($content, '</head>') === false) return;

        $head = substr($content, 0, $bodyTagClose + 1);
        $body = substr($content, $bodyTagClose + 1);

        $removals = []; // [[offset, length], …] — collected in body-relative coords

        // ── <link rel="stylesheet"> ───────────────────────────────────────────
        preg_match_all(
            '/<link\b[^>]*\brel=["\']stylesheet["\'][^>]*>/i',
            $body, $m, PREG_OFFSET_CAPTURE
        );
        foreach ($m[0] as [$tag, $off]) {
            if (!preg_match('/\bhref=["\']([^"\']+)["\']/i', $tag, $hm)) continue;
            $pos   = $this->assetPosAttr($tag, 'css') ?? 'head_late';
            $attrs = self::parseAttrs($tag, skip: ['rel', 'href', 'asset-pos']);
            $this->enqueue('css', $hm[1], $pos, $attrs, '');
            $removals[] = [$off, strlen($tag)];
        }

        // ── <style>…</style> ─────────────────────────────────────────────────
        preg_match_all('/<style\b[^>]*>.*?<\/style>/is', $body, $m, PREG_OFFSET_CAPTURE);
        foreach ($m[0] as [$tag, $off]) {
            if (!preg_match('/<style[^>]*>(.*?)<\/style>/is', $tag, $cm)) continue;
            $code = trim($cm[1]);
            if ($code === '') continue;
            $pos = $this->assetPosAttr($tag, 'css') ?? 'head_late';
            $this->enqueue(
                'inline_css',
                $this->useMinifyCss ? call_user_func($this->cssMinifier, $code) : $code,
                $pos, [], ''
            );
            $removals[] = [$off, strlen($tag)];
        }

        // ── <script> tags — one pass handles both src= (external) and inline.
        // Only tags with an explicit asset-pos="…" attribute are relocated;
        // plain <script> tags without asset-pos are left exactly in place.
        //
        // Matching the full <script>…</script> block prevents a stranded </script>
        // closing tag being left in the document when only the opening tag was
        // removed (the old two-pass approach had this bug for src= scripts).
        //
        // Known limitation: the lazy (.*?) stops at the FIRST </script> token.
        // If inline JS contains the literal string </script> it must be escaped
        // as <\/script> — this matches browser / HTML5 tokeniser behaviour and
        // is the established convention for embedding HTML strings in JS.
        preg_match_all('/<script\b([^>]*)>(.*?)<\/script>/is', $body, $m, PREG_OFFSET_CAPTURE);
        foreach ($m[0] as $idx => [$fullTag, $off]) {
            $pos = $this->assetPosAttr($fullTag, 'js');
            if ($pos === null) continue; // no asset-pos → leave in place

            $attrsStr = $m[1][$idx][0];
            if (preg_match('/\bsrc=["\']([^"\']+)["\']/i', $attrsStr, $sm)) {
                // External script file
                $attrs = self::parseAttrs($fullTag, skip: ['src', 'asset-pos']);
                $this->enqueue('js', $sm[1], $pos, $attrs, '');
            } else {
                // Inline script code
                $code = trim($m[2][$idx][0]);
                if ($code === '') continue;
                $this->enqueue(
                    'inline_js',
                    $this->useMinifyJs ? call_user_func($this->jsMinifier, $code) : $code,
                    $pos, [], ''
                );
            }
            $removals[] = [$off, strlen($fullTag)];
        }

        // ── <meta> tags ───────────────────────────────────────────────────────
        preg_match_all('/<meta\b[^>]*>/i', $body, $m, PREG_OFFSET_CAPTURE);
        foreach ($m[0] as [$tag, $off]) {
            $action   = preg_match('/\bmeta-add\b/i', $tag) ? 'add' : 'replace';
            $cleanTag = trim((string)preg_replace('/\s+meta-(replace|add)\b/i', '', $tag));
            $pos      = $this->assetPosAttr($tag, 'meta') ?? 'head_middle';
            $this->enqueue('meta', $cleanTag, $pos, ['action' => $action], '');
            $removals[] = [$off, strlen($tag)];
        }

        // ── <title>…</title> ─────────────────────────────────────────────────
        preg_match_all('/<title\b[^>]*>.*?<\/title>/is', $body, $m, PREG_OFFSET_CAPTURE);
        foreach ($m[0] as [$tag, $off]) {
            if (!preg_match('/<title[^>]*>(.*?)<\/title>/is', $tag, $cm)) continue;
            $text = trim($cm[1]);
            if ($text === '') continue;
            $action = preg_match('/\bmeta-add\b/i', $tag) ? 'add' : 'replace';
            $pos    = $this->assetPosAttr($tag, 'title') ?? 'head_middle';
            $this->enqueue('title', $text, $pos, ['action' => $action], '');
            $removals[] = [$off, strlen($tag)];
        }

        if (empty($removals)) {
            $content = $head . $body;
            return;
        }

        // Remove in reverse offset order so earlier offsets stay valid
        usort($removals, fn($a, $b) => $b[0] <=> $a[0]);
        foreach ($removals as [$off, $len]) {
            $body = substr_replace($body, '', $off, $len);
        }

        $content = $head . $body;
    }

    // ── asset-group ──────────────────────────────────────────────────────────

    /**
     * Find all <asset-group pos="…">…</asset-group> blocks in the full page,
     * distribute each child element to the correct queue position, and remove
     * the block from its original location.
     *
     * Individual child tags may carry their own asset-pos attribute to override
     * the group position on a per-tag basis.
     *
     *   <asset-group pos="body_late">
     *     <link rel="stylesheet" href="widget.css" asset-pos="head_late">
     *     <script src="widget-init.js"></script>
     *     <script>initWidget();</script>
     *   </asset-group>
     */
    private function processAssetGroups(string &$content): void
    {
        if (stripos($content, '<asset-group') === false) return;

        // preg_replace_callback removes each <asset-group> block from $content
        // before scan() runs. Assets are enqueued via queueGroupContent(); the
        // body scanner never sees them again — no double-queueing risk.
        //
        // The dedup system ($seen / $seenPaths) provides a safety net in case
        // the same asset is referenced both inside an asset-group and directly
        // elsewhere in the page.
        //
        // Known limitation: nested <asset-group> elements are not supported.
        // The lazy (.*?) anchors at the first </asset-group> it finds, which
        // is the inner closing tag — the outer block is then left partially
        // matched. In practice, nesting asset-groups is never needed.
        $content = (string)preg_replace_callback(
            '/<asset-group\b([^>]*)>(.*?)<\/asset-group>/is',
            function (array $m): string {
                $attrsStr = $m[1];
                $inner    = $m[2];

                // pos="…" is required; default head_late if omitted
                $groupPos = 'head_late';
                if (preg_match('/\bpos=["\']([^"\']+)["\']/i', $attrsStr, $pm)) {
                    $groupPos = trim($pm[1]);
                }

                $this->queueGroupContent($inner, $groupPos);
                return ''; // remove block from document
            },
            $content
        ) ?? $content;
    }

    /**
     * Process the inner content of an <asset-group> block.
     * Each recognised element is enqueued at the group position unless the tag
     * carries its own asset-pos attribute (which takes precedence).
     * Non-asset HTML leftovers are queued as a raw html block.
     */
    private function queueGroupContent(string $inner, string $groupPos): void
    {
        $inner = trim($inner);
        if ($inner === '') return;

        $matched = []; // collect matched tag strings to identify leftover HTML

        // ── <link rel="stylesheet"> ───────────────────────────────────────────
        preg_match_all('/<link\b[^>]*\brel=["\']stylesheet["\'][^>]*>/i', $inner, $m);
        foreach ($m[0] as $tag) {
            if (!preg_match('/\bhref=["\']([^"\']+)["\']/i', $tag, $hm)) continue;
            $pos   = $this->assetPosAttr($tag, 'css') ?? $this->normalizePos($groupPos, 'css');
            $attrs = self::parseAttrs($tag, skip: ['rel', 'href', 'asset-pos']);
            $this->enqueue('css', $hm[1], $pos, $attrs, '');
            $matched[] = $tag;
        }

        // ── <style>…</style> ─────────────────────────────────────────────────
        preg_match_all('/<style\b[^>]*>(.*?)<\/style>/is', $inner, $m);
        foreach ($m[0] as $idx => $fullTag) {
            $code = trim($m[1][$idx]);
            if ($code === '') continue;
            $pos = $this->assetPosAttr($fullTag, 'inline_css') ?? $this->normalizePos($groupPos, 'inline_css');
            $this->enqueue('inline_css',
                $this->useMinifyCss ? call_user_func($this->cssMinifier, $code) : $code,
                $pos, [], ''
            );
            $matched[] = $fullTag;
        }

        // ── <script> tags — one pass for both external src= and inline code ──
        preg_match_all('/<script\b([^>]*)>(.*?)<\/script>/is', $inner, $m);
        foreach ($m[0] as $idx => $fullTag) {
            $attrsStr = $m[1][$idx];
            if (preg_match('/\bsrc=["\']([^"\']+)["\']/i', $attrsStr, $sm)) {
                // External script file
                $pos   = $this->assetPosAttr($fullTag, 'js') ?? $this->normalizePos($groupPos, 'js');
                $attrs = self::parseAttrs($fullTag, skip: ['src', 'asset-pos']);
                $this->enqueue('js', $sm[1], $pos, $attrs, '');
            } else {
                // Inline script code
                $code = trim($m[2][$idx]);
                if ($code === '') continue;
                $pos = $this->assetPosAttr($fullTag, 'inline_js') ?? $this->normalizePos($groupPos, 'inline_js');
                $this->enqueue('inline_js',
                    $this->useMinifyJs ? call_user_func($this->jsMinifier, $code) : $code,
                    $pos, [], ''
                );
            }
            $matched[] = $fullTag;
        }

        // ── Remaining HTML (non-asset elements, e.g. <div>, <noscript>) ───────
        $remaining = $inner;
        foreach ($matched as $tag) {
            // Use str_replace — safe because each tag string is unique in context
            $remaining = str_replace($tag, '', $remaining);
        }
        $remaining = trim((string)preg_replace('/^\s*[\r\n]+/m', '', $remaining));
        if ($remaining !== '') {
            $pos = $this->normalizePos($groupPos, 'html');
            $this->enqueue('html', $remaining, $pos, [], '');
        }
    }

    // ── Legacy MOVE syntax ────────────────────────────────────────────────────

    /**
     * Pre-pass: find all <!--(MOVE) POSITION -->…<!--(END)--> blocks in the
     * full page content, queue their inner assets at the mapped position, and
     * remove the block from its original location.
     *
     * This provides backward compatibility for modules that use the old
     * mod_opf_move_stuff syntax instead of asset-pos="…" attributes.
     *
     * Syntax:
     *   <!--(MOVE) JS BODY BTM- -->
     *   <script>…</script>
     *   <!--(END)-->
     *
     *   <!--(MOVE) CSS HEAD BTM- -->
     *   <link rel="stylesheet" href="…">
     *   <!--(END)-->
     *
     * The POSITION token uses the same legacy names as Insert/I and is mapped
     * to canonical positions via normalizePos().
     */
    private function processMoveBlocks(string &$content): void
    {
        // Skip entirely if no MOVE blocks present (fast path)
        if (stripos($content, '<!--(MOVE)') === false) return;

        // Match:  <!--(MOVE) POSITION -->  inner content  <!--(END)-->
        // Without the U (PCRE_UNGREEDY) modifier the explicit ?-suffixed quantifiers
        // (.+?) and (.*?) are correctly lazy; with U they would become greedy and
        // one malformed / unclosed block could swallow all subsequent blocks.
        $regex = '/[ \t]*<!--\(MOVE\)\s+(.+?)\s*-->(.*?)<!--\(END\)-->[ \t]*(\r?\n)?/s';

        $content = preg_replace_callback(
            $regex,
            function (array $m): string {
                $rawPos = trim($m[1]); // e.g. "JS BODY BTM-"
                $inner  = $m[2];      // the HTML to relocate

                // Determine type hint from the position token for normalizePos().
                // Use stripos so "CSS HEAD MODFILES", "JS BODY BTM-" etc. are
                // all correctly classified — str_starts_with would miss "CSS" anywhere
                // after the first character (e.g. "HEAD CSS MODFILES").
                $upperPos = strtoupper($rawPos);
                $type = stripos($upperPos, 'CSS') !== false ? 'css' : 'js';
                $pos  = $this->normalizePos($rawPos, $type);

                $this->queueMoveContent($inner, $pos);

                return ''; // remove the MOVE block from its original location
            },
            $content
        ) ?? $content;
    }

    /**
     * Parse the inner content of a MOVE block and enqueue each recognised asset.
     *
     * Handles: <link rel="stylesheet">, <style>, <script src>, inline <script>.
     * Unknown/arbitrary HTML is queued as a raw html entry at the target position
     * so nothing is silently lost.
     */
    private function queueMoveContent(string $inner, string $pos): void
    {
        $inner = trim($inner);
        if ($inner === '') return;

        $matched = []; // collect matched tag strings for leftover detection

        // ── <link rel="stylesheet"> ───────────────────────────────────────────
        preg_match_all('/<link\b[^>]*\brel=["\']stylesheet["\'][^>]*>/i', $inner, $m);
        foreach ($m[0] as $tag) {
            if (!preg_match('/\bhref=["\']([^"\']+)["\']/i', $tag, $hm)) continue;
            $attrs = self::parseAttrs($tag, skip: ['rel', 'href', 'asset-pos']);
            $this->enqueue('css', $hm[1], $pos, $attrs, '');
            $matched[] = $tag;
        }

        // ── <style>…</style> ─────────────────────────────────────────────────
        preg_match_all('/<style\b[^>]*>(.*?)<\/style>/is', $inner, $m);
        foreach ($m[0] as $idx => $fullTag) {
            $code = trim($m[1][$idx]);
            if ($code === '') continue;
            $headPos = str_starts_with($pos, 'body') ? 'head_late' : $pos;
            $this->enqueue('inline_css',
                $this->useMinifyCss ? call_user_func($this->cssMinifier, $code) : $code,
                $headPos, [], ''
            );
            $matched[] = $fullTag;
        }

        // ── <script> tags — one pass for both external src= and inline code ──
        preg_match_all('/<script\b([^>]*)>(.*?)<\/script>/is', $inner, $m);
        foreach ($m[0] as $idx => $fullTag) {
            $attrsStr = $m[1][$idx];
            if (preg_match('/\bsrc=["\']([^"\']+)["\']/i', $attrsStr, $sm)) {
                // External script file
                $attrs = self::parseAttrs($fullTag, skip: ['src', 'asset-pos']);
                $this->enqueue('js', $sm[1], $pos, $attrs, '');
            } else {
                // Inline script code
                $code = trim($m[2][$idx]);
                if ($code === '') continue;
                $this->enqueue('inline_js',
                    $this->useMinifyJs ? call_user_func($this->jsMinifier, $code) : $code,
                    $pos, [], ''
                );
            }
            $matched[] = $fullTag;
        }

        // ── <meta> tags ───────────────────────────────────────────────────────
        preg_match_all('/<meta\b[^>]*>/i', $inner, $m);
        foreach ($m[0] as $tag) {
            $action   = preg_match('/\bmeta-add\b/i', $tag) ? 'add' : 'replace';
            $cleanTag = trim((string)preg_replace('/\s+meta-(replace|add)\b/i', '', $tag));
            $this->enqueue('meta', $cleanTag, 'head_middle', ['action' => $action], '');
            $matched[] = $tag;
        }

        // ── <title>…</title> ─────────────────────────────────────────────────
        preg_match_all('/<title\b[^>]*>(.*?)<\/title>/is', $inner, $m);
        foreach ($m[0] as $idx => $fullTag) {
            $text = trim($m[1][$idx]);
            if ($text === '') continue;
            $this->enqueue('title', $text, 'head_middle', ['action' => 'replace'], '');
            $matched[] = $fullTag;
        }

        // ── Leftover / arbitrary HTML ─────────────────────────────────────────
        // If the MOVE block contained content we didn't recognise (e.g. a bare
        // <div> or mixed markup), queue it as raw HTML so nothing is silently lost.
        // Each matched tag is removed individually (not as one concatenated string)
        // so non-adjacent tags are all correctly stripped.
        $remaining = $inner;
        foreach ($matched as $tag) {
            $remaining = str_replace($tag, '', $remaining);
        }
        $remaining = trim((string)preg_replace('/^\s*$/m', '', $remaining));
        if ($remaining !== '') {
            // Force body position for raw HTML — it cannot go in <head>
            $htmlPos = str_starts_with($pos, 'body') ? $pos : 'body_early';
            $this->enqueue('html', $remaining, $htmlPos, [], '');
        }
    }

    // ── Injection ─────────────────────────────────────────────────────────────

    private function inject(string &$content): void
    {
        if (empty($this->queue)) return;

        // injectMetaTitle() modifies $content (replaces existing tags, changes length).
        // findAnchors() must run AFTER it so byte offsets are computed on the final string.
        $this->injectMetaTitle($content);

        $anchors = $this->findAnchors($content);

        // All 9 canonical positions in DOM order
        $allPositions = [
            'head_top', 'head_early', 'head_middle', 'head_late', 'head_last',
            'body_top', 'body_early', 'body_late', 'body_last',
        ];

        // Build HTML chunks per position (CSS before JS within each)
        $insertions = [];
        foreach ($allPositions as $pos) {
            if (empty($this->queue[$pos])) continue;
            $html = $this->buildHtml($this->queue[$pos], $pos);
            if ($html !== '') $insertions[$pos] = $html;
        }

        if (empty($insertions)) return;

        // Insert in reverse DOM order so byte offsets remain valid.
        // Positions sharing the same anchor offset (e.g. head_middle / head_late / head_last
        // all sit at </head>) are inserted last-first so the final order is correct.
        //
        // Fallback: head positions whose anchor is null (no </head> found) are redirected
        // to body_top so CSS/JS queued from body-scanner extraction is never silently lost.
        $bodyTopFallback = $anchors['body_top'] ?? null;
        foreach (array_reverse($allPositions) as $pos) {
            if (empty($insertions[$pos])) continue;
            $anchor = $anchors[$pos] ?? null;
            if ($anchor === null) {
                // Only fall back head positions — body positions without anchors are dropped.
                if (str_starts_with($pos, 'head_') && $bodyTopFallback !== null) {
                    $anchor = $bodyTopFallback;
                } else {
                    continue;
                }
            }
            $content = substr_replace($content, $insertions[$pos], $anchor, 0);
        }
    }

    /**
     * Walk queued meta/title entries and attempt in-place replacement of matching
     * tags already present in <head>.  Entries that succeed are removed from the
     * queue; unmatched 'replace' entries and all 'add' entries remain for the
     * normal anchor-insertion step.
     */
    private function injectMetaTitle(string &$content): void
    {
        $headEnd = stripos($content, '</head>');
        if ($headEnd === false) return;

        $head = substr($content, 0, $headEnd);
        $rest = substr($content, $headEnd);

        foreach (['head_early', 'head_middle', 'head_late'] as $pos) {
            if (empty($this->queue[$pos])) continue;

            $remaining = [];
            foreach ($this->queue[$pos] as $entry) {

                if ($entry['type'] === 'title') {
                    if (($entry['attrs']['action'] ?? 'replace') === 'replace') {
                        $escaped = htmlspecialchars($entry['item'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                        $head    = (string)preg_replace(
                            '/<title\b[^>]*>.*?<\/title>/is',
                            '<title>' . $escaped . '</title>',
                            $head, 1, $count
                        );
                        if ($count > 0) continue; // replaced — drop from queue
                    }
                    $remaining[] = $entry;

                } elseif ($entry['type'] === 'meta') {
                    if (($entry['attrs']['action'] ?? 'replace') === 'replace') {
                        $id = $this->metaIdentifier($entry['item']);
                        if ($id !== null) {
                            [$attr, $val] = $id;
                            $pattern = '/<meta\b[^>]*\b'
                                     . preg_quote($attr, '/') . '\s*=\s*["\']'
                                     . preg_quote($val,  '/') . '["\'][^>]*>/i';
                            $head = (string)preg_replace($pattern, $entry['item'], $head, 1, $count);
                            if ($count > 0) continue; // replaced — drop from queue
                        }
                    }
                    $remaining[] = $entry;

                } else {
                    $remaining[] = $entry;
                }
            }

            $this->queue[$pos] = $remaining;
        }

        $content = $head . $rest;
    }

    /**
     * Extract the identifying attribute from a <meta> tag so we can locate an
     * existing matching tag to replace.
     * Returns e.g. ['name', 'description'] for <meta name="description" …>.
     * Returns null if no known identifier is present.
     */
    private function metaIdentifier(string $metaTag): ?array
    {
        foreach (['name', 'property', 'http-equiv'] as $attr) {
            if (preg_match('/\b' . $attr . '\s*=\s*["\']([^"\']+)["\']/i', $metaTag, $m)) {
                return [$attr, $m[1]];
            }
        }
        return null;
    }

    /**
     * Locate the byte offset of each injection anchor in $content.
     *
     *   head_early  — just after </title>; fallback: just after <head …>
     *   head_middle — just before </head>  (inserted before head_late in reverse pass)
     *   head_late   — just before </head>
     *   body_early  — just after <body …>
     *   body_late   — just before </body>
     */
    private function findAnchors(string $content): array
    {
        $anchors = [];

        // ── <head> ───────────────────────────────────────────────────────────────

        $headOpen = stripos($content, '<head');
        $headOpenClose = $headOpen !== false ? strpos($content, '>', $headOpen) : false;
        $afterHeadTag  = $headOpenClose !== false ? $headOpenClose + 1 : null;

        // head_top — immediately after <head …>
        $anchors['head_top'] = $afterHeadTag;

        // head_early — after </title>; fallback: after <head …>
        $titleEnd = stripos($content, '</title>');
        if ($titleEnd !== false) {
            $anchors['head_early'] = $titleEnd + strlen('</title>');
        } else {
            $anchors['head_early'] = $afterHeadTag;
        }

        // head_middle / head_late / head_last all anchor at </head>.
        // Reverse-order insertion keeps them in the right order:
        //   head_last inserted first → head_late before it → head_middle before that.
        $headEnd = stripos($content, '</head>');
        $anchors['head_middle'] = $headEnd !== false ? $headEnd : null;
        $anchors['head_late']   = $headEnd !== false ? $headEnd : null;
        $anchors['head_last']   = $headEnd !== false ? $headEnd : null;

        // ── <body> ───────────────────────────────────────────────────────────────

        $bodyOpen      = stripos($content, '<body');
        $bodyOpenClose = $bodyOpen !== false ? strpos($content, '>', $bodyOpen) : false;
        $afterBodyTag  = $bodyOpenClose !== false ? $bodyOpenClose + 1 : null;

        // body_top / body_early both anchor right after <body …>.
        // Reverse-order insertion: body_early first → body_top before it.
        $anchors['body_top']   = $afterBodyTag;
        $anchors['body_early'] = $afterBodyTag;

        // body_late / body_last both anchor at last </body>.
        // Reverse-order insertion: body_last first → body_late before it.
        $bodyEnd = strripos($content, '</body>');
        $anchors['body_late'] = $bodyEnd !== false ? $bodyEnd : null;
        $anchors['body_last'] = $bodyEnd !== false ? $bodyEnd : null;

        return $anchors;
    }

    /**
     * Render a list of queue entries as an HTML string.
     *
     * Ordering within a position:
     *   1. meta tags
     *   2. title tags
     *   3. CSS files (<link>) and inline CSS (<style>) — grouped, always before scripts
     *   4. JS entries in insertion order — <script src> and inline <script> interleaved
     *   5. raw HTML — before or after the JS group depending on $pos, see below
     *
     * CSS and meta/title are always emitted before scripts (head-semantic correctness).
     * Within the JS group, file references and inline blocks honour the exact order
     * in which they were registered via insertJsFile() / insertJsCode(), so that
     * bootstrapping code can safely precede or follow any file it depends on.
     *
     * HTML-vs-JS order is position-dependent, matching the legacy Insert/I class
     * placeholder scheme modules were built against (framework/Insert.php,
     * pre-AssetQueue): at "top"/"early"/"middle" positions JS came before HTML,
     * but at "late"/"last" (BODY BTM-style) positions HTML came before JS — e.g.
     * a module rendering `<script>var cfg = {...}</script>` via insertHtmlCode()
     * plus a library via insertJsFile() at 'body_late' relies on its inline config
     * running before the library, which reads that global synchronously on load.
     * Reversing this at late positions silently breaks such modules.
     */
    private function buildHtml(array $entries, string $pos = ''): string
    {
        $meta  = '';
        $title = '';
        $css   = '';
        $icss  = '';
        $js    = '';  // JS entries emitted in insertion order
        $html  = '';

        foreach ($entries as $entry) {
            switch ($entry['type']) {

                case 'meta':
                    $meta .= $entry['item'] . "\n";
                    break;

                case 'title':
                    // Reached only for action='add' or when replace found no match
                    $title .= '<title>'
                            . htmlspecialchars($entry['item'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
                            . "</title>\n";
                    break;

                case 'css':
                    $url = $this->resolveUrl($this->maybeMinifyFile('css', $entry['item']));
                    if ($url === null) break;
                    $css .= '<link rel="stylesheet" href="' . htmlspecialchars($url) . '"'
                          . self::renderAttrs($entry['attrs']) . ">\n";
                    break;

                case 'inline_css':
                    $icss .= ($this->useMinifyCss
                        ? (string)call_user_func($this->cssMinifier, $entry['item'])
                        : $entry['item']) . "\n";
                    break;

                case 'js':
                    $url = $this->resolveUrl($this->maybeMinifyFile('js', $entry['item']));
                    if ($url === null) break;
                    $js .= '<script src="' . htmlspecialchars($url) . '"'
                         . self::renderAttrs($entry['attrs']) . "></script>\n";
                    break;

                case 'inline_js':
                    // Only minify inline JS with Mullie — the naive fallback minifier
                    // cannot safely handle line comments without breaking URL strings.
                    $useMullieInline = $this->useMinifyJs
                        && class_exists(\MatthiasMullie\Minify\JS::class, false);
                    $code = $useMullieInline
                        ? (string)call_user_func($this->jsMinifier, $entry['item'])
                        : $entry['item'];
                    $js .= "<script>\n{$code}\n</script>\n";
                    break;

                case 'html':
                    $html .= $entry['item'] . "\n";
                    break;
            }
        }

        $out  = $meta;
        $out .= $title;
        $out .= $css;
        $out .= $icss !== '' ? "<style>\n{$icss}</style>\n" : '';

        // 'late' / 'last' positions (BODY BTM-style): HTML before JS.
        // All other positions (top/early/middle): JS before HTML.
        $htmlBeforeJs = str_ends_with($pos, '_late') || str_ends_with($pos, '_last');
        if ($htmlBeforeJs) {
            $out .= $html;
            $out .= $js;
        } else {
            $out .= $js;
            $out .= $html;
        }
        return $out !== '' ? "\n" . $out : '';
    }

    // ── URL / path resolution ─────────────────────────────────────────────────

    /**
     * Resolve a file reference to a public URL suitable for a <link href> or
     * <script src> attribute.
     *
     * {TOKEN} placeholders are expanded.  External URLs pass through unchanged.
     * Local files are verified to exist on disk; optionally a cache-busting mtime
     * query string is appended.  Returns null if the local file cannot be found.
     */
    private function resolveUrl(string $file): ?string
    {
        $url = strtr($file, $this->urlTokenMap());

        if ($this->isExternal($url)) {
            // Same-origin http(s) URLs get cache busting via local path lookup.
            // Cross-origin CDN URLs are left unchanged (no filesystem access).
            if (defined('ASSET_CACHE_BUSTING') && ASSET_CACHE_BUSTING) {
                $path = $this->urlToLocalPath($url);
                if ($path !== null) {
                    $sep  = str_contains($url, '?') ? '&' : '?';
                    $url .= $sep . filemtime($path);
                }
            }
            return $url;
        }

        $path = $this->urlToLocalPath($url);
        if ($path === null) {
            if ($this->debug) error_log("I: file not found: $file");
            return null;
        }

        if (defined('ASSET_CACHE_BUSTING') && ASSET_CACHE_BUSTING) {
            $sep  = str_contains($url, '?') ? '&' : '?';
            $url .= $sep . filemtime($path);
        }

        return $url;
    }

    /**
     * Resolve a file reference to an absolute filesystem path.
     * Used when building combined bundles to read source content.
     * Returns null if the file cannot be located on disk.
     */
    private function resolveLocalPath(string $file): ?string
    {
        $path = strtr($file, $this->pathTokenMap());

        if ($this->isExternal($path)) {
            $path = $this->urlToLocalPath($path);
            return ($path !== null && is_file($path)) ? $path : null;
        }

        return is_file($path) ? $path : null;
    }

    /** Build {TOKEN} → URL map for use in strtr(). */
    private function urlTokenMap(): array
    {
        return $this->urlTokens;
    }

    /**
     * Build {TOKEN} → filesystem-path map.
     * For each URL token whose value starts with WB_URL, the path equivalent
     * is derived by swapping WB_URL → WB_PATH.
     */
    private function pathTokenMap(): array
    {
        $wbUrl  = defined('WB_URL')  ? rtrim(WB_URL,  '/') : '';
        $wbPath = defined('WB_PATH') ? rtrim(WB_PATH, '/') : '';
        $map    = [];

        foreach ($this->urlTokens as $token => $url) {
            if ($wbUrl !== '' && str_starts_with($url, $wbUrl)) {
                $map[$token] = $wbPath . substr($url, strlen($wbUrl));
            }
        }

        return $map;
    }

    /**
     * Convert a same-domain absolute URL to a local filesystem path.
     * Returns null for external domains or if the resolved file does not exist.
     *
     *   https://example.com/modules/foo/bar.js  →  /var/www/modules/foo/bar.js
     */
    private function urlToLocalPath(string $url): ?string
    {
        $wbUrl  = defined('WB_URL')  ? rtrim(WB_URL,  '/') : '';
        $wbPath = defined('WB_PATH') ? rtrim(WB_PATH, '/') : '';

        $clean = strtok($url, '?') ?: $url; // strip query string
        if ($wbUrl !== '' && str_starts_with($clean, $wbUrl)) {
            $rel  = substr($clean, strlen($wbUrl));
            $path = $wbPath . '/' . ltrim($rel, '/');
            return is_file($path) ? $path : null;
        }
        return null;
    }

    /** Returns true for http://, https://, and protocol-relative // URLs. */
    private function isExternal(string $url): bool
    {
        return str_starts_with($url, 'http://')
            || str_starts_with($url, 'https://')
            || str_starts_with($url, '//');
    }

    // ── URL token initialisation ──────────────────────────────────────────────

    /**
     * Build the initial {TOKEN} → URL map from WBCE constants.
     * Called once in __construct; can be extended at runtime via addUrlToken().
     */
    private function buildUrlTokens(): array
    {
        $wbUrl = defined('WB_URL') ? rtrim(WB_URL, '/') : '';

        // Resolve default template URL
        $tplUrl = $wbUrl . '/templates/';
        if (defined('TEMPLATE')) {
            $tplUrl .= TEMPLATE;
        } elseif (class_exists('Settings', false) && method_exists('Settings', 'Get')) {
            $tplUrl .= (string)Settings::Get('default_template');
        }

        $tokens = [
            '{WB_URL}'           => $wbUrl,
            '{BASE_URL}'         => $wbUrl,              // wbEasy compat alias
            '{MODULES}'          => $wbUrl . '/modules',
            '{MODULES_URL}'      => defined('MODULES_URL')    ? MODULES_URL    : $wbUrl . '/modules',
            '{TEMPLATES_URL}'    => defined('TEMPLATES_URL')  ? TEMPLATES_URL  : $wbUrl . '/templates',
            '{DEFAULT_TEMPLATE}' => $tplUrl,
            '{TEMPLATE}'         => $tplUrl,
            '{THEME_URL}'        => defined('THEME_URL')      ? THEME_URL      : $tplUrl,
            '{CACHE}'            => $wbUrl . '/cache',
        ];

        if (defined('MEDIA_DIRECTORY')) {
            $tokens['{MEDIA_URL}'] = $wbUrl . MEDIA_DIRECTORY;
        }
        if (defined('ADMIN_URL')) {
            $tokens['{ADMIN_URL}'] = ADMIN_URL;
        }

        return $tokens;
    }

    // ── Internal queue helpers ────────────────────────────────────────────────

    /**
     * Mark all source files of a combined bundle as seen so they are not
     * re-queued individually by register_frontend_modfiles() or direct
     * insertCssFile() / insertJsFile() calls.
     *
     * Called after a successful buildCombined() — sources are only marked when
     * the bundle was actually written, so a failed build does not silently
     * suppress individual file loading.
     *
     * Both the raw URL string and the resolved absolute path are recorded:
     *   - raw URL  → catches identical string references
     *   - abs path → catches the same file referenced via different URL forms
     *               (token syntax, assets/ sub-path, full URL, etc.)
     *
     * @param  string   $type     'css' or 'js'
     * @param  string[] $sources  Source URLs as passed to insertCombined*()
     */
    private function markCombinedSources(string $type, array $sources): void
    {
        $bundlePaths = [];

        foreach ($sources as $src) {
            $src = trim((string)$src);
            if ($src === '') continue;

            $this->seen[$type . '|' . $src] = true;

            $absPath = $this->resolveLocalPath($src);
            if ($absPath !== null) {
                $this->seenPaths[$type . '|' . $absPath] = true;
                $bundlePaths[] = $absPath;
            }
        }

        // Evict individual entries already in the queue that are now subsumed by
        // this bundle. Handles the case where a module's include.php ran before the
        // template's insertCssBundle/insertJsBundle call and enqueued the same file
        // individually (e.g. klaro_consent calling I::insertCssFile() from include.php
        // before the template bundles the same CSS file).
        if (!empty($bundlePaths)) {
            foreach ($this->queue as &$entries) {
                $entries = array_values(array_filter(
                    $entries,
                    function (array $e) use ($type, $bundlePaths): bool {
                        if ($e['type'] !== $type) return true;
                        $path = $this->resolveLocalPath($e['item']);
                        return $path === null || !in_array($path, $bundlePaths, true);
                    }
                ));
            }
            unset($entries);
        }
    }

    private function enqueue(string $type, string $item, string $position, array $attrs, string $id): void
    {
        if ($type === 'css' || $type === 'js') {
            // Fast path: exact raw-URL dedup (token syntax, same call site)
            $rawKey = $type . '|' . $item;
            if (isset($this->seen[$rawKey])) return;

            // Path-based dedup: catches same file referenced via different URL forms
            // (e.g. '{MODULES}/foo/frontend.css'  vs  'http://…/modules/foo/frontend.css',
            //  or 'frontend.css'  vs  'assets/frontend.css' with same resolved path).
            // Skips files already claimed by insertCssBundle/insertJsBundle.
            $absPath = $this->resolveLocalPath($item);
            if ($absPath !== null) {
                $pathKey = $type . '|' . $absPath;
                if (isset($this->seenPaths[$pathKey])) return;
                $this->seenPaths[$pathKey] = true;
            }

            $this->seen[$rawKey] = true;
        }

        $this->queue[$position][] = compact('type', 'item', 'attrs', 'id');
    }

    /**
     * Normalize a position string to one of the five canonical names.
     * Accepts both new lowercase names and all legacy WBCE uppercase names.
     *
     * @param string $pos   Raw position string from caller.
     * @param string $type  Asset type: 'css' | 'js' | 'meta' | 'title' | 'html' | 'inline_css' | 'inline_js'
     */
    private function normalizePos(string $pos, string $type): string
    {
        // ── Legacy WBCE uppercase position names ──────────────────────────────
        static $legacyMap = [
            'HEAD TOP+'  => 'head_early',  'HEAD TOP-'  => 'head_early',
            'HEAD TOP'   => 'head_early',
            'HEAD BTM+'  => 'head_late',   'HEAD BTM-'  => 'head_late',
            'HEAD BTM'   => 'head_late',   'HEAD-'      => 'head_late',
            'HEAD'       => 'head_late',
            'BODY TOP+'  => 'body_early',  'BODY TOP-'  => 'body_early',
            'BODY TOP'   => 'body_early',  'BODY+'      => 'body_early',
            'BODY BTM+'  => 'body_late',   'BODY BTM-'  => 'body_late',
            'BODY BTM'   => 'body_late',   'BODY-'      => 'body_late',
            'BODY'       => 'body_late',
            // modfiles positions — head variants always go to head_late (no defer)
            'HEAD MODFILES'     => 'head_late',
            'CSS HEAD MODFILES' => 'head_late',
            'JS HEAD MODFILES'  => 'head_late',
            'BODY MODFILES'     => 'body_late',
            'JS BODY MODFILES'  => 'body_late',
            // old meta-specific positions
            'HEAD+'      => 'head_middle',
            'KEY+'       => 'head_middle',
            'DESC+'      => 'head_middle',
        ];

        $upper = strtoupper(trim($pos));
        if (isset($legacyMap[$upper])) {
            return $legacyMap[$upper];
        }

        // ── New lowercase names & shorthands ──────────────────────────────────
        $lower = strtolower(trim($pos));

        $shortMap = [
            'head'   => 'head_late',
            'body'   => 'body_late',
            'early'  => 'head_early',
            'middle' => 'head_middle',
            // 'late' is type-dependent: JS defaults to body, CSS defaults to head
            'late'   => in_array($type, ['js', 'inline_js'], true) ? 'body_late' : 'head_late',
        ];
        if (isset($shortMap[$lower])) {
            return $shortMap[$lower];
        }

        // ── Type safety corrections ───────────────────────────────────────────

        // CSS, inline CSS, meta, title cannot live in <body>
        if (in_array($type, ['css', 'inline_css', 'meta', 'title'], true)
            && str_starts_with($lower, 'body')
        ) {
            return in_array($type, ['css', 'inline_css'], true) ? 'head_late' : 'head_middle';
        }

        // Raw HTML can only live in <body>
        if ($type === 'html' && str_starts_with($lower, 'head')) {
            return 'body_early';
        }

        // ── Validate — unknown strings fall back to type default ──────────────
        $valid = [
            'head_top', 'head_early', 'head_middle', 'head_late', 'head_last',
            'body_top', 'body_early', 'body_late', 'body_last',
        ];
        if (in_array($lower, $valid, true)) {
            return $lower;
        }

        return in_array($type, ['js', 'inline_js'], true) ? 'body_late' : 'head_late';
    }

    /** Extract the asset-pos attribute value from a tag string, or return null. */
    private function assetPosAttr(string $tag, string $type): ?string
    {
        if (!preg_match('/\basset-pos=["\']?([a-zA-Z_]+)["\']?/i', $tag, $m)) return null;
        return $this->normalizePos($m[1], $type);
    }

    /**
     * Parse HTML attributes from a tag string into a key → value array.
     * Boolean attributes (e.g. defer, async) are stored as true.
     * Attributes listed in $skip are excluded.
     */
    private static function parseAttrs(string $tag, array $skip = []): array
    {
        $attrs = [];
        preg_match_all(
            '/\b([\w-]+)(?:\s*=\s*(?:"([^"]*)"|\'([^\']*)\'|(\S+)))?/i',
            $tag, $m, PREG_SET_ORDER
        );
        foreach ($m as $match) {
            $name = strtolower($match[1]);
            if (in_array($name, $skip, true)) continue;
            if (in_array($name, ['link', 'script', 'style'], true)) continue; // tag names
            $value        = $match[2] ?? $match[3] ?? $match[4] ?? null;
            $attrs[$name] = $value !== null ? $value : true;
        }
        return $attrs;
    }

    /** Render an attributes array as an HTML attribute string (space-prefixed). */
    private static function renderAttrs(array $attrs): string
    {
        $str = '';
        foreach ($attrs as $k => $v) {
            $str .= $v === true
                ? " $k"
                : ' ' . $k . '="' . htmlspecialchars((string)$v, ENT_QUOTES) . '"';
        }
        return $str;
    }

    private function reset(): void
    {
        $this->queue      = [];
        $this->seen       = [];
        $this->seenPaths  = [];
    }
}

