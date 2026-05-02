<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @author    Ryan Djurovich (original author)
 * @author    Werner v.d.Decken (2011, introduction of the move method and modernization)
 * @author    Christian M. Stefan (2026, PDO Database class refactor)
 * @copyright 2004-2008, Ryan Djurovich, Website Baker Project
 * @copyright 2009-2011, Website Baker Org. e.V.
 * @copyright 2026 WBCE Project
 * @license   GNU GPL2 (or any later version)
 *
 * This is a rewrite of class Order based on the 2011 WebsiteBaker CMS version.
 * Refactored to meet the needs of the newly introduced PDO Database class. 
 * ────────────────────────────────────────────────────────────────────────────────
 * Changes the order/position of a row in a table based on an integer field.
 */

// Prevent  this  file  from  being  accessed  directly
defined('WB_PATH') or die('No direct access allowed');

class Order
{
    const MOVE_UP   = 0;
    const MOVE_DOWN = 1;

    private Database $db;
    private string   $table;
    private string   $fieldOrder;
    private string   $fieldId;
    private string   $fieldGroup;
    
    /**
     * Legacy->Canonical method mapping (old snake_case → new PSR-4 camelCase)
     * (prefere using the new canonical names in all new code):
     */
    private const LEGACY = [
        'get_new'   => 'getNew',
        'move_up'   => 'moveUp',
        'move_down' => 'moveDown',
    ];

    /**
     * @param string $table       Table name (pass TABLE_PREFIX . 'name')
     * @param string $fieldOrder  Column holding the position integer
     * @param string $fieldId     Primary key column
     * @param string $fieldGroup  Column that partitions rows into independent ordered groups
     */
    public function __construct(
        string $table,
        string $fieldOrder,
        string $fieldId,
        string $fieldGroup
    ) {
        $this->db         = $GLOBALS['database'];
        $this->table      = $table;
        $this->fieldOrder = $fieldOrder;
        $this->fieldId    = $fieldId;
        $this->fieldGroup = $fieldGroup;
    }

    /** Move a row up (lower position number). */
    public function moveUp(string|int $id): bool
    {
        return $this->move($id, self::MOVE_UP);
    }

    /** Move a row down (higher position number). */
    public function moveDown(string|int $id): bool
    {
        return $this->move($id, self::MOVE_DOWN);
    }

    /**
     * Swap the position of a row with its neighbour in the given direction.
     *
     * @param string|int   $id          Primary key value of the row to move
     * @param int          $direction   MOVE_UP or MOVE_DOWN
     */
    public function move(string|int $id, int $direction = self::MOVE_UP): bool
    {
        $t  = $this->table;
        $fo = $this->fieldOrder;
        $fi = $this->fieldId;
        $fg = $this->fieldGroup;

        // Step 1: get current position and group of the row to move
        $current = $this->db->fetchAll(
            "SELECT `$fo` AS `order`, `$fg` AS `group`
             FROM `$t` WHERE `$fi` = ?",
            [$id]
        )[0] ?? null;

        if ($current === null) return false;

        // Step 2: find the neighbour to swap with
        if ($direction === self::MOVE_UP) {
            $comparison = "<";
            $sort       = "DESC";
        } else {
            $comparison = ">";
            $sort       = "ASC";
        }

        $neighbour = $this->db->fetchAll(
            "SELECT `$fi` AS `id`, `$fo` AS `order`
                FROM `$t`
                WHERE `$fg` = ? AND `$fo` $comparison ?
                ORDER BY `$fo` $sort
                LIMIT 1",
            [$current['group'], $current['order']]
        )[0] ?? null;

        if ($neighbour === null) return false;

        // Step 3: swap the two position numbers
        $this->db->query(
            "UPDATE `$t` SET `$fo` = ? WHERE `$fi` = ?",
            [$current['order'], $neighbour['id']]
        );
        $this->db->query(
            "UPDATE `$t` SET `$fo` = ? WHERE `$fi` = ?",
            [$neighbour['order'], $id]
        );

        return !$this->db->hasError();
    }

    /**
     * Return the next free position number within a group.
     *
     * @param string|int  $group  Group value
     */
    public function getNew(string|int $group): int
    {
        $t  = $this->table;
        $fo = $this->fieldOrder;
        $fg = $this->fieldGroup;

        return (int)$this->db->fetchValue(
            "SELECT MAX(`$fo`) FROM `$t` WHERE `$fg` = ?",
            [$group]
        ) + 1;
    }

    /**
     * Renumber all rows in a group sequentially from 1 to n.
     * Call this after deleting a row to close any gaps.
     *
     * Uses a PHP loop for cross-database compatibility 
     *
     * @param string|int $group  Group value
     */
    public function clean(string|int $group): bool
    {
        $t  = $this->table;
        $fo = $this->fieldOrder;
        $fi = $this->fieldId;
        $fg = $this->fieldGroup;

        $rows = $this->db->fetchAll(
            "SELECT `$fi` AS `id` FROM `$t`
             WHERE `$fg` = ?
             ORDER BY `$fo` ASC",
            [$group]
        );

        foreach ($rows as $position => $row) {
            $this->db->query(
                "UPDATE `$t` SET `$fo` = ? WHERE `$fi` = ?",
                [$position + 1, $row['id']]
            );
        }

        return !$this->db->hasError();
    }
    
    /**
     * Handle calls to legacy method names (backward compatibility)
     */
    public function __call(string $name, array $args): mixed
    {
        if (isset(self::LEGACY[$name])) {
            $canonical = self::LEGACY[$name];

            if (defined('SQL_CANONICAL_DEBUG') && SQL_CANONICAL_DEBUG) {
                trigger_error(
                    "Order::$name() is deprecated — use $canonical() instead",
                    E_USER_DEPRECATED
                );
            }

            return $this->$canonical(...$args);
        }

        throw new BadMethodCallException("Order::$name() does not exist.");
    }
}