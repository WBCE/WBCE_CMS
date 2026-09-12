<?php
/**
 * MdReaderHelper
 *
 * Internal helper for reader.php.
 * Handles: path security, language resolution, manifest parsing,
 *          Markdown rendering, TOC generation, language flag detection.
 *
 * Not part of the public API — use MdReaderLink for building links.
 *
 * @package  MarkdownWbce
 * @author   Christian M. Stefan (https://www.wbEasy.de/)
 * @version  0.1.0
 */
defined('WB_PATH') or die('No direct access allowed');

class MdReaderHelper
{
    // ── Path security ─────────────────────────────────────────────────────────

    /**
     * Resolve a relative path (relative to WB_PATH) to an absolute filesystem
     * path and verify it is inside WB_PATH (path-traversal guard) AND has a
     * .md extension.
     *
     * The extension check is defense-in-depth: this resolver backs both the
     * read-only reader and the (admin-session-gated) file-overwrite endpoint,
     * and the latter is reachable from a frontend-adjacent trigger — without
     * it, an authenticated session could read/overwrite arbitrary files under
     * WB_PATH (e.g. config.php), not just documentation.
     *
     * Returns the real absolute path or null if the path is invalid / outside
     * the document root / not a .md file.
     */
    public static function safePath(string $relativePath): ?string
    {
        // Extension whitelist — case-insensitive, must end in .md
        if (!preg_match('/\.md$/i', $relativePath)) {
            return null;
        }

        // Strip leading slash, join with WB_PATH
        $candidate = WB_PATH . '/' . ltrim($relativePath, '/');

        // Normalise (resolves ../ etc.)
        $real = realpath($candidate);

        if ($real === false) {
            return null;
        }

        // Must be inside WB_PATH
        $root = realpath(WB_PATH);
        if ($root === false || !str_starts_with($real, $root . DIRECTORY_SEPARATOR)) {
            return null;
        }

        // Must be a readable file
        if (!is_file($real) || !is_readable($real)) {
            return null;
        }

        return $real;
    }

    /**
     * Same as safePath() but for directories.
     * Returns real absolute path or null.
     */
    public static function safeDir(string $relativePath): ?string
    {
        $candidate = WB_PATH . '/' . ltrim($relativePath, '/');
        $real      = realpath($candidate);

        if ($real === false) {
            return null;
        }

        $root = realpath(WB_PATH);
        if ($root === false || !str_starts_with($real, $root . DIRECTORY_SEPARATOR)) {
            return null;
        }

        if (!is_dir($real)) {
            return null;
        }

        return $real;
    }

    // ── Language resolution ───────────────────────────────────────────────────

    /**
     * Given an absolute path to a .md file and a language code (e.g. 'DE'),
     * returns the absolute path to the localised variant if it exists,
     * otherwise returns the original path.
     *
     * Example:
     *   /var/www/wbce/modules/foo/docs/README.md  +  'DE'
     *   → /var/www/wbce/modules/foo/docs/README_DE.md  (if it exists)
     *   → /var/www/wbce/modules/foo/docs/README.md     (fallback)
     */
    public static function resolveLanguage(string $absPath, string $langCode): string
    {
        if ($langCode === '') {
            return $absPath;
        }

        $localised = preg_replace('/\.md$/i', '_' . strtoupper($langCode) . '.md', $absPath);

        return ($localised && file_exists($localised)) ? $localised : $absPath;
    }

    /**
     * Resolve a "logical" .md path to whichever file actually exists on
     * disk, applying the same base→localized preference as
     * resolveLanguage() even when the base file itself doesn't exist (e.g.
     * only README_DE.md was ever committed, no plain README.md).
     *
     * Order tried:
     *   1. base file exists → base, unless an exact <LANG> variant also
     *      exists (same preference as resolveLanguage())
     *   2. base file missing, exact <LANG> variant exists → that variant
     *   3. base file missing, no exact <LANG> variant → first available
     *      *_<CODE>.md variant found in the same directory (alphabetical)
     *   4. nothing at all → null
     *
     * Always returns a safePath()-validated path (or null) — every branch
     * re-enters the same extension/containment/is_file() checks.
     */
    public static function findExistingDoc(string $relativePath, string $langCode): ?string
    {
        $base = self::safePath($relativePath);
        if ($base !== null) {
            return self::resolveLanguage($base, $langCode);
        }

        if (!preg_match('/\.md$/i', $relativePath)) {
            return null;
        }

        // Exact current-language variant.
        if ($langCode !== '') {
            $exact = preg_replace('/\.md$/i', '_' . strtoupper($langCode) . '.md', $relativePath);
            $found = $exact !== null ? self::safePath($exact) : null;
            if ($found !== null) {
                return $found;
            }
        }

        // Any other *_<CODE>.md variant in the same directory — first
        // match, alphabetically, so the result is at least deterministic.
        $dirRel = dirname($relativePath);
        $realDir = self::safeDir($dirRel === '.' ? '/' : $dirRel);
        if ($realDir === null) {
            return null;
        }

        $stem = preg_replace('/\.md$/i', '', basename($relativePath));
        // glob() wildcards, not regex — escape glob metacharacters in the
        // stem so an unusual filename can't widen the pattern.
        $escapedStem = strtr($stem, ['*' => '[*]', '?' => '[?]', '[' => '[[]']);

        $candidates = glob($realDir . '/' . $escapedStem . '_*.md') ?: [];
        sort($candidates);

        $root = realpath(WB_PATH);
        foreach ($candidates as $candidate) {
            $relCandidate = '/' . ltrim(str_replace($root, '', $candidate), '/\\');
            $validated    = self::safePath($relCandidate);
            if ($validated !== null) {
                return $validated;
            }
        }

        return null;
    }

    /**
     * Detect all available language variants for a given .md file.
     * Scans WB_PATH/languages/ for *.svg files to get the known language codes,
     * then checks which _CODE.md variants exist on disk.
     *
     * Returns an array of entries:
     *   [
     *     'code'    => 'DE',
     *     'flag'    => WB_URL . '/languages/DE.svg',
     *     'relPath' => '/modules/foo/docs/README_DE.md',
     *   ]
     *
     * The plain base file (README.md) is included too — as an 'EN' entry —
     * whenever it exists AND at least one *_<CODE>.md sibling also exists.
     * This project's own convention treats the base file as the English
     * fallback (see docs/README.md's "Sprachversionen" section: "README.md
     * ← Fallback / Englisch"); without this, a doc that has both README.md
     * and README_DE.md showed only a lone "DE" flag once the DE variant was
     * open, with no way back to the base file at all.
     */
    public static function availableLanguages(string $absPath): array
    {
        $result  = [];
        $langDir = WB_PATH . '/languages/';

        if (!is_dir($langDir)) {
            return $result;
        }

        // Normalise back to the logical base filename first — $absPath may
        // itself already be a localized variant (findExistingDoc() serves
        // README_DE.md when no plain README.md exists at all), and sibling
        // variants are always named off the base stem, not off another
        // language's suffix (README_DE_FR.md would never match anything).
        $baseAbsPath = preg_replace('/_[A-Za-z]{2,3}\.md$/', '.md', $absPath) ?? $absPath;

        foreach (glob($langDir . '*.svg') as $svg) {
            $code      = strtoupper(basename($svg, '.svg'));
            $localised = preg_replace('/\.md$/i', '_' . $code . '.md', $baseAbsPath);

            if ($localised && file_exists($localised)) {
                $result[] = [
                    'code'    => $code,
                    'flag'    => WB_URL . '/languages/' . $code . '.svg',
                    'relPath' => self::_toWebRelPath($localised),
                ];
            }
        }

        // Prepend the base file as 'EN', but only when there's something to
        // switch BETWEEN — a doc with no localized siblings at all still
        // gets no language switcher (nothing to choose).
        $hasEnAlready = in_array('EN', array_column($result, 'code'), true);
        if (!empty($result) && !$hasEnAlready
            && file_exists($langDir . 'EN.svg')
            && file_exists($baseAbsPath)
        ) {
            array_unshift($result, [
                'code'    => 'EN',
                'flag'    => WB_URL . '/languages/EN.svg',
                'relPath' => self::_toWebRelPath($baseAbsPath),
            ]);
        }

        return $result;
    }

    // ── Manifest parser (Variante D) ──────────────────────────────────────────

    /**
     * Load and parse a directory manifest (md_reader.json).
     *
     * Returns a normalised docs array:
     *   [
     *     ['path' => '/modules/…/README.md', 'label' => 'Overview'],
     *     …
     *   ]
     * and a title string.
     *
     * Falls back to README.md in the directory when no manifest exists.
     *
     * @return array{title: string, docs: array}
     */
    public static function loadManifest(string $absDir): array
    {
        $manifestFile = rtrim($absDir, '/\\') . '/md_reader.json';

        if (file_exists($manifestFile)) {
            $raw = json_decode(file_get_contents($manifestFile), true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($raw)) {
                // Malformed JSON — treat as empty manifest
                $raw = [];
            }

            $title = $raw['title'] ?? basename($absDir);

            // D-manifest: has 'docs' array
            if (!empty($raw['docs']) && is_array($raw['docs'])) {
                $docs = [];
                foreach ($raw['docs'] as $entry) {
                    $file = $entry['file'] ?? '';
                    if ($file === '') continue;

                    // path relative to the manifest directory
                    $absFile = rtrim($absDir, '/\\') . '/' . ltrim($file, '/');
                    $relPath = self::_toWebRelPath($absFile);

                    $docs[] = [
                        'path'  => $relPath,
                        'label' => $entry['label'] ?? basename($file, '.md'),
                    ];
                }
                return ['title' => $title, 'docs' => $docs];
            }

            // D-simple: manifest exists but no 'docs' key — use README.md
            return ['title' => $title, 'docs' => self::_readmeFallback($absDir)];
        }

        // No manifest at all — README.md fallback
        $title = basename($absDir);
        return ['title' => $title, 'docs' => self::_readmeFallback($absDir)];
    }

    // ── Markdown rendering ────────────────────────────────────────────────────

    /**
     * Render a Markdown file to HTML.
     * Rewrites relative image src attributes to absolute filesystem paths
     * so images load correctly regardless of the reader URL.
     *
     * Returns the HTML string or an empty string on failure.
     */
    public static function renderFile(string $absPath): string
    {
        if (!file_exists($absPath) || !is_readable($absPath)) {
            return '';
        }

        require_once __DIR__ . '/Parsedown/ParsedownWbce.php';

        $markdown = file_get_contents($absPath);
        $parser   = new ParsedownWbce();
        $html     = $parser->text($markdown);

        // Rewrite relative image src to absolute URL
        if (str_contains($html, '<img')) {
            $html = self::_rewriteImagePaths($html, $absPath);
        }

        return $html;
    }

    /**
     * Render a Markdown doc for embedding directly inside a backend page — no
     * popup, no reader.php round-trip.
     *
     * Resolves the language variant, renders to HTML, injects heading anchors +
     * builds a table of contents, and reports which optional assets the content
     * needs. The caller loads layout/markdown.css itself, plus — when the
     * matching flag is set — layout/filetree.css + layout/filetree.js for
     * ```file-tree blocks, and a highlighter for fenced code.
     *
     * @param  string      $relativePath  Doc path relative to WB_PATH (absolute also accepted)
     * @param  string|null $langCode      2-char locale; null = current LANGUAGE
     * @param  string      $tocClass      CSS class for the generated <ul> TOC
     * @return array{abs:string, html:string, toc:string, title:string,
     *               langs:array, needsCode:bool, needsFileTree:bool}|null
     *         null when the file cannot be resolved (missing / outside WB_PATH / not .md)
     */
    public static function renderForEmbed(
        string $relativePath,
        ?string $langCode = null,
        string $tocClass = 'docs-nav'
    ): ?array {
        $lang = $langCode ?? (defined('LANGUAGE') ? (string) LANGUAGE : 'EN');
        $abs  = self::findExistingDoc($relativePath, $lang);
        if ($abs === null || !is_file($abs)) {
            return null;
        }

        $html = self::renderFile($abs);
        if ($html === '') {
            return null;
        }

        $title = self::_firstHeadingText($html) ?: pathinfo($abs, PATHINFO_FILENAME);
        $toc   = self::buildToc($html, $tocClass); // injects anchors into $html by ref

        return [
            'abs'           => $abs,
            'html'          => $html,
            'toc'           => $toc,
            'title'         => $title,
            'langs'         => self::availableLanguages($abs),
            'needsCode'     => self::needsCodeMirror($html),
            'needsFileTree' => self::needsFileTree($html),
        ];
    }

    /**
     * Every stylesheet + script a module needs to show a renderForEmbed()
     * result inline in a backend page. One call — no per-module CSS.
     *
     * Pass the renderForEmbed() array to add the file-tree renderer and the
     * syntax highlighter only when the doc actually needs them; pass null for
     * the base set only.
     *
     *   $doc = MdReaderHelper::renderForEmbed('/modules/x/docs/y.md');
     *   $a   = MdReaderHelper::embedAssets($doc);
     *   foreach ($a['css'] as $u) { I::insertCssFile($u); }
     *   foreach ($a['js']  as $u) { I::insertJsFile($u, 'body_late'); }
     *
     * @param  array<string,mixed>|null $doc  renderForEmbed() result, or null
     * @return array{css:list<string>, js:list<string>}
     */
    public static function embedAssets(?array $doc = null): array
    {
        $css = [
            self::_asset('/layout/markdown.css'),
            self::_asset('/layout/markdown-embed.css'),
        ];
        $js = [
            self::_asset('/layout/markdown-embed.js'),
        ];

        if ($doc !== null && !empty($doc['needsFileTree'])) {
            $css[] = self::_asset('/layout/filetree.css');
            $js[]  = self::_asset('/layout/filetree.js');
        }
        if ($doc !== null && !empty($doc['needsCode'])) {
            $hl    = self::highlightAssets();
            $css[] = $hl['css_light'];
            $js    = array_merge($js, $hl['js']);
        }

        return ['css' => $css, 'js' => $js];
    }

    /**
     * Module-relative asset path → absolute URL. No cache-buster here: the
     * embed feeds these straight into I::insertCssFile()/insertJsFile(), and
     * AssetQueue applies its own ?mtime when OPF_ASSETS_CACHE_BUSTING(_BE) is
     * on. (The reader popup does its own versioning because reader.htt writes
     * the <link>/<script> tags directly, bypassing the queue.)
     */
    private static function _asset(string $rel): string
    {
        $base = defined('MDR_URL') ? MDR_URL : (WB_URL . '/modules/MarkdownWbce');
        return $base . $rel;
    }

    /**
     * Vendored syntax-highlighting assets (highlight.js + GitHub themes +
     * the shared layout/highlight.js init). One source of truth for both the
     * reader popup and inline embeds.
     *
     * @return array{js:list<string>, css_light:string, css_dark:string}
     */
    public static function highlightAssets(): array
    {
        return [
            'js' => [
                self::_asset('/layout/vendor/hljs/highlight.min.js'),
                self::_asset('/layout/highlight.js'),
            ],
            'css_light' => self::_asset('/layout/vendor/hljs/github.min.css'),
            'css_dark'  => self::_asset('/layout/vendor/hljs/github-dark.min.css'),
        ];
    }

    /** First H1–H3 text from rendered HTML, or '' when there is none. */
    private static function _firstHeadingText(string $html): string
    {
        if (preg_match('/<h[1-3][^>]*>(.*?)<\/h[1-3]>/is', $html, $m)) {
            return trim(html_entity_decode(strip_tags($m[1]), ENT_QUOTES, 'UTF-8'));
        }
        return '';
    }

    // ── TOC generation ────────────────────────────────────────────────────────

    /**
     * Scan rendered HTML for headings, inject anchor tags before each heading,
     * and return a nested <ul> TOC.
     *
     * Modifies $html by reference (adds anchors).
     *
     * @param string $html          Rendered HTML (modified in place)
     * @param string $tocClass      CSS class for the outer <ul>
     * @return string               The TOC HTML
     */
    public static function buildToc(string &$html, string $tocClass = 'docs-nav'): string
    {
        $pattern = '/<h([1-6])[^>]*>(.*?)<\/h[1-6]>/is';
        preg_match_all($pattern, $html, $matches);

        if (empty($matches[0])) {
            return '';
        }

        $headings = [];
        foreach ($matches[0] as $i => $fullTag) {
            $level = (int) $matches[1][$i];
            // $matches[2] is the heading's inner HTML — already entity-encoded
            // by Parsedown ("Cache &amp; Status"). Decode before we re-encode
            // in _buildTocHtml(), otherwise the TOC shows a literal "&amp;".
            $text  = html_entity_decode(strip_tags($matches[2][$i]), ENT_QUOTES, 'UTF-8');
            $slug  = self::_slug($text);

            $headings[] = ['tag' => $fullTag, 'level' => $level, 'text' => $text, 'slug' => $slug];

            // Inject anchor before the heading
            $anchor = '<a id="' . htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') . '" class="h-anchor"></a>';
            $html   = str_replace($fullTag, $anchor . $fullTag, $html);
        }

        return self::_buildTocHtml($headings, $tocClass);
    }

    // ── CodeMirror detection ──────────────────────────────────────────────────

    /**
     * Returns true when the rendered HTML contains fenced code blocks
     * that CodeMirror should syntax-highlight.
     */
    public static function needsCodeMirror(string $html): bool
    {
        return (bool) preg_match('/<code\b[^>]*class="language-/i', $html);
    }

    /**
     * Returns true when the rendered HTML contains a ```file-tree fenced
     * or ```page-tree block (rendered by ParsedownWbce::blockFencedCode() as
     * <pre class="file-tree">…</pre>) that layout/filetree.js should turn
     * into a visual icon tree.
     */
    public static function needsFileTree(string $html): bool
    {
        return (bool) preg_match('/<pre\b[^>]*class="(?:file|page)-tree"/i', $html);
    }

    /**
     * Load CodeMirror config from CMS settings.
     * Returns an array with keys: theme, font, font_size.
     * All values fall back to safe defaults when CodeMirror_Config is absent.
     */
    public static function codeMirrorConfig(): array
    {
        $raw = Settings::Get('cmc_cfg', '');
        $cfg = $raw !== '' ? @unserialize($raw) : false;

        if (!is_array($cfg)) {
            $cfg = [];
        }

        return [
            'theme'     => $cfg['theme']     ?? 'default',
            'font'      => $cfg['font']      ?? 'monospace',
            'font_size' => (int) ($cfg['font_size'] ?? 13),
        ];
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    /**
     * Absolute filesystem path → WB_PATH-relative web path ("/modules/…").
     * Normalises backslashes first — WB_PATH itself uses OS-native
     * separators (backslash on Windows), so the remainder after stripping
     * the WB_PATH prefix can still contain internal backslashes that
     * ltrim() (leading chars only) never touches. Left un-normalised, a
     * generated href like "/modules\Foo\docs\README_DE.md" works by
     * Windows/browser leniency in dev but breaks on a real Linux/Apache
     * deployment.
     */
    /**
     * WB_PATH-relative, forward-slashed path for an absolute file path —
     * the form every public API here (and reader.php's edit form) hands
     * around. Public because reader.php needs the relative path of the doc
     * it actually resolved and displays, not the one that was requested.
     */
    public static function _toWebRelPath(string $absPath): string
    {
        $relative = str_replace(WB_PATH, '', $absPath);
        $relative = str_replace('\\', '/', $relative);
        return '/' . ltrim($relative, '/');
    }

    /**
     * Fallback: single README.md in the given directory.
     * @return array
     */
    private static function _readmeFallback(string $absDir): array
    {
        $readme  = rtrim($absDir, '/\\') . '/README.md';
        $relPath = self::_toWebRelPath($readme);

        return [[
            'path'  => $relPath,
            'label' => 'README',
        ]];
    }

    /**
     * Rewrite relative <img src="…"> to absolute URLs.
     */
    private static function _rewriteImagePaths(string $html, string $absFilePath): string
    {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $html);
        libxml_clear_errors();

        $baseDir = dirname($absFilePath);

        foreach ($dom->getElementsByTagName('img') as $img) {
            $src = $img->getAttribute('src');
            if ($src === '' || filter_var($src, FILTER_VALIDATE_URL)) {
                continue; // already absolute
            }
            // Convert to URL relative to WB_URL
            $absImg  = realpath($baseDir . '/' . $src);
            if ($absImg && str_starts_with($absImg, realpath(WB_PATH))) {
                $relImg  = str_replace(realpath(WB_PATH), '', $absImg);
                $img->setAttribute('src', WB_URL . str_replace('\\', '/', $relImg));
            }
        }

        // Extract only the body content (strip doctype/html/head/body wrappers)
        $body = $dom->getElementsByTagName('body')->item(0);
        if (!$body) {
            return $html;
        }

        $out = '';
        foreach ($body->childNodes as $child) {
            $out .= $dom->saveHTML($child);
        }
        return $out;
    }

    /**
     * Generate a URL-friendly slug from a heading string.
     */
    private static function _slug(string $text): string
    {
        $slug = strtolower($text);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        return trim($slug, '-');
    }

    /**
     * Build a nested <ul> TOC from an array of heading entries.
     *
     * @param array<array{level: int, text: string, slug: string}> $headings
     */
    private static function _buildTocHtml(array $headings, string $tocClass): string
    {
        if (empty($headings)) {
            return '';
        }

        $levels   = array_column($headings, 'level');
        $minLevel = min($levels);
        $current  = $minLevel;
        $toc      = '';

        foreach ($headings as $h) {
            $level = $h['level'];
            $link  = '<a href="#' . htmlspecialchars($h['slug'], ENT_QUOTES, 'UTF-8') . '">'
                   . htmlspecialchars($h['text'], ENT_QUOTES, 'UTF-8')
                   . '</a>';

            if ($level > $current) {
                $toc .= '<ul class="level-h' . $level . '">';
                $current = $level;
            } elseif ($level < $current) {
                while ($current > $level) {
                    $toc .= '</li></ul>';
                    $current--;
                }
            } else {
                if ($toc !== '') {
                    $toc .= '</li>';
                }
            }

            $toc .= '<li class="mdr-toc-l' . $level . '">' . $link;
        }

        while ($current > $minLevel) {
            $toc .= '</li></ul>';
            $current--;
        }
        $toc .= '</li>';

        return '<ul class="' . htmlspecialchars($tocClass, ENT_QUOTES, 'UTF-8') . '">'
             . "\n" . $toc . "\n"
             . '</ul>' . "\n";
    }
}
