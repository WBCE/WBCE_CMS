<?php
/**
 * @file       functions.php
 * @category   admintool
 * @package    addon_monitor
 * @author     Christian M. Stefan (https://www.wbeasy.de)
 * @license    http://www.gnu.org/licenses/gpl.html
 * @platform   WBCE CMS 1.7.0
 *
 * PDO-compatible rewrite. All queries use prepared statements and
 * ANSI-SQL CASE WHEN instead of MySQL-only IF() — SQLite compatible.
 */

defined('WB_PATH') or die("Cannot access this file directly");

// ── Helper ────────────────────────────────────────────────────────────────────

/**
 * Parse the comma-separated `function` field into a clean $scope array.
 * Filters to only recognised values via $whitelist (if provided).
 *
 * @param  string   $raw        Raw value from addons.function column
 * @param  string[] $whitelist  Optional whitelist; empty = return all
 * @return string[]
 */
function parseAddonScope(string $raw, array $whitelist = []): array
{
    $parts = array_filter(array_map('trim', explode(',', $raw)));
    return $whitelist
        ? array_values(array_intersect($parts, $whitelist))
        : array_values($parts);
}

// ── Modules ───────────────────────────────────────────────────────────────────

function getModulesArray(): array
{
    global $database;

    $whitelist = ['page', 'tool', 'snippet', 'wysiwyg', 'preinit', 'initialize'];

    $aAddons = [
        'addons'               => [],
        'count_tools'          => 0,
        'count_snippets'       => 0,
        'count_pagemodules'    => 0,
        'count_wysiwygeditors' => 0,
        'default_wysiwyg'      => $database->fetchValue(
            "SELECT `value` FROM `{TP}settings` WHERE `name` = ?",
            ['wysiwyg_editor']
        ),
    ];

    // CASE WHEN replaces MySQL-only IF() — works on MySQL and SQLite
    $rows = $database->fetchAll(
        "SELECT DISTINCT a.*,
                CASE WHEN s.module IS NOT NULL THEN 'Y' ELSE 'N' END AS active
         FROM `{TP}addons` a
         LEFT JOIN `{TP}sections` s ON a.directory = s.module
         WHERE a.`type` = 'module'"
    );

    $langFilePattern = WB_PATH . '/modules/%s/languages/' . LANGUAGE . '.php';
    $moduleBase      = basename(dirname(__FILE__));

    foreach ($rows as $aRec) {

        // Full scope array — all recognised function values for this addon
        $aRec['scope'] = parseAddonScope($aRec['function'], $whitelist);

        if (empty($aRec['scope'])) continue;

        foreach ($aRec['scope'] as $function) {

            $rec           = $aRec;
            $rec['function'] = $function; // single value for this iteration

            switch ($function) {
                case 'page':
                    $aAddons['count_pagemodules']++;
                    if ($rec['active'] === 'Y') {
                        $rec['active_sections'] = [];
                        $sections = $database->fetchAll(
                            "SELECT `section_id`, `page_id` FROM `{TP}sections` WHERE `module` = ?",
                            [$rec['directory']]
                        );
                        foreach ($sections as $s) {
                            $rec['active_sections'][$s['section_id']] = $s['page_id'];
                        }
                    }
                    break;

                case 'tool':
                    $aAddons['count_tools']++;
                    $iconFile    = WB_PATH . '/modules/' . $rec['directory'] . '/tool_icon.png';
                    $rec['icon'] = is_readable($iconFile)
                        ? '../../modules/' . $rec['directory'] . '/tool_icon.png'
                        : "../../modules/$moduleBase/icons/tool.png";
                    break;

                case 'snippet':
                    $aAddons['count_snippets']++;
                    break;

                case 'wysiwyg':
                    $aAddons['count_wysiwygeditors']++;
                    break;
            }

            // Icon for page modules and snippets
            if ($function === 'page' || $function === 'snippet') {
                $iconRel     = '/modules/' . $rec['directory'] . '/addon_icon.png';
                $fallback    = $function === 'snippet' ? 'snippet' : 'page_module';
                $rec['icon'] = is_readable(WB_PATH . $iconRel)
                    ? '../..' . $iconRel
                    : "../../modules/$moduleBase/icons/$fallback.png";
            }

            // Language-file description override
            $langFile = sprintf($langFilePattern, $rec['directory']);
            if (is_readable($langFile)) {
                require_once $langFile;
                if (!empty($module_description)) {
                    $rec['description'] = $module_description;
                    unset($module_description);
                }
            }

            $aAddons['addons'][] = $rec;
        }
    }

    return $aAddons;
}

// ── Templates ─────────────────────────────────────────────────────────────────

function getTemplatesArray(): array
{
    global $database;

    $aAddons = [
        'addons'              => [],
        'count_pagetemplates' => 0,
        'count_acpthemes'     => 0,
        'default_acp'         => $database->fetchValue(
            "SELECT `value` FROM `{TP}settings` WHERE `name` = ?",
            ['default_theme']
        ),
    ];

    $moduleBase = basename(dirname(__FILE__));

    $rows = $database->fetchAll(
        "SELECT DISTINCT a.*,
                CASE WHEN p.template IS NOT NULL THEN 'Y' ELSE 'N' END AS active
         FROM `{TP}addons` a
         LEFT JOIN `{TP}pages` p ON a.directory = p.template
         WHERE a.`function` IN ('theme', 'template')
         ORDER BY a.`function`, a.`name`"
    );

    foreach ($rows as $aRec) {

        $aRec['scope'] = parseAddonScope($aRec['function']);

        if ($aRec['function'] === 'template') {
            $aAddons['count_pagetemplates']++;
            if ($aRec['active'] === 'Y') {
                $pages = $database->fetchAll(
                    "SELECT `page_id` FROM `{TP}pages` WHERE `template` = ?",
                    [$aRec['directory']]
                );
                $aRec['active_pages'] = array_column($pages, 'page_id');
            }
        } else {
            $aAddons['count_acpthemes']++;
        }

        $iconRel     = '/templates/' . $aRec['directory'] . '/preview.jpg';
        $type        = ($aRec['function'] === 'theme') ? 'acp_theme' : 'page_template';
        $aRec['icon'] = is_readable(WB_PATH . $iconRel)
            ? '../..' . $iconRel
            : "../../modules/$moduleBase/icons/{$type}_preview.jpg";

        $aAddons['addons'][] = $aRec;
    }

    return $aAddons;
}

// ── Languages ─────────────────────────────────────────────────────────────────

function getLanguagesArray(): array
{
    global $database;

    $aAddons    = ['addons' => []];
    $moduleBase = basename(dirname(__FILE__));

    $rows = $database->fetchAll(
        "SELECT DISTINCT a.*,
                CASE WHEN p.language IS NOT NULL THEN 'Y' ELSE 'N' END AS active
         FROM `{TP}addons` a
         LEFT JOIN `{TP}pages` p ON a.directory = p.language
         WHERE a.`type` = 'language'"
    );

    foreach ($rows as $aRec) {

        $aRec['scope'] = parseAddonScope($aRec['function']);

        if ($aRec['active'] === 'Y') {
            $pages = $database->fetchAll(
                "SELECT `page_id` FROM `{TP}pages` WHERE `language` = ?",
                [$aRec['directory']]
            );
            $aRec['active_pages'] = array_column($pages, 'page_id');
        }

        $iconRel      = '/languages/' . strtolower($aRec['directory']) . '.png';
        $aRec['icon'] = is_readable(WB_PATH . $iconRel)
            ? '../..' . $iconRel
            : "../../modules/$moduleBase/icons/unknown.png";

        $aAddons['addons'][] = $aRec;
    }

    return $aAddons;
}

