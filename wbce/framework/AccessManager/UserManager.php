<?php
/**
 * UserManager — CRUD, validation, and form helpers for WBCE CMS users
 * ────────────────────────────────────────────────────────────────────────────
 * 
 * @package    WBCE\AccessManager
 * @author     Christian M. Stefan  (https://www.wbEasy.de)
 * @copyright  2026 Christian M. Stefan
 * @copyright  2026 WBCE CMS Project
 * @since      WBCE 1.7.0
 *
 * This class does NOT touch class Alerts and does not perform any redirects. 
 * It returns data and status — the controller 
 * decides what to display and where to redirect.
 * 
 * Methods:
 *   getUser()
 *   getAllUsers()
 *   deleteUser()
 *   toggleActive()
 *   autoCleanup()
 *   validateUsername()
 *   validateEmail()
 *   validatePassword()
 *   getGroupsForForm()
 *   getHomeFolders()
 */

declare(strict_types=1);

class UserManager
{
    private Database $db;

    /**
     * SuperAdmin user_id — cannot be modified or deleted by non-superadmins.
     */
    private const SUPERADMIN_ID = 1;


    public function __construct()
    {
        global $database;
        $this->db = $database;
    }

    /**
     * Load a single user with their group names.
     *
     * Returns the user row plus a 'groups' sub-array [group_id => group_name].
     * The JOIN produces multiple rows when a user belongs to multiple groups;
     * this method collapses them into one record.
     *
     * @param  int        $userId
     * @return array|null  User record with 'groups' key, or null if not found
     */
    public function getUser(int $userId): ?array
    {
        if ($userId < 1) {
            return null;
        }

        $rows = $this->db->fetchAll(
            "SELECT u.*, g.`group_id` AS `grp_id`, g.`name` AS `group_name`
               FROM `{TP}users` AS u
               LEFT JOIN `{TP}groups` AS g
                 ON FIND_IN_SET(g.`group_id`, u.`groups_id`)
              WHERE u.`user_id` = ?",
            [$userId]
        );

        if (empty($rows)) {
            return null;
        }

        // First row = user data, subsequent rows = additional group memberships
        $user = $rows[0];
        $user['login_ip'] = ($user['login_ip'] == 0) ? '0.0.0.0' : $user['login_ip'];
        $user['status']   = ($user['active'] ? 'active' : 'inactive');
        $user['groups']   = [];

        foreach ($rows as $row) {
            if (!empty($row['grp_id'])) {
                $user['groups'][$row['grp_id']] = $row['group_name'];
            }
        }

        // Clean up the temporary alias
        unset($user['grp_id'], $user['group_name']);

        return $user;
    }

    /**
     * Get all users, optionally filtered by active status.
     *
     * Each user record includes a 'groups' sub-array [group_id => group_name].
     * Collapses the JOIN's multiple rows per user into single records.
     *
     * @param  int|false  $activeFilter  false = all users, 0 = inactive, 1 = active
     * @param  bool       $showSuperAdmin  Whether to include user_id=1
     * @return array      Keyed by user_id
     */
    public function getAllUsers(int|false $activeFilter = false, bool $showSuperAdmin = true): array
    {
        $sql = "SELECT u.*, g.`name` AS `group_name`, g.`group_id` AS `grp_id`
                  FROM `{TP}users` AS u
                  LEFT JOIN `{TP}groups` AS g
                    ON FIND_IN_SET(g.`group_id`, u.`groups_id`) > 0
                 WHERE 1=1";

        $params = [];

        if (!$showSuperAdmin) {
            $sql .= " AND u.`user_id` != ?";
            $params[] = self::SUPERADMIN_ID;
        }

        if ($activeFilter !== false) {
            if ($activeFilter === 0) {
                $sql .= " AND u.`active` = 0";
            } else {
                $sql .= " AND u.`active` >= 1";
            }
        }

        $sql .= " ORDER BY u.`display_name`, u.`username`";

        $rows = $this->db->fetchAll($sql, $params);

        // Collapse multiple rows per user (from the group JOIN)
        $users = [];
        foreach ($rows as $rec) {
            $userId = $rec['user_id'];

            if (!isset($users[$userId])) {
                $rec['login_ip'] = ($rec['login_ip'] == 0) ? '0.0.0.0' : $rec['login_ip'];
                $rec['status']   = ($rec['active'] ? 'active' : 'inactive');
                $rec['groups']   = [];
                $users[$userId]  = $rec;
            }

            if ($rec['group_name'] !== null) {
                $users[$userId]['groups'][$rec['grp_id']] = $rec['group_name'];
            }
        }

        // Clean up temporary aliases
        foreach ($users as &$u) {
            unset($u['grp_id'], $u['group_name']);
        }

        return $users;
    }

    // === WRITING =============================================================

    /**
     * Create a new user.
     *
     * @param  array  $data  Column => value pairs (without user_id)
     * @return int    New user_id, or 0 on failure
     */
    public function createUser(array $data): int
    {
        unset($data['user_id']); // safety

        $this->db->insertRow('{TP}users', $data);

        if ($this->db->hasError()) {
            return 0;
        }

        return (int) $this->db->lastInsertId();
    }

    /**
     * Update an existing user.
     *
     * @param  int    $userId
     * @param  array  $data    Column => value pairs
     * @return bool   True on success
     */
    public function updateUser(int $userId, array $data): bool
    {
        if ($userId < 2) {
            return false; // SuperAdmin is not editable through this interface
        }

        $data['user_id'] = $userId;
        $this->db->upsertRow('{TP}users', 'user_id', $data);

        return !$this->db->hasError();
    }

    /**
     * Delete a user (two-stage: active → deactivate, inactive → delete).
     *
     * This prevents accidental deletion of active accounts.
     * The controller decides what message to show based on the return value.
     *
     * @param  int          $userId
     * @return string|false  'disabled' if deactivated, 'deleted' if removed, false on error/refusal
     */
    public function deleteUser(int $userId): string|false
    {
        if ($userId < 2) {
            return false;
        }

        $isActive = (int) $this->db->fetchValue(
            "SELECT `active` FROM `{TP}users` WHERE `user_id` = ?",
            [$userId]
        );

        if ($isActive === 1) {
            // Stage 1: deactivate first
            $this->db->upsertRow('{TP}users', 'user_id', [
                'user_id' => $userId,
                'active'  => 0,
            ]);
            $result = 'disabled';
        } else {
            // Stage 2: actually delete
            $this->db->deleteRow('{TP}users', 'user_id', $userId);
            $result = 'deleted';
        }

        return $this->db->hasError() ? false : $result;
    }

    /**
     * Toggle a user's active status.
     *
     * @param  int       $userId
     * @return bool|null  New active state (true=active, false=inactive), null on error
     */
    public function toggleActive(int $userId): ?bool
    {
        if ($userId < 2) {
            return null;
        }

        $current = (int) $this->db->fetchValue(
            "SELECT `active` FROM `{TP}users` WHERE `user_id` = ?",
            [$userId]
        );

        $newState = ($current === 1) ? 0 : 1;

        $this->db->upsertRow('{TP}users', 'user_id', [
            'user_id' => $userId,
            'active'  => $newState,
        ]);

        return $this->db->hasError() ? null : ($newState === 1);
    }

    /**
     * Delete all users that have no email or no password.
     *
     * These are typically incomplete signup records.
     *
     * @return int  Number of users deleted
     */
    public function autoCleanup(): int
    {
        $emptyUsers = $this->db->fetchAll(
            "SELECT `user_id` FROM `{TP}users` WHERE `email` = '' OR `password` = ''"
        );

        $count = 0;
        foreach ($emptyUsers as $rec) {
            $this->db->deleteRow('{TP}users', 'user_id', $rec['user_id']);
            if (!$this->db->hasError()) {
                $count++;
            }
        }

        return $count;
    }

    // === VALIDATION ==========================================================

    /**
     * Validate a username.
     *
     * Checks format (starts with letter, min 3 chars, allowed chars)
     * and uniqueness against the database.
     *
     * @param  string  $username
     * @param  int     $excludeId  User to exclude from uniqueness check (for updates)
     * @return array   Empty array = valid. Non-empty = list of error message keys.
     */
    public function validateUsername(string $username, int $excludeId = 0): array
    {
        $errors = [];

        if (!preg_match('/^[a-z][a-z0-9_-]{2,}$/i', $username)) {
            $errors[] = L_('MESSAGE:USERS_NAME_INVALID_CHARS');
        }

        if (strlen($username) < 3) {
            $errors[] = L_('MESSAGE:USERS_USERNAME_TOO_SHORT');
        }

        // Check uniqueness
        $existing = $this->db->fetchValue(
            "SELECT `user_id` FROM `{TP}users` WHERE `username` = ? AND `user_id` != ?",
            [$username, $excludeId]
        );

        if ($existing !== '' && $existing !== null) {
            $errors[] = L_('MESSAGE:USERS_USERNAME_TAKEN');
        }

        return $errors;
    }

    /**
     * Validate an email address.
     *
     * Uses Wb::validate_email() for format checking, which handles
     * internationalized domain names (IDN/Punycode) correctly.
     * Also checks uniqueness against the database.
     *
     * @param  Wb      $wb         Wb or Admin instance (for validate_email())
     * @param  string  $email
     * @param  int     $excludeId  User to exclude from uniqueness check
     * @return array   Empty array = valid. Non-empty = list of error message keys.
     */
    public function validateEmail(Wb $wb, string $email, int $excludeId = 0): array
    {
        $errors = [];

        if ($email === '') {
            $errors[] = L_('MESSAGE:SIGNUP_NO_EMAIL');
            return $errors; // no point checking further
        }

        if (!$wb->validate_email($email)) {
            $errors[] = L_('MESSAGE:USERS_INVALID_EMAIL');
        }

        // Check uniqueness (always — for new users excludeId=0 excludes nothing)
        $existing = $this->db->fetchValue(
            "SELECT `user_id` FROM `{TP}users` WHERE `email` = ? AND `user_id` != ?",
            [$email, $excludeId]
        );

        if ($existing !== '' && $existing !== null) {
            $errors[] = L_('MESSAGE:USERS_EMAIL_TAKEN');
        }

        return $errors;
    }

    /**
     * Validate and encode a password.
     *
     * Delegates the actual pattern check to Admin::checkPasswordPattern() 
     * since the password policy logic lives there.
     *
     * @param  Admin   $admin       For checkPasswordPattern()
     * @param  string  $password    The new password
     * @param  string  $password2   Confirmation
     * @return string|array         Encoded hash on success, array of errors on failure
     */
    public function validatePassword(Admin $admin, string $password, string $password2): string|array
    {
        if ($password === '') {
            return [L_('MESSAGE:USERS_PASSWORD_TOO_SHORT')];
        }

        $result = $admin->checkPasswordPattern($password, $password2);

        // checkPasswordPattern returns the hashed password as string on success,
        // or an array of error strings on failure
        return $result;
    }

    // === FORM HELPERS (data for dropdowns/checkboxes in the user form) =======

    /**
     * Get groups list for the user form, with 'active' flag for assigned groups.
     *
     * Used to render group checkboxes in the modify/add user form.
     *
     * @param  int    $userId        Load assigned groups from DB for this user (0 = none)
     * @param  array  $preselected   Override: group IDs to mark as active (e.g. from POST on error)
     * @param  bool   $showAdminGroup  Whether to include group_id=1
     * @return array  Keyed by group_id: [id, name, active => bool]
     */
    public function getGroupsForForm(
        int   $userId = 0,
        array $preselected = [],
        bool  $showAdminGroup = false
    ): array {
        // Determine which groups are assigned
        $assigned = $preselected;

        if (empty($assigned) && $userId > 0) {
            $user = $this->db->fetchRow(
                "SELECT `group_id`, `groups_id` FROM `{TP}users` WHERE `user_id` = ?",
                [$userId]
            );
            if ($user !== null) {
                $combined = $user['group_id'] . ',' . $user['groups_id'];
                $assigned = array_map('intval', array_filter(
                    explode(',', $combined),
                    fn($v) => $v !== '' && $v !== '0'
                ));
            }
        }

        // Query all groups
        $sql = "SELECT `group_id` AS `id`, `name` FROM `{TP}groups`";
        $params = [];

        if (!$showAdminGroup) {
            $sql .= " WHERE `group_id` != ?";
            $params[] = 1;
        }

        $sql .= " ORDER BY `name`";
        $rows = $this->db->fetchAll($sql, $params);

        $groups = [];
        foreach ($rows as $rec) {
            $rec['active'] = in_array((int) $rec['id'], $assigned, true);
            $groups[$rec['id']] = $rec;
        }

        return $groups;
    }

    /**
     * Get media folders as a visual tree for the home folder dropdown.
     *
     * Uses the framework functions directoriesList(), directoriesTree(),
     * and flattenTree() to build a hierarchical list with tree connectors.
     *
     * @param  int     $userId       Load assigned folder from DB for this user (0 = none)
     * @param  string  $preselected  Override: folder path to mark as selected (e.g. from POST)
     * @return array   [['dir' => path, 'name' => name, 'prefix' => connector, 'level' => int, 'selected' => bool], ...]
     */
    public function getHomeFolders(int $userId = 0, string $preselected = ''): array
    {
        if ($preselected === '' && $userId > 0) {
            $preselected = (string) $this->db->fetchValue(
                "SELECT `home_folder` FROM `{TP}users` WHERE `user_id` = ?",
                [$userId]
            );
        }

        $baseDir = WB_PATH . MEDIA_DIRECTORY;

        $flat = directoriesList($baseDir);
        $tree = directoriesTree($flat);

        return flattenTree($tree, static function (array $node, array $meta) use ($preselected): array {
            return [
                'dir'      => $node['path'],
                'name'     => $node['name'],
                'prefix'   => $meta['prefix'],
                'level'    => $meta['level'],
                'selected' => ($preselected !== '' && $preselected === $node['path']),
            ];
        }, connectorsFromRoot: true);
    }
}