<?php
/**
 * Asset Optimizer — info.php
 *
 * Admin tool for framework/Assets/AssetQueue.php: minification / cache-busting
 * switches, cache status (savings, bundle sources), web-font cache and health
 * checks.
 *
 * @category   admintool
 * @package    asset_optimizer
 * @author     Christian M. Stefan · www.wbEasy.de
 * @license    http://www.gnu.org/licenses/gpl.html
 * @platform   WBCE 1.7.x
 */

if (!defined('WB_PATH')) {
    die('Cannot access this file directly');
}

$module_directory   = 'asset_optimizer';
$module_name        = 'AssetQueue Optimizer';
$module_function    = 'tool';
$module_version     = '1.0.0';
$module_platform    = '1.7.0';
$module_author      = 'Christian M. Stefan';
$module_license     = 'GNU General Public License';
$module_description = 'Manage WBCE\'s asset pipeline (framework/Assets/AssetQueue.php): CSS/JS minification, bundling and browser cache-busting, plus a cache-status view with per-bundle byte savings and source lists, the self-hosted web-font cache, and health checks.';
$module_icon        = 'fa fa-file-code-o';
$module_level       = 'core';

/**
 * DEVELOPMENT HISTORY (Change Log):
 *
 * v.1.0.0 2026-09-09 Christian M. Stefan
 *         [+] Initial release. Three tabs on the cp_chrome / cp_theme design
 *             system:
 *             - Settings: MINIFY_CSS / MINIFY_JS / MINIFY_USE_SUFFIX /
 *               ASSETS_MINIFY_DEBUG / MINIFY_ASSETS_DIR /
 *               ASSET_QUEUE_DEBUG written to var/config_constants.ini.php
 *               (flipping any of the three minify constants rewrites the asset
 *               cache); OPF_ASSETS_CACHE_BUSTING(_BE) written to the settings
 *               table (the same rows the Outputfilter Dashboard "Assets Cache
 *               Busting" filter uses). The backend cache-busting switch follows
 *               the front-end one (forced on + read-only) while that is on. The
 *               cache-directory field shows the resolved path read-only until
 *               "Set a custom directory" is clicked. Any constant pinned in
 *               config.php is shown read-only with a notice.
 *             - Cache & status: byte savings (original / minified / gzip est.),
 *               per-bundle source lists from the new .meta.json sidecar with a
 *               "source deleted" flag, the cache/fonts/ summary, clear-cache
 *               actions and a health-check list.
 *             - Documentation: the AssetQueue docs (moved here from
 *               framework/Assets/ into docs/) rendered inline in the backend
 *               via MarkdownWbce's new MdReaderHelper::renderForEmbed(), with
 *               cp-subtabs (Guide / Assets in templates / Web fonts /
 *               Full reference), a sticky in-page TOC (parked below the theme's
 *               fixed header via its scroll-padding-top / a measured
 *               fixed-element bottom) and highlight.js syntax highlighting
 *               (MdReaderHelper::highlightAssets(), same as the reader popup).
 *               Falls back to the on-disk path when MarkdownWbce is absent.
 *               docs/ holds TOOL_GUIDE.md (plain-language, no code),
 *               ASSETS_TUTORIAL.md, FONTS_TUTORIAL.md and ASSETS_REFERENCE.md,
 *               each with an EN base and a *_DE.md German sibling (served by
 *               LANGUAGE, EN base as fallback).
 *         [+] i18n: EN + DE complete; NL / PL / NO / FR / IT / ES / RU cover the
 *             visible labels (help paragraphs fall back to EN).
 *         [c] Core: AssetQueue debug constant settled on ASSETS_MINIFY_DEBUG
 *             (ASSET_MINIFY_DEBUG / MINIFY_ASSETS_DEBUG still accepted);
 *             resolveUrl() now honours OPF_ASSETS_CACHE_BUSTING_BE for backend
 *             requests; buildCombined() writes a <bundle>.meta.json sidecar
 *             (identifier / type / minified / built / bytes / sources) that
 *             powers the bundle inspector. cacheBustingEnabled() for a backend
 *             request is OPF_ASSETS_CACHE_BUSTING || OPF_ASSETS_CACHE_BUSTING_BE
 *             (FE busting on => backend always busts). The admin console-error
 *             diagnostics moved from WBCE_DEBUG to a dedicated ASSET_QUEUE_DEBUG
 *             constant (WBCE_DEBUG still implies it).
 */
