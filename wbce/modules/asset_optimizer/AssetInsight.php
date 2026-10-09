<?php

declare(strict_types=1);

/**
 * Asset Optimizer — read side.
 *
 * Pure inspection of the AssetQueue configuration and cache. No output, no
 * writes. tool.php owns every state change (settings save, cache clear).
 *
 *  • constantSource() / constantValue() — where a managed constant is defined
 *    (config.php | ini | settings | default) and its current value.
 *  • configPinned() — managed constants pinned by a raw define() (usually
 *    config.php); the tool shows those read-only.
 *  • assetCache() — cache/assets/ contents: bundles (with the source list from
 *    the <bundle>.meta.json sidecar and a "source deleted" flag) and stand-alone
 *    minified files, each with byte size, gzip estimate and, for bundles, the
 *    original (pre-minify) size and the saving.
 *  • fontCache() — cache/fonts/ summary.
 *  • paths() — resolved cache directories + writability.
 *  • minifyLibraryPresent() — whether matthiasmullie/minify is in include/.
 *
 * @author  Christian M. Stefan · www.wbEasy.de
 * @license GNU GPL2
 */
final class AssetInsight
{
    /** Managed constants that live in var/config_constants.ini.php. */
    public const FILE_BASED = [
        'MINIFY_CSS',
        'MINIFY_JS',
        'MINIFY_USE_SUFFIX',
        'ASSETS_MINIFY_DEBUG',
        'MINIFY_ASSETS_DIR',
        'ASSET_QUEUE_DEBUG',   // AssetQueue's own console.error() diagnostics (admin only)
    ];

    /** Managed constants that live in the settings table (shared with OPF Dashboard). */
    public const DB_BASED = [
        'OPF_ASSETS_CACHE_BUSTING',
        'OPF_ASSETS_CACHE_BUSTING_BE',
    ];

    /** Historical spellings of ASSETS_MINIFY_DEBUG, still honoured by AssetQueue. */
    public const DEBUG_ALIASES = ['ASSET_MINIFY_DEBUG', 'MINIFY_ASSETS_DEBUG'];

    // ── Paths ────────────────────────────────────────────────────────────────

    public static function assetsDir(): string
    {
        if (defined('MINIFY_ASSETS_DIR') && MINIFY_ASSETS_DIR !== '') {
            return rtrim((string) MINIFY_ASSETS_DIR, '/\\') . '/';
        }
        return rtrim(WB_PATH, '/\\') . '/cache/assets/';
    }

    public static function fontsDir(): string
    {
        return rtrim(WB_PATH, '/\\') . '/cache/fonts/';
    }

    /**
     * @return array{assets:string, assets_rel:string, assets_writable:bool,
     *               fonts:string, fonts_rel:string, fonts_writable:bool,
     *               custom_dir:bool}
     */
    public static function paths(): array
    {
        $assets = self::assetsDir();
        $fonts  = self::fontsDir();
        return [
            'assets'          => $assets,
            'assets_rel'      => self::rel($assets),
            'assets_writable' => self::writable($assets),
            'fonts'           => $fonts,
            'fonts_rel'       => self::rel($fonts),
            'fonts_writable'  => self::writable($fonts),
            'custom_dir'      => defined('MINIFY_ASSETS_DIR') && MINIFY_ASSETS_DIR !== '',
        ];
    }

    public static function minifyLibraryPresent(): bool
    {
        $base = defined('INCLUDE_PATH') ? INCLUDE_PATH : (rtrim(WB_PATH, '/\\') . '/include');
        return is_file($base . '/matthiasmullie/minify/src/CSS.php')
            && is_file($base . '/matthiasmullie/minify/src/JS.php');
    }

    // ── Constant provenance ──────────────────────────────────────────────────

    /**
     * Where a managed constant currently gets its value from.
     *
     * @return 'config.php'|'ini'|'settings'|'default'
     */
    public static function constantSource(string $key): string
    {
        $sc   = self::showConstants();
        $key  = strtoupper($key);

        if (isset($sc['ini'][$key])) {
            return 'ini';
        }
        if (isset($sc['db'][$key])) {
            return 'settings';
        }
        if (defined($key) && isset($sc['code'][$key])) {
            return 'config.php';
        }
        // A constant defined but not tracked in any section — treat as a raw
        // define() we cannot manage from here.
        if (defined($key)) {
            return 'config.php';
        }
        return 'default';
    }

    /** Current effective value of a managed constant (null when undefined). */
    public static function constantValue(string $key): mixed
    {
        return defined($key) ? constant($key) : null;
    }

    /** Managed constants pinned by a raw define() (config.php) — shown read-only. */
    public static function configPinned(): array
    {
        $out = [];
        foreach ([...self::FILE_BASED, ...self::DB_BASED] as $key) {
            if (self::constantSource($key) === 'config.php') {
                $out[] = $key;
            }
        }
        return $out;
    }

    /** Any legacy ASSETS_MINIFY_DEBUG spelling that is currently defined+truthy. */
    public static function legacyDebugAlias(): ?string
    {
        foreach (self::DEBUG_ALIASES as $c) {
            if (defined($c) && constant($c)) {
                return $c;
            }
        }
        return null;
    }

    // ── Asset cache ──────────────────────────────────────────────────────────

    /**
     * @return array{
     *   bundles:list<array<string,mixed>>,
     *   files:list<array<string,mixed>>,
     *   totals:array{count:int, bytes:int, gzip:int, orig:int, saved_pct:?int, last_built:int},
     *   exists:bool
     * }
     */
    public static function assetCache(): array
    {
        $dir     = self::assetsDir();
        $bundles = [];
        $files   = [];

        if (is_dir($dir)) {
            foreach (glob($dir . '*') ?: [] as $path) {
                if (!is_file($path)) {
                    continue;
                }
                $base = basename($path);
                if (!preg_match('/\.(css|js)$/i', $base)) {
                    continue; // skip .hash, .meta.json, .tmp.*, index.php
                }
                $type = strtolower(pathinfo($base, PATHINFO_EXTENSION));
                $row  = [
                    'name'  => $base,
                    'type'  => $type,
                    'bytes' => (int) filesize($path),
                    'gzip'  => self::gzsize($path),
                    'mtime' => (int) filemtime($path),
                ];

                if (str_starts_with($base, 'combined_')) {
                    $meta = self::readMeta($path . '.meta.json');
                    $row['kind']     = 'bundle';
                    $row['minified'] = $meta['minified'] ?? str_contains($base, '.min.');
                    $row['built']    = $meta['built'] ?? $row['mtime'];
                    $row['sources']  = $meta['sources'] ?? null;
                    $row['orig']     = $meta['orig'] ?? null;
                    $row['missing']  = $meta['missing'] ?? 0;
                    $row['saved_pct'] = ($row['orig'] && $row['orig'] > 0)
                        ? (int) round((1 - $row['bytes'] / $row['orig']) * 100)
                        : null;
                    $bundles[] = $row;
                } else {
                    $row['kind']      = 'minified';
                    $row['built']     = $row['mtime'];
                    $row['sources']   = null;
                    $row['orig']      = null;
                    $row['saved_pct'] = null;
                    $files[] = $row;
                }
            }
        }

        usort($bundles, static fn ($a, $b) => $b['built'] <=> $a['built']);
        usort($files, static fn ($a, $b) => $b['built'] <=> $a['built']);

        $all       = array_merge($bundles, $files);
        $bytes     = array_sum(array_column($all, 'bytes'));
        $gzip      = array_sum(array_column($all, 'gzip'));
        $origKnown = array_sum(array_filter(array_column($bundles, 'orig')));
        $bundleBytes = array_sum(array_column($bundles, 'bytes'));
        $lastBuilt = $all ? max(array_column($all, 'built')) : 0;

        return [
            'bundles' => $bundles,
            'files'   => $files,
            'totals'  => [
                'count'      => count($all),
                'bytes'      => (int) $bytes,
                'gzip'       => (int) $gzip,
                'orig'       => (int) $origKnown,
                'saved_pct'  => ($origKnown > 0)
                    ? (int) round((1 - $bundleBytes / $origKnown) * 100)
                    : null,
                'last_built' => (int) $lastBuilt,
            ],
            'exists'  => is_dir($dir),
        ];
    }

    // ── Font cache ───────────────────────────────────────────────────────────

    /**
     * @return array{files:list<array{name:string,ext:string,bytes:int,mtime:int}>,
     *               count:int, bytes:int, exists:bool}
     */
    public static function fontCache(): array
    {
        $dir   = self::fontsDir();
        $files = [];

        if (is_dir($dir)) {
            foreach (glob($dir . '*') ?: [] as $path) {
                $base = basename($path);
                if (!is_file($path) || $base === 'index.php') {
                    continue;
                }
                $files[] = [
                    'name'  => $base,
                    'ext'   => strtolower(pathinfo($base, PATHINFO_EXTENSION)) ?: '?',
                    'bytes' => (int) filesize($path),
                    'mtime' => (int) filemtime($path),
                ];
            }
        }

        usort($files, static fn ($a, $b) => strcmp($a['name'], $b['name']));

        return [
            'files'  => $files,
            'count'  => count($files),
            'bytes'  => (int) array_sum(array_column($files, 'bytes')),
            'exists' => is_dir($dir),
        ];
    }

    // ── internals ────────────────────────────────────────────────────────────

    /** @return array{ini:array<string,mixed>, db:array<string,mixed>, code:array<string,mixed>} */
    private static function showConstants(): array
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }
        $sc = class_exists('Settings') ? Settings::showConstants() : [];
        $up = static function (array $a): array {
            $out = [];
            foreach ($a as $k => $v) {
                $out[strtoupper((string) $k)] = $v;
            }
            return $out;
        };
        return $cache = [
            'ini'  => $up($sc['file_based_settings'] ?? []),
            'db'   => $up($sc['from_db'] ?? []),
            'code' => $up($sc['from_code'] ?? []),
        ];
    }

    /**
     * Read a <bundle>.meta.json sidecar and fold in a live "source deleted"
     * check + the original (summed source) byte size.
     *
     * @return array{minified:bool, built:int, sources:list<array<string,mixed>>, orig:int, missing:int}|array{}
     */
    private static function readMeta(string $file): array
    {
        if (!is_file($file)) {
            return [];
        }
        $data = json_decode((string) file_get_contents($file), true);
        if (!is_array($data) || !isset($data['sources']) || !is_array($data['sources'])) {
            return [];
        }

        $wb      = str_replace('\\', '/', rtrim(WB_PATH, '/\\'));
        $sources = [];
        $orig    = 0;
        $missing = 0;
        foreach ($data['sources'] as $s) {
            $path = isset($s['path']) ? (string) $s['path'] : '';
            $gone = $path === '' || !is_file($path);
            $bytes = (int) ($s['bytes'] ?? 0);
            $orig += $bytes;
            if ($gone) {
                $missing++;
            }
            // Prefer a clean repo-relative label; fall back to the raw ref.
            $ref  = str_replace('\\', '/', (string) ($s['ref'] ?? '?'));
            $disp = $path !== '' && str_starts_with(str_replace('\\', '/', $path), $wb)
                ? ltrim(substr(str_replace('\\', '/', $path), strlen($wb)), '/')
                : (str_starts_with($ref, $wb) ? ltrim(substr($ref, strlen($wb)), '/') : $ref);
            $sources[] = [
                'ref'     => $disp,
                'bytes'   => $bytes,
                'mtime'   => (int) ($s['mtime'] ?? 0),
                'missing' => $gone,
            ];
        }

        return [
            'minified' => (bool) ($data['minified'] ?? false),
            'built'    => (int) ($data['built'] ?? 0),
            'sources'  => $sources,
            'orig'     => $orig,
            'missing'  => $missing,
        ];
    }

    private static function gzsize(string $path): int
    {
        $raw = @file_get_contents($path);
        if ($raw === false || $raw === '') {
            return 0;
        }
        $gz = @gzencode($raw, 6);
        return $gz === false ? 0 : strlen($gz);
    }

    private static function writable(string $dir): bool
    {
        return is_dir($dir) ? is_writable($dir) : is_writable(dirname(rtrim($dir, '/\\')));
    }

    private static function rel(string $path): string
    {
        $wb = str_replace('\\', '/', rtrim(WB_PATH, '/\\'));
        $p  = str_replace('\\', '/', rtrim($path, '/\\'));
        return str_starts_with($p, $wb) ? ltrim(substr($p, strlen($wb)), '/') . '/' : $p . '/';
    }
}
