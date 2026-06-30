<?php

/*
 * @category   WBCE CMS — Framework
 * @package    framework
 * @author     Christian M. Stefan  (https://www.wbEasy.de)
 * @copyright  2025-2026 Christian M. Stefan
 * @copyright  2026 WBCE CMS Project
 * @license    GNU/GPL v2 https://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * PageTree — builds, annotates, and prepares the admin page tree for Twig.
 *
 * QUICK START:
 *
 *   $tree = PageTree::load($admin);
 *   echo $twig->render('pages_pageTree.twig', ['pages' => $tree]);
 *
 * STEP BY STEP (when you need an intermediate result):
 *
 *   $data = PageTree::query();
 *   $tree = PageTree::build($data);
 *   $tree = PageTree::applyPermissions($tree, $admin);
 *
 * COMBOBOX (parent-page <select>):
 *   $flat = PageTree::pageTreeCombobox($tree, $currentPageId, $selectedParentId);
 *
 * REORDER (AJAX drag-drop):
 *   PageTree::reorder(null, $_POST['page_id']);
 *
 * MOVE UP / DOWN:
 *   PageTree::movePage(null, $pageId, 'up');
 *
 * ── DATA SHAPE ──────────────────────────────────────────────────────────────
 *
 * Every node produced by build() carries:
 *
 *   page_id, parent, root_parent, level, menu,
 *   menu_title, page_title, link, visibility, position, siblings,
 *   language, page_trail, description, keywords,
 *   admin_groups, admin_users, viewing_groups, viewing_users,
 *   section_count, has_menu_link,
 *   publish_state   — 'none' | 'active' | 'inactive'
 *   children        — nested child nodes, absent on leaf nodes
 *
 * Set by applyPermissions():
 *   pageIDKEY, frontend_link, can_modify,
 *   canModifyPage, canModifySettings, canManageSections,
 *   canDeleteAndModify, canAddChild,
 *   pageIsMovable, canMoveUp, canMoveDown,
 *   sectionCase  — 'noclock' | 'clock' | 'clock_red' | 'menu_link'
 */
class PageTree
{
    // ── 0. CONVENIENCE ───────────────────────────────────────────────────────

    /**
     * All three stages in one call.
     * Uses $GLOBALS['admin'] + $GLOBALS['database'], reads PAGE_TRASH automatically.
     *
     * @param  object|null   $admin  Defaults to $GLOBALS['admin']
     * @param  Database|null $db     Defaults to $GLOBALS['database']
     * @return array  Ready-to-render tree
     */
    public static function load(?object $admin = null, ?Database $db = null): array
    {
        $admin ??= $GLOBALS['admin'];
        $data   = self::query($db);
        $tree   = self::build($data);
        return self::applyPermissions($tree, $admin);
    }

    // ── 1. QUERY ─────────────────────────────────────────────────────────────

    /**
     * Fetch all data for the tree in two queries (pages + sections).
     *
     * $db defaults to $GLOBALS['database'].
     * $includeDeleted defaults to the PAGE_TRASH === 'inline' constant.
     *
     * @param  Database|null $db
     * @param  bool|null     $includeDeleted  null = read PAGE_TRASH automatically
     * @return array{pages: array, sections: array}
     */
    public static function query(?Database $db = null, ?bool $includeDeleted = null): array
    {
        $db             ??= $GLOBALS['database'];
        $includeDeleted ??= (defined('PAGE_TRASH') && PAGE_TRASH === 'inline');
        $where            = $includeDeleted ? '' : "WHERE p.`visibility` <> 'deleted'";
        $pageCodeCol      = self::isPageCodeUsed($db) ? ', p.`page_code`' : '';

        $pages = $db->fetchAll(
            "SELECT p.`page_id`, p.`parent`, p.`root_parent`, p.`level`, p.`menu`,
                    p.`menu_title`, p.`page_title`, p.`link`, p.`visibility`,
                    p.`position`, p.`page_trail`, p.`language`,
                    p.`admin_groups`, p.`admin_users`,
                    p.`viewing_groups`, p.`viewing_users`,
                    p.`description`, p.`keywords`{$pageCodeCol},
                    (SELECT MAX(`position`)
                     FROM `{TP}pages`
                     WHERE `parent` = p.`parent`) AS siblings
             FROM `{TP}pages` p
             {$where}
             ORDER BY p.`position` ASC"
        );

        $sections = $db->fetchAll(
            'SELECT `page_id`,
                    COUNT(`section_id`)              AS section_count,
                    MAX(`publ_start` + `publ_end`)   AS has_scheduling,
                    MAX(`publ_start`)                AS max_start,
                    MAX(`publ_end`)                  AS max_end,
                    GROUP_CONCAT(`module`)           AS modules
             FROM `{TP}sections`
             GROUP BY `page_id`'
        );

        $sectionMap = [];
        foreach ($sections as $s) {
            $pid = (int)$s['page_id'];
            $sectionMap[$pid] = [
                'section_count'  => (int)$s['section_count'],
                'has_scheduling' => (int)$s['has_scheduling'],
                'max_start'      => (int)$s['max_start'],
                'max_end'        => (int)$s['max_end'],
                'has_menu_link'  => str_contains((string)$s['modules'], 'menu_link'),
            ];
        }

        return ['pages' => $pages, 'sections' => $sectionMap];
    }

    // ── 2. BUILD ─────────────────────────────────────────────────────────────

    /**
     * Turn flat page rows + section map into a nested tree.
     * One pass, zero recursion, O(n) via the reference trick.
     *
     * All can* flags default to false — call applyPermissions() next.
     *
     * @param  array{pages: array, sections: array} $data  Output of query()
     * @return array  Top-level nodes (parent = 0) with children embedded
     */
    public static function build(array $data): array
    {
        $rows       = $data['pages'];
        $sectionMap = $data['sections'];
        $now        = time();

        $refs  = [];
        $roots = [];

        foreach ($rows as $row) {
            $pid     = (int)$row['page_id'];
            $thisref = &$refs[$pid];

            $thisref['page_id']        = $pid;
            $thisref['parent']         = (int)$row['parent'];
            $thisref['root_parent']    = (int)($row['root_parent'] ?? 0);
            $thisref['level']          = (int)$row['level'];
            $thisref['menu']           = (int)$row['menu'];
            $thisref['menu_title']     = $row['menu_title'];
            $thisref['page_title']     = $row['page_title'];
            $thisref['link']           = $row['link'];
            $thisref['description']    = $row['description']    ?? '';
            $thisref['keywords']       = $row['keywords']       ?? '';
            $thisref['language']       = $row['language']       ?? 'EN';
            $thisref['page_trail']     = $row['page_trail']     ?? '';
            $thisref['visibility']     = $row['visibility'];
            $thisref['position']       = (int)$row['position'];
            $thisref['siblings']       = (int)($row['siblings'] ?? 1);
            $thisref['admin_groups']   = $row['admin_groups']   ?? '';
            $thisref['admin_users']    = $row['admin_users']    ?? '';
            $thisref['viewing_groups'] = $row['viewing_groups'] ?? '';
            $thisref['viewing_users']  = $row['viewing_users']  ?? '';

            if (isset($row['page_code'])) {
                $thisref['page_code'] = $row['page_code'];
            }

            $sec = $sectionMap[$pid] ?? null;
            $thisref['section_count']  = (int)($sec['section_count'] ?? 0);
            $thisref['has_menu_link']  = (bool)($sec['has_menu_link'] ?? false);
            $thisref['publish_state']  = self::publishState($sec, $now);

            // Permission flags — false until applyPermissions() runs
            $thisref['can_modify']         = false;
            $thisref['canModifyPage']      = false;
            $thisref['canModifySettings']  = false;
            $thisref['canManageSections']  = false;
            $thisref['canDeleteAndModify'] = false;
            $thisref['canAddChild']        = false;
            $thisref['pageIsMovable']      = false;
            $thisref['canMoveUp']          = false;
            $thisref['canMoveDown']        = false;
            $thisref['sectionCase']        = 'noclock';
            $thisref['pageIDKEY']          = '';
            $thisref['frontend_link']      = '';

            if ($thisref['parent'] === 0) {
                $roots[$pid] = &$thisref;
            } else {
                $refs[$thisref['parent']]['children'][$pid] = &$thisref;
            }

            unset($thisref);
        }

        return $roots;
    }

    /**
     * Derive publish_state from aggregated section scheduling data.
     *
     *   'none'     — no scheduling on any section                → sectionCase: noclock
     *   'active'   — scheduling set and currently in window      → sectionCase: clock
     *   'inactive' — scheduling set but outside window           → sectionCase: clock_red
     */
    private static function publishState(?array $sec, int $now): string
    {
        if (!$sec || $sec['has_scheduling'] === 0) return 'none';
        $afterStart = ($sec['max_start'] === 0 || $now >= $sec['max_start']);
        $beforeEnd  = ($sec['max_end']   === 0 || $now <= $sec['max_end']);
        return ($afterStart && $beforeEnd) ? 'active' : 'inactive';
    }

    // ── 3. APPLY PERMISSIONS ─────────────────────────────────────────────────

    /**
     * Walk the tree and set all can* flags, sectionCase, pageIDKEY,
     * and frontend_link on every node.
     *
     * @param  array   $tree   Output of build()
     * @param  object  $admin  WBCE admin/wb object
     * @return array           Same tree, flags populated (passed by value — tree is returned)
     */
    public static function applyPermissions(array $tree, object $admin): array
    {
        $ctx = [
            'manageSections'  => defined('MANAGE_SECTIONS')
                                  && (MANAGE_SECTIONS === 'enabled' || MANAGE_SECTIONS == 1),
            'pageTrashInline' => defined('PAGE_TRASH') && PAGE_TRASH === 'inline',
            'levelLimit'      => defined('PAGE_LEVEL_LIMIT') ? (int)PAGE_LEVEL_LIMIT : 0,
            'pModify'         => (bool)$admin->get_permission('pages_modify'),
            'pSettings'       => (bool)$admin->get_permission('pages_settings'),
            'pDelete'         => (bool)$admin->get_permission('pages_delete'),
            'pAdd'            => (bool)$admin->get_permission('pages_add'),
            'userGroups'      => $admin->get_groups_id(),
            'userId'          => $admin->get_user_id(),
            'pagesDir'        => defined('PAGES_DIRECTORY') ? PAGES_DIRECTORY : '',
            'pageExt'         => defined('PAGE_EXTENSION')  ? PAGE_EXTENSION  : '',
        ];

        self::walkPermissions($tree, $admin, $ctx);
        return $tree;
    }

    private static function walkPermissions(array &$nodes, object $admin, array $ctx): void
    {
        foreach ($nodes as &$p) {
            $deleted = ($p['visibility'] === 'deleted');

            // Per-page admin group / user check
            $aAdminGroups = explode(',', str_replace('_', '', $p['admin_groups']));
            $aAdminUsers  = explode(',', str_replace('_', '', $p['admin_users']));

            $bInGroup = false;
            foreach ($ctx['userGroups'] as $gid) {
                if (in_array((string)$gid, $aAdminGroups, true)) {
                    $bInGroup = true;
                    break;
                }
            }

            if ($bInGroup || is_numeric(array_search($ctx['userId'], $aAdminUsers))) {
                $p['can_modify'] = $deleted ? $ctx['pageTrashInline'] : true;
            } else {
                $p['can_modify'] = false;
            }

            $cm = $p['can_modify'];

            $p['canModifyPage']      = $ctx['pModify']   && $cm;
            $p['canModifySettings']  = $ctx['pSettings'] && $cm;
            $p['canManageSections']  = $ctx['manageSections'] && $ctx['pModify'] && $cm;
            $p['canDeleteAndModify'] = $ctx['pDelete']   && $cm;
            $p['canAddChild']        = $ctx['pAdd'] && $cm && !$deleted
                                       && ($ctx['levelLimit'] === 0 || $p['level'] + 1 < $ctx['levelLimit']);
            $p['pageIsMovable']      = $ctx['pSettings'] && $cm && $p['siblings'] > 1;
            $p['canMoveUp']          = $p['pageIsMovable'] && $p['position'] > 1;
            $p['canMoveDown']        = $p['pageIsMovable'] && $p['position'] < $p['siblings'];

            $p['pageIDKEY']     = $admin->getIDKEY($p['page_id']);
            $p['frontend_link'] = $ctx['pagesDir'] . $p['link'] . $ctx['pageExt'];

            if ($p['has_menu_link']) {
                $p['sectionCase'] = 'menu_link';
            } elseif ($p['publish_state'] === 'active') {
                $p['sectionCase'] = 'clock';
            } elseif ($p['publish_state'] === 'inactive') {
                $p['sectionCase'] = 'clock_red';
            } else {
                $p['sectionCase'] = 'noclock';
            }

            if (!empty($p['children'])) {
                self::walkPermissions($p['children'], $admin, $ctx);
            }
        }
        unset($p);
    }

    // ── 4. REORDER ───────────────────────────────────────────────────────────

    /**
     * Update page positions after a drag-drop reorder.
     * Called from the AJAX reorder endpoint.
     *
     * @param  Database  $db
     * @param  array     $orderedIds  Ordered page_ids (e.g. from $_POST['page_id'])
     * @return array{success: bool, message: string}
     */
    public static function reorder(?Database $db, array $orderedIds): array
    {
        $db ??= $GLOBALS['database'];

        if (empty($orderedIds)) {
            return ['success' => false, 'message' => 'No page IDs received'];
        }

        $position = 1;
        foreach ($orderedIds as $rawId) {
            $pid = (int)$rawId;
            if ($pid <= 0) continue;
            $db->query(
                'UPDATE `{TP}pages` SET `position` = ? WHERE `page_id` = ?',
                [$position, $pid]
            );
            $position++;
        }

        return $db->hasError()
            ? ['success' => false, 'message' => $db->getError()]
            : ['success' => true,  'message' => 'Order saved'];
    }

    // ── 5. MOVE PAGE ─────────────────────────────────────────────────────────

    /**
     * Swap a page's position with the adjacent sibling (up or down).
     *
     * @param  Database  $db
     * @param  int       $pageId
     * @param  string    $direction  'up' | 'down'
     * @return bool
     */
    public static function movePage(?Database $db, int $pageId, string $direction): bool
    {
        $db ??= $GLOBALS['database'];

        $row = $db->fetchRow(
            'SELECT `position`, `parent` FROM `{TP}pages` WHERE `page_id` = ?',
            [$pageId]
        );
        if (!$row) return false;

        $pos     = (int)$row['position'];
        $parent  = (int)$row['parent'];
        $swapPos = ($direction === 'up') ? $pos - 1 : $pos + 1;

        $swap = $db->fetchRow(
            'SELECT `page_id` FROM `{TP}pages` WHERE `parent` = ? AND `position` = ?',
            [$parent, $swapPos]
        );
        if (!$swap) return false;

        $db->query('UPDATE `{TP}pages` SET `position` = ? WHERE `page_id` = ?', [$swapPos, $pageId]);
        $db->query('UPDATE `{TP}pages` SET `position` = ? WHERE `page_id` = ?', [$pos, (int)$swap['page_id']]);

        return !$db->hasError();
    }

    // ── 6. COMBOBOX ──────────────────────────────────────────────────────────

    /**
     * Build a flat array for a parent-page <select> element.
     * Renders ASCII tree connectors to show hierarchy:
     *
     *   Start
     *   ├─ Sub
     *   │  ├─ SubSub
     *   │  └─ SubSub2
     *   ├─ Sub2
     *   └─ Sub3
     *
     * @param  array  $tree             Output of build() [+ applyPermissions()]
     * @param  int    $currentPageId    Exclude this page and its direct children
     * @param  int    $selectedParent   page_id to pre-select
     * @param  int    $levelLimit       Max level (0 = no limit); defaults to PAGE_LEVEL_LIMIT
     * @return array  Each item: [page_id, prefix, menu_title, page_title,
     *                            level, visibility, menu, language, selected, disabled]
     */
    public static function pageTreeCombobox(
        array $tree,
        int   $currentPageId  = 0,
        int   $selectedParent = 0,
        int   $levelLimit     = 0
    ): array {
        if ($levelLimit === 0 && defined('PAGE_LEVEL_LIMIT')) {
            $levelLimit = (int)PAGE_LEVEL_LIMIT;
        }
        $result = [];
        self::flattenWithConnectors($tree, $currentPageId, $selectedParent, $levelLimit, [], $result);
        return $result;
    }

    /**
     * Recursive helper for pageTreeCombobox.
     *
     * $continuing is a bool[] indexed by depth:
     *   true  → this level still has siblings below → draw │
     *   false → last sibling at this level          → draw a space
     */
    private static function flattenWithConnectors(
        array  $nodes,
        int    $excludeId,
        int    $selectedId,
        int    $levelLimit,
        array  $continuing,
        array  &$result
    ): void {
        $visible = array_values(
            array_filter($nodes, static function ($p) use ($levelLimit) {
                if (($p['visibility'] ?? '') === 'deleted') return false;
                if ($levelLimit > 0 && (int)$p['level'] + 1 >= $levelLimit) return false;
                return true;
            })
        );
        $last = count($visible) - 1;

        foreach ($visible as $i => $p) {
            $pid    = (int)$p['page_id'];
            $isLast = ($i === $last);

            $prefix = '';
            foreach ($continuing as $hasSiblingBelow) {
                $prefix .= $hasSiblingBelow ? '│' . str_repeat("\u{00A0}", 2) : str_repeat("\u{00A0}", 3);
            }
            if (!empty($continuing)) {
                $prefix .= $isLast ? '└─' . "\u{00A0}" : '├─' . "\u{00A0}";
            }

            $disabled = $excludeId > 0 && ($pid === $excludeId || (int)($p['parent'] ?? 0) === $excludeId);

            $result[] = [
                'page_id'    => $pid,
                'prefix'     => $prefix,
                'menu_title' => $p['menu_title'],
                'page_title' => $p['page_title'],
                'level'      => (int)$p['level'],
                'visibility' => $p['visibility'],
                'menu'       => (int)$p['menu'],
                'language'   => $p['language'] ?? 'EN',
                'selected'   => ($pid === $selectedId),
                'disabled'   => $disabled,
            ];

            if (!empty($p['children'])) {
                self::flattenWithConnectors(
                    $p['children'],
                    $excludeId,
                    $selectedId,
                    $levelLimit,
                    [...$continuing, !$isLast],
                    $result
                );
            }
        }
    }

    // ── 7. FLATTEN ───────────────────────────────────────────────────────────

    /**
     * Flatten the nested tree into a depth-ordered array.
     * Useful for breadcrumb lookups and full-tree iteration without recursion.
     *
     * @param  array  $tree
     * @param  array  $result  (internal recursion accumulator)
     * @return array
     */
    public static function flatten(array $tree, array $result = []): array
    {
        foreach ($tree as $node) {
            $children = $node['children'] ?? [];
            unset($node['children']);
            $result[] = $node;
            if ($children) {
                $result = self::flatten($children, $result);
            }
        }
        return $result;
    }

    // ── PRIVATE HELPERS ───────────────────────────────────────────────────────

    /**
     * Check whether mod_multilingual is installed and has added page_code
     * to the pages table.
     */
    private static function isPageCodeUsed(Database $db): bool
    {
        return defined('WB_PATH')
            && is_file(WB_PATH . '/modules/mod_multilingual/update_keys.php')
            && $db->fieldExists('{TP}pages', 'page_code');
    }
}
