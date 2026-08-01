<?php
/**
 * framework/Assets/FontCache.php — class FontCache
 *
 * @package    WBCE\Assets
 * @author     Christian M. Stefan  (https://www.wbEasy.de)
 * @copyright  2025-2026 Christian M. Stefan
 * @copyright  2026 WBCE CMS Project
 * @since      WBCE 1.7.0
 * @license    GNU/GPL v2 https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Downloads, caches, and serves web fonts locally.
 * Internal helper for AssetQueue — not part of the public WBCE API.
 *
 * Responsibilities
 * ────────────────
 *  • Fetch font CSS from any provider (Google Fonts, Bunny Fonts, Fontshare, …)
 *  • Download each referenced font file into the local cache directory
 *  • Rewrite url() references in CSS to the local paths
 *  • Generate semantic alias @font-face blocks on request
 *  • Atomic cache writes — concurrent requests never see a partial file
 *  • Admin force-refresh via CTRL+F5 (Cache-Control: no-cache)
 *
 * Usage (from AssetQueue only)
 * ────────────────────────────
 *  $cache  = new FontCache($cacheDir, WB_PATH, WB_URL, $debug);
 *  $result = $cache->resolve($url, 'woff2', 'MainFont');
 *  // $result['cssUrl']   — public URL of the cached local CSS
 *  // $result['aliasCss'] — inline @font-face CSS with the alias name ('' when unused)
 */

final class FontCache
{
    // User-Agent strings — Google Fonts and Bunny Fonts use the UA to decide
    // which font format to return in the CSS.  Other providers ignore it.
    private const USER_AGENTS = [
        'woff2'         => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0',
        'woff2-unicode' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.5112.81 Safari/537.36',
        'woff'          => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:27.0) Gecko/20100101 Firefox/27.0',
        'ttf'           => 'Mozilla/5.0 (Unknown; Linux x86_64) AppleWebKit/538.1 (KHTML, like Gecko) Safari/538.1 Daum/4.1',
    ];

    // Domains whose CSS API is UA-sensitive (format selection via User-Agent).
    // All other providers deliver the format directly without a UA preference.
    private const UA_SENSITIVE = [
        'fonts.googleapis.com',
        'fonts.bunny.net',
    ];

    public function __construct(
        private readonly string $cacheDir,
        private readonly string $wbPath,
        private readonly string $wbUrl,
        private readonly bool   $debug = false,
    ) {
        if (!is_dir($this->cacheDir)) {
            if (!mkdir($this->cacheDir, 0755, true) && !is_dir($this->cacheDir)) {
                error_log('FontCache: cannot create cache directory: ' . $this->cacheDir);
            }
        }
    }

    // ── Public interface ──────────────────────────────────────────────────────

    /**
     * Resolve a font CSS URL to a locally cached copy.
     * Downloads on first use; returns from cache on all subsequent calls.
     * Falls back to the original external URL when cURL is unavailable or
     * the download fails (fonts still load, GDPR benefit is lost).
     *
     * @param string $url    Font CSS URL — any provider.
     * @param string $format UA hint for format selection: woff2 (default), woff2-unicode, woff, ttf.
     * @param string $alias  Optional semantic font-family alias (e.g. 'MainFont').
     *                       When non-empty, additional @font-face blocks are generated that
     *                       map the alias name to the same locally-cached font files.
     *
     * @return array{cssUrl: string, aliasCss: string}
     */
    public function resolve(string $url, string $format, string $alias): array
    {
        $key     = $this->key($url, $format);
        $cssFile = $this->cacheDir . $key . '.css';
        $cssUrl  = $this->toUrl($key . '.css');

        if (!is_file($cssFile)) {
            if (!extension_loaded('curl')) {
                if ($this->debug) {
                    error_log('FontCache: cURL unavailable — using external URL: ' . $url);
                }
                return ['cssUrl' => $url, 'aliasCss' => ''];
            }
            if (!$this->download($url, $format, $cssFile, $key)) {
                if ($this->debug) {
                    error_log('FontCache: download failed — using external URL: ' . $url);
                }
                return ['cssUrl' => $url, 'aliasCss' => ''];
            }
        }

        $aliasCss = ($alias !== '') ? $this->buildAliasCss($cssFile, $alias) : '';

        return ['cssUrl' => $cssUrl, 'aliasCss' => $aliasCss];
    }

    /**
     * Delete all cached files for a specific URL + format combination.
     * Called by AssetQueue on admin force-refresh (CTRL+F5).
     */
    public function forceRefresh(string $url, string $format): void
    {
        $key = $this->key($url, $format);
        foreach (glob($this->cacheDir . $key . '*') ?: [] as $file) {
            @unlink($file);
        }
    }

    /**
     * Wipe the entire font cache directory.
     * Called by AssetQueue::clearFontCache().
     */
    public function clearAll(): void
    {
        if (!is_dir($this->cacheDir)) return;
        if (function_exists('removePath')) {
            removePath(rtrim($this->cacheDir, '/\\'), true);
        } else {
            foreach (glob($this->cacheDir . '*') ?: [] as $file) {
                if (is_file($file)) unlink($file);
            }
        }
    }

    // ── Private: download ─────────────────────────────────────────────────────

    /**
     * Fetch the CSS from $url, download every font file it references,
     * rewrite all url() values to local paths, and write the result atomically.
     */
    private function download(string $url, string $format, string $cssFile, string $key): bool
    {
        $ua  = $this->ua($url, $format);
        $css = $this->fetch($url, $ua);
        if ($css === null) return false;

        $base = $this->toUrl(''); // base URL for font files: .../cache/fonts/

        // Process @font-face blocks individually so filenames can carry a
        // human-readable prefix derived from font-family + font-weight.
        // Example: Inter_400_<md5>.woff2, Inter_100_900_<md5>.woff2 (variable font)
        $css = (string)preg_replace_callback(
            '/@font-face\s*\{([^}]+)\}/is',
            function (array $blockMatch) use ($base, $ua): string {
                $inner = $blockMatch[1];

                // Extract font-family and font-weight to build filename prefix
                $family = '';
                $weight = '';
                if (preg_match('/font-family\s*:\s*["\']?([^"\';\r\n]+)["\']?\s*;/i', $inner, $fm)) {
                    $family = trim($fm[1]);
                }
                if (preg_match('/font-weight\s*:\s*([^;\r\n]+)\s*;/i', $inner, $wm)) {
                    $weight = trim($wm[1]);
                }

                // Sanitize: alphanumeric only, spaces/hyphens → underscore
                $prefix = '';
                if ($family !== '') {
                    $fam    = preg_replace('/[^a-zA-Z0-9]+/', '_', $family);
                    $wgt    = preg_replace('/[^a-zA-Z0-9]+/', '_', $weight);
                    $prefix = rtrim($fam . '_' . $wgt, '_') . '_';
                }

                // Download each font file url() within this block
                $newInner = (string)preg_replace_callback(
                    '/url\((["\']?)(https?:\/\/[^\)"\']+)\1\)/i',
                    function (array $m) use ($prefix, $base, $ua): string {
                        $fontUrl  = $m[2];
                        $quote    = $m[1];
                        $ext      = strtolower(pathinfo((string)strtok($fontUrl, '?'), PATHINFO_EXTENSION)) ?: 'woff2';
                        $filename = $prefix . md5($fontUrl) . '.' . $ext;
                        $path     = $this->cacheDir . $filename;

                        if (!is_file($path)) {
                            $data = $this->fetch($fontUrl, $ua);
                            if ($data !== null) {
                                $tmp = $path . '.tmp.' . getmypid();
                                if (file_put_contents($tmp, $data, LOCK_EX) !== false) {
                                    rename($tmp, $path);
                                } else {
                                    @unlink($tmp);
                                }
                            }
                        }

                        // Keep original CDN URL if download failed —
                        // font still loads, only the local-cache benefit is lost.
                        return is_file($path)
                            ? 'url(' . $quote . $base . $filename . $quote . ')'
                            : $m[0];
                    },
                    $inner
                );

                return '@font-face {' . $newInner . '}';
            },
            $css
        );

        // Atomic write: temp file → rename
        $tmp = $cssFile . '.tmp.' . getmypid();
        if (file_put_contents($tmp, $css, LOCK_EX) !== false && rename($tmp, $cssFile)) {
            return true;
        }
        @unlink($tmp);
        return false;
    }

    // ── Private: alias CSS generation ────────────────────────────────────────

    /**
     * Parse the cached CSS for @font-face blocks and produce a copy where
     * every font-family declaration is replaced with the requested alias.
     *
     * The browser deduplicates font downloads by URL — each font file is fetched
     * only once even when referenced under both its original name and the alias.
     *
     * Example output for alias = 'MainFont':
     *   @font-face { font-family: 'MainFont'; font-weight: 400; src: url(…); }
     *   @font-face { font-family: 'MainFont'; font-weight: 700; src: url(…); }
     */
    private function buildAliasCss(string $cssFile, string $alias): string
    {
        $css = file_get_contents($cssFile);
        if ($css === false || $css === '') return '';

        $escaped = str_replace("'", "\\'", $alias);
        $out     = '';

        preg_match_all('/@font-face\s*\{([^}]+)\}/is', $css, $m);
        foreach ($m[1] as $block) {
            $aliased = (string)preg_replace(
                '/font-family\s*:\s*["\']?[^"\';\n]+["\']?\s*;/i',
                "font-family: '$escaped';",
                $block,
                1
            );
            $out .= "@font-face {\n" . $aliased . "}\n";
        }

        return $out;
    }

    // ── Private: helpers ──────────────────────────────────────────────────────

    /** Deterministic cache key for a URL + format combination. */
    private function key(string $url, string $format): string
    {
        return md5($url . '|' . $format);
    }

    /**
     * Select the correct User-Agent for this URL.
     * Only UA-sensitive providers (Google, Bunny) need a specific UA string.
     * All others get a neutral browser UA.
     */
    private function ua(string $url, string $format): string
    {
        foreach (self::UA_SENSITIVE as $domain) {
            if (stripos($url, $domain) !== false) {
                return self::USER_AGENTS[$format] ?? self::USER_AGENTS['woff2'];
            }
        }
        return 'Mozilla/5.0 (compatible; WBCE-CMS/1.x; +https://wbce.org)';
    }

    /** Convert a filename in cacheDir to its public URL. */
    private function toUrl(string $file): string
    {
        $dir  = str_replace('\\', '/', rtrim($this->cacheDir, '/\\'));
        $base = str_replace('\\', '/', rtrim($this->wbPath, '/'));
        $rel  = ($base !== '' && str_starts_with($dir, $base))
            ? ltrim(substr($dir, strlen($base)), '/')
            : $dir;
        return rtrim($this->wbUrl, '/') . '/' . rtrim($rel, '/') . '/' . $file;
    }

    /** Minimal cURL fetch — returns body on HTTP 200, null otherwise. */
    private function fetch(string $url, string $userAgent): ?string
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT      => $userAgent,
            CURLOPT_URL            => $url,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        $result = curl_exec($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return ($status === 200 && is_string($result) && $result !== '') ? $result : null;
    }
}
