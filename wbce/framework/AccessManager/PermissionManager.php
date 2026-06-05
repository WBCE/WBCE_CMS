<?php
/**
 * PermissionManager — Manages group permissions for WBCE CMS
 * ────────────────────────────────────────────────────────────────────────────
 * 
 * @package    WBCE\AccessManager
 * @author     Christian M. Stefan  (https://www.wbEasy.de)
 * @copyright  2026 Christian M. Stefan
 * @copyright  2026 WBCE CMS Project
 * @since      WBCE 1.7.0
 *
 * Single point of access for reading, writing, and checking 
 * group permissions. 
 * 
 * Methods:
 *   getStructure()
 *   hasPermission()
 *   parseFromPost()
 *   getAddonList()
 *   getDefaults()
 */

declare(strict_types=1);

class PermissionManager
{
    private Database $db;

    /**
     * The permission tree defines the UI structure AND the POST-parsing logic.
     * 
     * Each top-level key is an area (shown in the Groups UI with an icon).
     * 'title'    → i18n key for the area label
     * 'children' → individual permission checkboxes within that area
     *
     * Meta-parents ('addons', 'access', 'preferences') are NOT displayed as 
     * areas — they are auto-derived from their children in parseFromPost().
     */
    private const PERMISSION_TREE = [
        'pages' => [
            'title'    => 'MENU:PAGES',
            'children' => [
                'pages_view'     => 'TEXT:VIEW',
                'pages_add'      => 'TEXT:ADD',
                'pages_add_l0'   => 'TEXT:ADD',
                'pages_settings' => 'TEXT:MODIFY_SETTINGS',
                'pages_modify'   => 'TEXT:MODIFY_CONTENT',
                'pages_intro'    => 'HEADING:MODIFY_INTRO_PAGE',
                'pages_delete'   => 'TEXT:DELETE',
            ],
        ],
        'media' => [
            'title'    => 'MENU:MEDIA',
            'children' => [
                'media_view'   => 'TEXT:VIEW',
                'media_upload' => 'TEXT:UPLOAD_FILES',
                'media_rename' => 'TEXT:RENAME',
                'media_delete' => 'TEXT:DELETE',
                'media_create' => 'TEXT:CREATE_FOLDER',
            ],
        ],
        'modules' => [
            'title'    => 'MENU:MODULES',
            'children' => [
                'modules_view'      => 'TEXT:VIEW',
                'modules_install'   => 'TEXT:INSTALL',
                'modules_uninstall' => 'TEXT:UNINSTALL',
            ],
        ],
        'templates' => [
            'title'    => 'MENU:TEMPLATES',
            'children' => [
                'templates_view'      => 'TEXT:VIEW',
                'templates_install'   => 'TEXT:INSTALL',
                'templates_uninstall' => 'TEXT:UNINSTALL',
            ],
        ],
        'languages' => [
            'title'    => 'MENU:LANGUAGES',
            'children' => [
                'languages_view'      => 'TEXT:VIEW',
                'languages_install'   => 'TEXT:INSTALL',
                'languages_uninstall' => 'TEXT:UNINSTALL',
            ],
        ],
        'settings' => [
            'title'    => 'MENU:SETTINGS',
            'children' => [
                'settings_basic'    => 'TEXT:BASIC',
                'settings_advanced' => 'TEXT:ADVANCED',
            ],
        ],
        'admintools' => [
            'title'    => 'MENU:ADMINTOOLS',
            'children' => [
                'admintools_settings' => 'TEXT:MODIFY_SETTINGS',
            ],
        ],
        'users' => [
            'title'    => 'MENU:USERS',
            'children' => [
                'users_view'   => 'TEXT:VIEW',
                'users_add'    => 'TEXT:ADD',
                'users_modify' => 'TEXT:MODIFY',
                'users_delete' => 'TEXT:DELETE',
            ],
        ],
        'groups' => [
            'title'    => 'MENU:GROUPS',
            'children' => [
                'groups_view'   => 'TEXT:VIEW',
                'groups_add'    => 'TEXT:ADD',
                'groups_modify' => 'TEXT:MODIFY',
                'groups_delete' => 'TEXT:DELETE',
            ],
        ],
    ];

    /**
     * Meta-parents are permission names that get auto-enabled when any 
     * of their constituent areas have at least one active child.
     * They are stored in the system_permissions string but NOT displayed 
     * as UI areas.
     */
    private const META_PARENTS = [
        'addons' => ['modules', 'templates', 'languages'],
        'access' => ['users', 'groups'],
    ];

    /**
     * Standalone meta-parents: enabled when their single child is set.
     * These are read from POST directly but not shown as areas.
     */
    private const STANDALONE_PERMISSIONS = [
        'preferences' => 'preferences_settings',
    ];

    /**
     * Areas that have associated addon permissions.
     * Maps area name → [addon function name(s), permission column].
     * Used by getAreaStates() to determine tri-state (none/partial/full).
     */
    private const ADDON_LINKED_AREAS = [
        'admintools' => ['functions' => ['tool'],               'column' => 'module_permissions'],
        'modules'    => ['functions' => ['page'],               'column' => 'module_permissions'],
        'templates'  => ['functions' => ['template', 'theme'],  'column' => 'template_permissions'],
    ];

    public function __construct()
    {
        global $database;
        $this->db = $database;
    }

    // =========================================================================
    //  READING PERMISSIONS
    // =========================================================================

    /**
     * Get the full permission structure for a group, ready for the Twig template.
     *
     * Each area includes:
     *   'checked'  => bool    (parent checkbox state)
     *   'state'    => string  'none' | 'partial' | 'full'
     *   'children' => [perm => [title, checked]]
     *
     * State is computed from child checkboxes only (not addon counts).
     * For overview icons that also consider addons, use getAreaStates().
     *
     * @param  int   $groupId  0 = new group (uses defaults)
     * @return array           Nested structure per area
     */
    public function getStructure(int $groupId = 0): array
    {
        $activePerms = ($groupId > 0)
            ? $this->getPermissionSet($groupId, 'system_permissions')
            : $this->getDefaults('system_permissions');

        $structure = [];

        foreach (self::PERMISSION_TREE as $area => $def) {
            $children = [];
            $checkedCount = 0;
            $totalCount   = count($def['children']);

            foreach ($def['children'] as $perm => $title) {
                $isChecked = in_array($perm, $activePerms, true);
                $children[$perm] = [
                    'title'   => $title,
                    'checked' => $isChecked,
                ];
                if ($isChecked) {
                    $checkedCount++;
                }
            }

            // Determine tri-state from children
            if ($checkedCount === 0) {
                $state = 'none';
            } elseif ($checkedCount === $totalCount) {
                $state = 'full';
            } else {
                $state = 'partial';
            }

            $structure[$area] = [
                'title'    => $def['title'],
                'checked'  => in_array($area, $activePerms, true),
                'state'    => $state,
                'children' => $children,
            ];
        }

        return $structure;
    }

    /**
     * Check whether a group has a specific permission.
     *
     * @param  int    $groupId
     * @param  string $permName  e.g. 'pages_add', 'media_view'
     * @param  string $column    DB column: 'system_permissions', 'module_permissions', 'template_permissions'
     * @return bool
     */
    public function hasPermission(int $groupId, string $permName, string $column = 'system_permissions'): bool
    {
        if ($groupId === 0) {
            $perms = $this->getDefaults($column);
        } else {
            $perms = $this->getPermissionSet($groupId, $column);
        }

        return in_array($permName, $perms, true);
    }

    /**
     * Get tri-state for all areas, considering both children AND addon counts.
     *
     * Used in the groups overview table for icon coloring.
     * Unlike getStructure(), this also factors in addon permissions:
     * e.g. admintools with all children checked but only 2/5 tools → 'partial'.
     *
     * Returns: [area => 'none'|'partial'|'full', 'preferences' => 'none'|'full']
     *
     * @param  int   $groupId
     * @return array  Keyed by area name
     */
    public function getAreaStates(int $groupId): array
    {
        $activePerms = $this->getPermissionSet($groupId, 'system_permissions');
        $states = [];

        foreach (self::PERMISSION_TREE as $area => $def) {
            $totalChildren   = count($def['children']);
            $checkedChildren = 0;

            foreach ($def['children'] as $perm => $title) {
                if (in_array($perm, $activePerms, true)) {
                    $checkedChildren++;
                }
            }

            // For addon-linked areas, also count addon permissions
            if (isset(self::ADDON_LINKED_AREAS[$area])) {
                $link = self::ADDON_LINKED_AREAS[$area];

                foreach ($link['functions'] as $function) {
                    $addonList   = $this->getAddonList($function, $groupId);
                    $totalAddons = count($addonList);
                    $checkedAddons = 0;

                    foreach ($addonList as $addon) {
                        if ($addon['checked']) {
                            $checkedAddons++;
                        }
                    }

                    $totalChildren   += $totalAddons;
                    $checkedChildren += $checkedAddons;
                }
            }

            // Determine state
            if ($checkedChildren === 0) {
                $states[$area] = 'none';
            } elseif ($checkedChildren === $totalChildren) {
                $states[$area] = 'full';
            } else {
                $states[$area] = 'partial';
            }
        }

        // Preferences: single permission, always full or none
        $states['preferences'] = in_array('preferences_settings', $activePerms, true)
            ? 'full'
            : 'none';

        return $states;
    }

    // === WRITING PERMISSIONS (from POST) =====================================

    /**
     * Parse all permission data from a POST request.
     *
     * Replaces the entire posted_permissions.php (174 lines).
     * Reads child permissions from POST, auto-enables parent areas,
     * auto-enables meta-parents, validates addon directories.
     *
     * @param  Admin  $admin   The Admin instance (for get_post())
     * @return array  Keys: 'system_permissions', 'module_permissions', 'template_permissions'
     *                Values: comma-separated strings ready for DB storage
     */
    public function parseFromPost(Admin $admin): array
    {
        $active = [];

        // --- System permissions: read children, auto-set parents ---
        foreach (self::PERMISSION_TREE as $area => $def) {
            $areaHasActive = false;

            foreach ($def['children'] as $perm => $title) {
                if ($admin->get_post($perm) == 1) {
                    $active[] = $perm;
                    $areaHasActive = true;
                }
            }

            // Special case: pages_add implied by pages_add_l0
            if ($area === 'pages'
                && $admin->get_post('pages_add') != 1
                && $admin->get_post('pages_add_l0') == 1
            ) {
                if (!in_array('pages_add', $active, true)) {
                    $active[] = 'pages_add';
                }
                $areaHasActive = true;
            }

            if ($areaHasActive) {
                $active[] = $area;
            }
        }

        // --- Meta-parents: auto-enable when constituent areas are active ---
        foreach (self::META_PARENTS as $meta => $areas) {
            foreach ($areas as $area) {
                if (in_array($area, $active, true)) {
                    $active[] = $meta;
                    break;
                }
            }
        }

        // --- Standalone permissions ---
        foreach (self::STANDALONE_PERMISSIONS as $parent => $child) {
            if ($admin->get_post($child) == 1) {
                $active[] = $child;
                $active[] = $parent;
            }
        }

        $systemPermissions = implode(',', array_unique($active));

        // --- Module & template permissions: validate against filesystem ---
        $modulePermissions   = $this->parseAddonPermissionsFromPost($admin, 'module');
        $templatePermissions = $this->parseAddonPermissionsFromPost($admin, 'template');

        return [
            'system_permissions'   => $systemPermissions,
            'module_permissions'   => $modulePermissions,
            'template_permissions' => $templatePermissions,
        ];
    }

    // === ADDON LISTS (for the Groups permission UI) ==========================

    /**
     * Get list of addons for a permission category, with checked status per group.
     *
     * Replaces get_addon_list(). Used in the Groups UI to show which 
     * page modules, tools, templates, etc. a group may use.
     *
     * @param  string $function  'page', 'tool', 'setting', 'template', 'theme'
     * @param  int    $groupId   0 = new group, no pre-checked items
     * @return array  [addon_id => [addon_id, name, directory, checked => bool]]
     */
    public function getAddonList(string $function, int $groupId = 0): array
    {
        // Determine addon type: modules table uses 'module', templates table uses 'template'
        $type = in_array($function, ['template', 'theme'], true) ? 'template' : 'module';

        // Get currently assigned addons for this group
        $assignedAddons = [];
        if ($groupId > 0) {
            $column = $type . '_permissions';
            $raw = $this->db->fetchValue(
                "SELECT `{$column}` FROM `{TP}groups` WHERE `group_id` = ?",
                [$groupId]
            );
            if ($raw !== '') {
                $assignedAddons = array_map('trim', explode(',', $raw));
            }
        }

        // Query all addons of this type/function
        $addons = $this->db->fetchAll(
            "SELECT `addon_id`, `name`, `directory`
               FROM `{TP}addons`
              WHERE `type` = ? AND `function` LIKE ?
              ORDER BY `name`",
            [$type, '%' . $function . '%']
        );

        $result = [];
        foreach ($addons as $rec) {
            $identifier = $rec['directory'];

            // Tools get '_tool' suffix in the permissions string
            if ($function === 'tool') {
                $identifier .= '_tool';
            }

            // New group ($groupId === 0): nothing pre-selected — start clean.
            // Existing group: checked = allowed = NOT in deny list.
            $rec['checked'] = ($groupId > 0) && !in_array($identifier, $assignedAddons, true);
            $result[$rec['addon_id']] = $rec;
        }

        return $result;
    }

    // === DEFAULTS (for new groups) ===========================================

    /**
     * Get default permissions from the INI file.
     *
     * @param  string $type  'system_permissions', 'module_permissions', 'template_permissions'
     * @return array         List of permission names
     */
    public function getDefaults(string $type = 'system_permissions'): array
    {
        // Path relative to the groups admin directory
        $iniFile = WB_PATH . '/admin/groups/add_new_group_defaults.ini.php';

        if (!is_readable($iniFile)) {
            return [];
        }

        $data = parse_ini_file($iniFile);
        if (!isset($data[$type]) || $data[$type] === '') {
            return [];
        }

        return array_map('trim', explode(',', str_replace(' ', '', $data[$type])));
    }

    // === INTERNAL HELPERS ====================================================

    /**
     * Read a permission column from the groups table and return as array.
     *
     * @param  int     $groupId
     * @param  string  $column   'system_permissions', 
     *                            'module_permissions', (including admin-tools)
     *                            'template_permissions'
     * @return array             List of permission name strings
     */
    private function getPermissionSet(int $groupId, string $column): array
    {
        // Whitelist the column name to prevent injection (it's not a bindable param)
        $allowed = ['system_permissions', 'module_permissions', 'template_permissions'];
        if (!in_array($column, $allowed, true)) {
            return [];
        }

        $raw = $this->db->fetchValue(
            "SELECT `{$column}` FROM `{TP}groups` WHERE `group_id` = ?",
            [$groupId]
        );

        if ($raw === '' || $raw === null) {
            return [];
        }

        return array_map('trim', explode(',', $raw));
    }

    /**
     * Parse module_permissions[] or template_permissions[] from POST,
     * validating each entry against the filesystem.
     *
     * @param  Admin  $admin
     * @param  string $type   'module' or 'template'
     * @return string         Comma-separated list of validated addon directories
     */
    private function parseAddonPermissionsFromPost(Admin $admin, string $type): string
    {
        $fieldName = $type . '_permissions';
        $posted    = array_map('trim', (array) $admin->get_post($fieldName));

        // Validate posted (checked = allowed) identifiers against the filesystem
        $dirPrefix = WB_PATH . '/' . $type . 's/';
        $allowed   = [];

        foreach ($posted as $addonDir) {
            if ($addonDir === '') continue;

            // For tools: the POST value has '_tool' suffix, the actual directory doesn't
            $fsDir = str_replace('_tool', '', $addonDir);

            if (is_readable($dirPrefix . $addonDir . '/info.php')
                || is_readable($dirPrefix . $fsDir   . '/info.php')
            ) {
                $allowed[] = $addonDir;
            }
        }

        // Deny list = all known addons minus the allowed (checked) ones.
        // This matches the historical storage format where module_permissions
        // holds blocked entries, and Admin::get_permission() returns !$found.
        $all    = $this->getAllAddonIdentifiers($type);
        $denied = array_values(array_diff($all, $allowed));

        return implode(',', $denied);
    }

    /**
     * Return all addon identifiers of the given type stored in {TP}addons.
     * Tools receive a '_tool' suffix to match the deny-list storage format.
     *
     * @param  string $type  'module' | 'template'
     * @return string[]
     */
    private function getAllAddonIdentifiers(string $type): array
    {
        $rows = $this->db->fetchAll(
            "SELECT `directory`, `function` FROM `{TP}addons` WHERE `type` = ?",
            [$type]
        );

        $identifiers = [];
        foreach ($rows as $row) {
            $dir = $row['directory'];
            $fn  = $row['function'];

            // Modules with 'tool' in their function are stored with '_tool' suffix
            if (str_contains($fn, 'tool')) {
                $identifiers[] = $dir . '_tool';
            }
            // Modules with 'page' or no special function use the plain directory name.
            // A module can have both (e.g. function='page,tool') → add both identifiers.
            if (!str_contains($fn, 'tool') || str_contains($fn, 'page')) {
                $identifiers[] = $dir;
            }
        }

        return array_unique($identifiers);
    }
}