<?php
/**
 * LinkResolver — resolves link tokens embedded in rendered content to real URLs.
 * 
 * @author     Christian M. Stefan
 * @copyright  Copyright (c) 2026 Christian M. Stefan
 * @copyright  Copyright (c) 2026 WBCE CMS Project
 * @license    GNU/GPL 2  https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Understands two token shapes:
 *   - `[keyword:NN]`    current format; `pagelink` is the built-in keyword for
 *                       core pages, anything else is a module keyword
 *   - `[wblinkNN]`      legacy, deprecated but still supported for existing content
 *
 * `pagelink` resolution goes through the core Wbce::pageLink() (see
 * framework/Wbce.php). Module keywords are resolved by a per-module
 * subclass of this very class — modules extend LinkResolver and override
 * linkItems()/resolve() in a file named exactly `LinkResolver.php` at the
 * root of their module directory. No opt-in marker, no {TP}addons query:
 * every module's provider file has the same name, so a filesystem glob on
 * `modules/*\/LinkResolver.php` finds all of them directly (same idea as
 * the `predb_*` filesystem-glob discovery in framework/initialize.php, just
 * matched on filename instead of directory-name prefix).
 *
 * One class serves both roles on purpose: the static side is the dispatcher,
 * the instance side is the contract each module subclass implements. A
 * module registering itself only has one file to add, named one specific
 * way — nothing to touch in info.php, nothing to keep in sync with the
 * addons table after an update.
 *
 * Used by modules/outputfilter_dashboard/plugins/core_outputfilters/opf_pagelink.php
 * at render time.
 */
class LinkResolver
{
    /** @var array<string, array{0: class-string<self>, 1: string}>|null keyword => [className, filePath] */
    private static ?array $providerClasses = null;

    public static function resolveContent(string $content): string
    {
        // Legacy [wblinkNN] — deprecated, still resolved as a plain pagelink
        // for content saved before [pagelink:NN] existed.
        $content = preg_replace_callback(
            '/\[wblink([0-9]+)\]/i',
            static fn(array $m): string => self::resolvePageLink((int) $m[1]) ?? $m[0],
            $content
        );

        // Current format: [keyword:id]. 
        // Core 'pagelink' itself is built in, 
        // everything else is looked up via module discovery.
        $content = preg_replace_callback(
            '/\[([a-z0-9_]+):([0-9]+)\]/i',
            static function (array $m): string {
                $keyword = strtolower($m[1]);
                $id      = (int) $m[2];
                $resolved = $keyword === 'pagelink'
                    ? self::resolvePageLink($id)
                    : self::resolveModuleItem($keyword, $id);
                return $resolved ?? $m[0]; // unresolved: leave the token visible, same as legacy behaviour
            },
            $content
        );

        return $content;
    }

    private static function resolvePageLink(int $pageId): ?string
    {
        $url = page_link($pageId);
        return $url !== '' ? $url : null;
    }

    private static function resolveModuleItem(string $keyword, int $itemId): ?string
    {
        return self::providerFor($keyword)?->resolve($itemId);
    }

    /**
     * Load and instantiate the module provider registered for $keyword, if any.
     */
    public static function providerFor(string $keyword): ?self
    {
        $providers = self::discoverProviders();
        if (!isset($providers[$keyword])) {
            return null;
        }

        [$class, $file] = $providers[$keyword];

        if (!class_exists($class)) {
            require_once $file;
        }

        if (!class_exists($class) || !is_subclass_of($class, self::class)) {
            return null;
        }

        return new $class();
    }

    /**
     * Every module opts in simply by having a `LinkResolver.php` at the root
     * of its module directory, defining a subclass named
     * "<StudlyCaseDir>LinkResolver" — no {TP}addons marker, no DB query.
     *
     * @return array<string, array{0: class-string<self>, 1: string}> keyword => [className, filePath]
     */
    public static function discoverProviders(): array
    {
        if (self::$providerClasses !== null) {
            return self::$providerClasses;
        }

        self::$providerClasses = [];

        $files = glob(WB_PATH . '/modules/*/LinkResolver.php') ?: [];

        foreach ($files as $file) {
            $dir = basename(dirname($file));
            self::$providerClasses[$dir] = [self::classNameFor($dir), $file];
        }

        return self::$providerClasses;
    }

    private static function classNameFor(string $dir): string
    {
        return str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $dir))) . 'LinkResolver';
    }

    // ---- Provider contract — overridden by module subclasses ----

    /**
     * All linkable items in one section of a page, e.g. the news posts
     * belonging to a "news" section. Used by tinymce_wbce's link picker.
     *
     * @return array<int, array{label: string, value: string}>
     */
    public function linkItems(int $sectionId, int $pageId): array
    {
        return [];
    }

    /**
     * Resolve a single item id (the NN in "[keyword:NN]") to a URL.
     */
    public function resolve(int $itemId): ?string
    {
        return null;
    }
}
