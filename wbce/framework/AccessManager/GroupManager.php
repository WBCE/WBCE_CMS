<?php
/**
 * GroupManager — CRUD and validation for WBCE CMS groups
 * ────────────────────────────────────────────────────────────────────────────
 * 
 * @package    WBCE\AccessManager
 * @author     Christian M. Stefan  (https://www.wbEasy.de)
 * @copyright  2026 Christian M. Stefan
 * @copyright  2026 WBCE CMS Project
 * @since      WBCE 1.7.0
 *
 * Methods:
 *   getAllGroups()
 *   getGroupName()
 *   getGroupDescription()
 *   duplicateGroup()
 *   deleteGroup()
 *   isNameTaken()
 *   hasMembers()
 */
declare(strict_types=1);

class GroupManager
{
    private Database $db;

    /**
     * Admin group_id — cannot be modified, deleted, or listed for non-superadmins.
     */
    private const ADMIN_GROUP_ID = 1;


    public function __construct()
    {
        global $database;
        $this->db = $database;
    }

    /**
     * Load a single group by ID (all columns).
     *
     * @param  int         $groupId
     * @return array|null  Associative row or null if not found
     */
    public function getGroup(int $groupId): ?array
    {
        if ($groupId < 1) {
            return null;
        }

        return $this->db->fetchRow(
            "SELECT * FROM `{TP}groups` WHERE `group_id` = ?",
            [$groupId]
        );
    }

    /**
     * Get the name of a group.
     *
     * @param  int     $groupId
     * @return string  Empty string if not found
     */
    public function getGroupName(int $groupId): string
    {
        if ($groupId < 1) {
            return '';
        }

        $name = $this->db->fetchValue(
            "SELECT `name` FROM `{TP}groups` WHERE `group_id` = ?",
            [$groupId]
        );

        return ($name !== '' && $name !== null) ? $name : '';
    }

    /**
     * Get the description of a group.
     *
     * @param  int     $groupId
     * @return string  Empty string if not found
     */
    public function getGroupDescription(int $groupId): string
    {
        if ($groupId < 1) {
            return '';
        }

        $desc = $this->db->fetchValue(
            "SELECT `description` FROM `{TP}groups` WHERE `group_id` = ?",
            [$groupId]
        );

        return ($desc !== '' && $desc !== null) ? $desc : '';
    }

    /**
     * Get all groups (optionally exclude admin group) with optional user count.
     *
     * Note: IDKEY generation is left to the controller — the Manager 
     * should not depend on the Admin object.
     *
     * @param  bool   $withUserCount   Include count of users per group
     * @param  bool   $includeAdmin    Include the admin group (id=1) in the list
     * @return array  Keyed by group_id: [group_id => [group_id, name, description, usercount?]]
     */
    public function getAllGroups(bool $withUserCount = true, bool $includeAdmin = true): array
    {
        if ($withUserCount) {
            $sql = "SELECT g.`group_id`, g.`name`, g.`description`,
                           COUNT(u.`user_id`) AS `usercount`
                      FROM `{TP}groups` AS g
                      LEFT JOIN `{TP}users` AS u
                        ON (g.`group_id` = u.`group_id`
                            OR FIND_IN_SET(g.`group_id`, u.`groups_id`) > 0)";
        } else {
            $sql = "SELECT g.`group_id`, g.`name`, g.`description`
                      FROM `{TP}groups` AS g";
        }

        $params = [];
        if (!$includeAdmin) {
            $sql .= " WHERE g.`group_id` != ?";
            $params[] = self::ADMIN_GROUP_ID;
        }

        if ($withUserCount) {
            $sql .= " GROUP BY g.`group_id`";
        }
        $sql .= " ORDER BY g.`group_id`";

        $rows = $this->db->fetchAll($sql, $params);

        $groups = [];
        foreach ($rows as $row) {
            $groups[$row['group_id']] = $row;
        }

        return $groups;
    }

    /**
     * Simple list for dropdowns (e.g. signup group selector).
     * Returns all groups except admin, with just id and name.
     *
     * @return array  [['id' => int, 'name' => string], ...]
     */
    public function getGroupsSimpleList(): array
    {
        return $this->db->fetchAll(
            "SELECT `name`, `group_id` AS `id`
               FROM `{TP}groups`
              WHERE `group_id` != ?
              ORDER BY `name`",
            [self::ADMIN_GROUP_ID]
        );
    }


    /**
     * Create a new group.
     *
     * @param  array  $data  Column => value pairs (without group_id)
     * @return int    New group_id, or 0 on failure
     */
    public function createGroup(array $data): int
    {
        unset($data['group_id']); // safety

        $this->db->insertRow('{TP}groups', $data);

        if ($this->db->hasError()) {
            return 0;
        }

        return (int) $this->db->lastInsertId();
    }

    /**
     * Update an existing group.
     *
     * @param  int    $groupId
     * @param  array  $data     Column => value pairs (group_id will be set internally)
     * @return bool   True on success
     */
    public function updateGroup(int $groupId, array $data): bool
    {
        if ($groupId < 2) {
            return false; // don't allow modifying admin group
        }

        $data['group_id'] = $groupId;
        $this->db->upsertRow('{TP}groups', 'group_id', $data);

        return !$this->db->hasError();
    }

    /**
     * Delete a group.
     *
     * Refuses to delete the admin group (id=1) or groups that still have members.
     *
     * @param  int    $groupId
     * @return bool   True if deleted, false if refused or error
     */
    public function deleteGroup(int $groupId): bool
    {
        if ($groupId < 2) {
            return false;
        }

        if ($this->hasMembers($groupId)) {
            return false;
        }

        $this->db->deleteRow('{TP}groups', 'group_id', $groupId);

        return !$this->db->hasError();
    }

    /**
     * Duplicate a group (copy all data, append "_duplicate" to name).
     *
     * @param  int  $groupId  Source group to copy
     * @return int  New group_id, or 0 on failure
     */    
    public function duplicateGroup(int $groupId): int
    {
        $source = $this->getGroup($groupId);
        if ($source === null) {
            return 0;
        }

        // 1. Normalization: Remove existing suffixes (e.g., "_duplicate" or "_duplicate_5")
        // to prevent creating names like "mygroup_duplicate_duplicate"
        $cleanName = preg_replace('/_duplicate(_\d+)?$/', '', $source['name']);

        // 2. Initialize the base name and counter for unique generation
        $newName = $cleanName . '_duplicate';
        $counter = 1;

        // 3. Loop until a unique name is found
        while ($this->isNameTaken($newName)) {
            $newName = $cleanName . '_duplicate_' . $counter;
            $counter++;
        }

        // 4. Prepare data for the new group and save it
        unset($source['group_id']);
        $source['name'] = $newName;

        return $this->createGroup($source);
    }

    /**
     * Check if a group name is already in use.
     *
     * @param  string  $name       The name to check
     * @param  int     $excludeId  Group to exclude (for updates: don't match against self)
     * @return bool    True if the name is already taken
     */
    public function isNameTaken(string $name, int $excludeId = 0): bool
    {
        if ($excludeId > 0) {
            $count = $this->db->fetchValue(
                "SELECT COUNT(*) FROM `{TP}groups` WHERE `name` = ? AND `group_id` != ?",
                [$name, $excludeId]
            );
        } else {
            $count = $this->db->fetchValue(
                "SELECT COUNT(*) FROM `{TP}groups` WHERE `name` = ?",
                [$name]
            );
        }

        return (int) $count > 0;
    }

    /**
     * Check if a group has any members assigned.
     *
     * Checks both the primary group_id and the comma-separated groups_id field.
     *
     * @param  int   $groupId
     * @return bool  True if at least one user belongs to this group
     */
    public function hasMembers(int $groupId): bool
    {
        $count = $this->db->fetchValue(
            "SELECT COUNT(*) FROM `{TP}users`
              WHERE `group_id` = ? OR FIND_IN_SET(?, `groups_id`) > 0",
            [$groupId, $groupId]
        );

        return (int) $count > 0;
    }
}
