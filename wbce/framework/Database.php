<?php
/**
 * Standalone PDO Database Class
 *
 * A modern, independent PDO database wrapper developed as a standalone class 
 * and adapted for seamless integration with WBCE CMS.
 *
 * This is a complete replacement for the legacy mysqli-based database class 
 * and adds support for MySQL/MariaDB, SQLite and PostgreSQL while maintaining 
 * full backward compatibility.
 * 
 *
 * @author     Christian M. Stefan
 * @copyright  Copyright (c) 2025-2026 Christian M. Stefan
 * @copyright  Copyright (c) 2026 WBCE CMS Project
 * @license    GNU/GPL 2  https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @note       This class maintains the public interface expected by WBCE core 
 *             and modules (legacy method aliases, DatabaseResult wrapper, etc.) 
 *             in order to ensure maximum compatibility.
 * 
 * ─────────────────────────────────────────────────────────────────────────────
 * CANONICAL METHOD NAMES (prefere using these in all new code):
 *
 *   query()            fetchValue()     fetchAll()
 *   insertRow()        upsertRow()      deleteRow()
 *   modifyField()      removeField()    addField()
 *   fieldExists()      tableExists()    lastInsertId()
 *   hasError()         getError()       setError()
 *   getDriver()        getPDO()         importSql()
 *
 * JSON column helpers (driver-transparent MySQL/MariaDB + SQLite so every module 
 * gets them on the shared $database instance without a second PDO connection):
 *
 *   jsonGet()          jsonSet()        jsonArrayContains()  jsonMerge()
 *   jsonSave()         jsonRead()       castForJson()
 */

defined('TABLE_PREFIX') or die(header('Location: ../index.php', true, 301));

// ─────────────────────────────────────────────────────────────────────────────
// Auto-build DSN from classic WBCE config constants
// ─────────────────────────────────────────────────────────────────────────────
if (!defined('DB_DSN')) {
    $dbType = strtolower(defined('DB_TYPE') ? DB_TYPE : 'mysqli');

    switch ($dbType) {
        
        case 'sqlite':
            $sqlitePath = WB_PATH . '/var/database/wbce.sqlite';
            if (defined('SQLITE_DB_PATH') && SQLITE_DB_PATH !== '') {
                if(function_exists('wbceSafePath')){
                    $validated = wbceSafePath(SQLITE_DB_PATH);
                    if ($validated !== null) {
                        $sqlitePath = $validated; 
                    }
                }
            }
            define('DB_DSN', 'sqlite:' . $sqlitePath);
            define('DB_USER', '');
            define('DB_PASS', '');
            break;

        case 'mysqli':
        case 'mysql':
        case 'maria':
        default:
            $port = defined('DB_PORT') && DB_PORT ? ';port=' . DB_PORT : '';
            define('DB_DSN', 'mysql:host=' . DB_HOST . $port . ';dbname=' . DB_NAME . ';charset=utf8mb4');
            define('DB_USER', defined('DB_USERNAME') ? DB_USERNAME : 'root');
            define('DB_PASS', defined('DB_PASSWORD') ? DB_PASSWORD : '');
            break;
    }
}


class Database
{
    private string $driver   = 'mysql';
    private PDO    $pdo;
    private string $error    = '';
    private array  $prefixes = [];
    
    // ── Legacy method mapping ──────────────────────────────────────────────────────────
    /**
     * WBCE_LEGACY ALIASES
     *
     * This array defines which old method names are mapped to the new 
     * canonical methods. When PDO_CANONICAL_DEBUG is enabled, a deprecation 
     * warning will be triggered for each legacy method call.
     *
     * Note regarding SqlImport():
     * The SqlImport() method is NOT handled through this array. 
     * It is implemented as a separate legacy method further below.
     * Reason: It has a different signature and return behavior 
     * (boolean instead of a detailed result array) compared to the new importSql().
     */
    private const WBCE_LEGACY = [
        'get_one'         => 'fetchValue',
        'get_array'       => 'fetchAll',
        'updateRow'       => 'upsertRow',
        'delRow'          => 'deleteRow',
        'field_exists'    => 'fieldExists',
        'field_add'       => 'addField',
        'field_modify'    => 'modifyField',
        'field_remove'    => 'removeField',
        'is_error'        => 'hasError',
        'get_error'       => 'getError',
        'set_error'       => 'setError',
        'getLastInsertId' => 'lastInsertId',
        'pdo'             => 'getPDO',
        'index_remove'    => 'removeIndex',
        'index_add'       => 'addIndex'
    ];

    public function __call(string $name, array $args): mixed
    {
        if (isset(self::WBCE_LEGACY[$name])) {
            $canonical = self::WBCE_LEGACY[$name];
            if (defined('PDO_CANONICAL_DEBUG') && PDO_CANONICAL_DEBUG) {
                trigger_error(
                    "Database::$name() is deprecated — use $canonical()",
                    E_USER_DEPRECATED
                );
            }
            return $this->$canonical(...$args);
        }
        throw new BadMethodCallException("Database::$name() does not exist.");
    }
    
    /**
     * Magic getter for backward compatibility with old modules.
     * Some modules directly access $database->db_handle.
     */
    public function __get(string $name): mixed
    {
        if ($name === 'db_handle' || $name === 'DbHandle') {
            return $this->pdo;                    
        }

        if ($name === 'db_name' || $name === 'DbName') {
            // Optional: you can return DB_NAME if needed
            return defined('DB_NAME') ? DB_NAME : null;
        }

        // For any other unknown property
        if (defined('SQL_DEBUG') && SQL_DEBUG) {
            trigger_error("Undefined property: Database::\$$name", E_USER_WARNING);
        }

        return null;
    }
    
    // ── Constructor ──────────────────────────────────────────────────────────────────────
    public function __construct()
    {
        $this->prefixes = [
            '{TABLE_PREFIX}' => TABLE_PREFIX,
            '{TP}'           => TABLE_PREFIX,
        ];

        try {
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            $dsn = DB_DSN;

            if (str_starts_with($dsn, 'sqlite')) {
                $this->pdo    = new PDO($dsn, null, null, $options);
                $this->driver = 'sqlite';
                // busy_timeout matters more than usual here: WbceDbSession writes
                // on every request, so any concurrent request (a thumbnail, an
                // AJAX call) racing the same connection would otherwise hit
                // SQLITE_BUSY immediately instead of waiting a moment for the
                // lock to clear.
                $this->pdo->exec('PRAGMA journal_mode=WAL; PRAGMA foreign_keys=ON; PRAGMA busy_timeout=5000;');
                $this->registerSQLiteCompatFunctions();
            } elseif (str_starts_with($dsn, 'mysql')) {
                $this->pdo    = new PDO($dsn, DB_USER, DB_PASS, $options);
                $this->driver = 'mysql';
                $this->pdo->exec("SET NAMES utf8mb4; SET @@sql_mode=''");
            } else {
                throw new RuntimeException('Unsupported DB driver in DSN. Only mysql and sqlite are supported.');
            }
        } catch (PDOException $e) {
            throw new RuntimeException('DB connection failed: ' . $e->getMessage());
        }
    }

    // ── SQLite compatibility functions ───────────────────────────────────────────────────

    /**
     * Registers PHP implementations of MySQL functions that SQLite lacks.
     *
     * Called once after the SQLite connection is established. All registered
     * functions are available for every query on this connection, including
     * those executed via importSql().
     *
     * Adding a new MySQL function: implement it as a PHP callable and add
     * a sqliteCreateFunction() call below. The signature is:
     *   sqliteCreateFunction(string $name, callable $fn, int $argCount = -1)
     * Use -1 for variadic functions (unknown argument count).
     */
    private function registerSQLiteCompatFunctions(): void
    {
        // FIND_IN_SET(needle, haystack) — returns 1-based position, 0 if not found.
        // MySQL: FIND_IN_SET('b', 'a,b,c') → 2
        // Used in WBCE for comma-separated group/permission lists.
        $this->sqliteCreateFunction(
            'FIND_IN_SET',
            function (?string $needle, ?string $haystack): int {
                if ($needle === null || $haystack === null || $haystack === '') {
                    return 0;
                }
                $list = explode(',', $haystack);
                $pos  = array_search(trim($needle), array_map('trim', $list), true);
                return $pos !== false ? $pos + 1 : 0;
            },
            2
        );

        // RAND() — returns a random float between 0 and 1, like MySQL's RAND().
        $this->sqliteCreateFunction(
            'RAND',
            fn(): float => (float)(mt_rand() / mt_getrandmax()),
            0
        );

        // NOW() — returns current datetime as 'Y-m-d H:i:s' string.
        $this->sqliteCreateFunction(
            'NOW',
            fn(): string => date('Y-m-d H:i:s'),
            0
        );

        // UNIX_TIMESTAMP([datetime]) — returns current or parsed Unix timestamp.
        // With no argument: equivalent to time().
        // With a datetime string: parses it and returns the Unix timestamp.
        $this->sqliteCreateFunction(
            'UNIX_TIMESTAMP',
            function (?string $datetime = null): int {
                return $datetime !== null ? (int)strtotime($datetime) : time();
            },
            -1
        );

        // IFNULL(expr, alt) — returns expr if not null, otherwise alt.
        // SQLite has this natively as IFNULL(), but some older SQL uses the MySQL alias.
        $this->sqliteCreateFunction(
            'IFNULL',
            fn(mixed $expr, mixed $alt): mixed => $expr !== null ? $expr : $alt,
            2
        );

        // CONCAT(s1, s2, ...) — string concatenation.
        // SQLite uses || for concatenation; MySQL uses CONCAT().
        $this->sqliteCreateFunction(
            'CONCAT',
            fn(string ...$parts): string => implode('', $parts),
            -1
        );
    }

    /**
     * PDO::sqliteCreateFunction() is deprecated since PHP 8.5 in favor of
     * Pdo\Sqlite::createFunction() (the driver-specific subclass introduced
     * in PHP 8.4). Prefer the new method when available, fall back to the
     * old one on PHP < 8.4.
     */
    private function sqliteCreateFunction(string $name, callable $fn, int $argCount): void
    {
        if (method_exists($this->pdo, 'createFunction')) {
            $this->pdo->createFunction($name, $fn, $argCount);
        } else {
            $this->pdo->sqliteCreateFunction($name, $fn, $argCount);
        }
    }

    // ── Prefix handling ─────────────────────────────────────────────────────────────────
    protected function prep(string $sql): string
    {
        return str_contains($sql, '{') ? strtr($sql, $this->prefixes) : $sql;
    }

    public function addPrefix(string $placeholder, string $value): void
    {
        $this->prefixes[$placeholder] = $value;
    }

    // ── Core queries ─────────────────────────────────────────────────────────────────────

    /**
     * Bind params with type-aware PDO::PARAM_* constants instead of relying on
     * PDOStatement::execute($params), which binds every value as PARAM_STR under
     * emulated prepares (PDO_MySQL's default — ATTR_EMULATE_PREPARES is not
     * disabled in the constructor). MySQL's LIMIT/OFFSET clause rejects quoted
     * string literals ("LIMIT '5'" is a syntax error, "LIMIT 5" isn't), so any
     * query binding a LIMIT/OFFSET value as "?" fails unless it's bound as an
     * actual integer. array_values() guards against non-sequential array keys.
     */
    private function bindParams(PDOStatement $stmt, array $params): void
    {
        $i = 0;
        foreach (array_values($params) as $value) {
            $i++;
            $type = match (true) {
                is_int($value)  => PDO::PARAM_INT,
                is_bool($value) => PDO::PARAM_BOOL,
                is_null($value) => PDO::PARAM_NULL,
                default          => PDO::PARAM_STR,
            };
            $stmt->bindValue($i, $value, $type);
        }
    }

    public function query(string $sql, array $params = []): DatabaseResult
    {
        $sql = $this->prep($sql);
        if ($this->driver === 'sqlite') {
            $sql = $this->translateInsertSet($sql);
            if ($this->isLastInsertIdQuery($sql)) {
                $this->error = '';
                return DatabaseResult::fromSingleValue($this->pdo->lastInsertId());
            }
        }
        try {
            $stmt = $this->pdo->prepare($sql);
            $this->bindParams($stmt, $params);
            $stmt->execute();
            $this->error = '';
            return new DatabaseResult($stmt);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            if (defined('SQL_DEBUG') && SQL_DEBUG) {
                trigger_error('SQL Query Error: ' . $e->getMessage() .
                              ' | SQL: ' . preg_replace('/\s+/', ' ', $sql), E_USER_WARNING);
            }
            return new DatabaseResult(null);
        }
    }

    /**
     * Rewrites MySQL's `INSERT INTO t SET col=val, col2=val2` into standard
     * `INSERT INTO t (col, col2) VALUES (val, val2)` for SQLite, which has
     * no SET form for INSERT ("near SET: syntax error").
     *
     * Applied transparently inside query() (SQLite only) so modules that
     * still write this pattern at *runtime* — not just at install time —
     * keep working without a code change. 
     *
     * Deliberately conservative: bails out (returns $sql unchanged) on
     * anything it isn't confident about — ON DUPLICATE KEY UPDATE, no
     * parseable assignments, unbalanced quotes — so an unsupported
     * variant fails loudly with its own SQLite syntax error rather than
     * being silently mistranslated into something that runs but is wrong.
     */
    private function translateInsertSet(string $sql): string
    {
        if (!preg_match(
            '/^\s*INSERT\s+(?:IGNORE\s+)?INTO\s+(`?[\w{}]+`?)\s+SET\s+(.+?)\s*;?\s*$/is',
            $sql,
            $m
        )) {
            return $sql;
        }
        [, $table, $assignments] = $m;

        if (stripos($assignments, 'ON DUPLICATE KEY UPDATE') !== false) {
            return $sql;
        }

        $pairs = $this->splitAssignments($assignments);
        if ($pairs === null) {
            return $sql;
        }

        $cols = [];
        $vals = [];
        foreach ($pairs as $pair) {
            $eq = strpos($pair, '=');
            if ($eq === false) {
                return $sql;
            }
            $cols[] = trim(substr($pair, 0, $eq));
            $vals[] = trim(substr($pair, $eq + 1));
        }

        return "INSERT INTO $table (" . implode(', ', $cols) . ') VALUES (' . implode(', ', $vals) . ')';
    }

    /**
     * Matches the MySQL-only `SELECT LAST_INSERT_ID()` idiom — SQLite has no
     * such function ("no such function: LAST_INSERT_ID"). Used by query()
     * and fetchValue() (get_one()'s target) to answer it directly from
     * PDO::lastInsertId() instead of running it as SQL.
     */
    private function isLastInsertIdQuery(string $sql): bool
    {
        return (bool) preg_match(
            '/^\s*SELECT\s+LAST_INSERT_ID\s*\(\s*\)\s*(?:AS\s+[`"]?\w+[`"]?)?\s*;?\s*$/i',
            $sql
        );
    }

    /**
     * Splits a `col=val, col2=val2` assignment list on commas, respecting
     * single-quoted string values so a literal comma inside a quoted value
     * isn't mistaken for an assignment separator. Returns null if the
     * string has unbalanced quotes (parsing not safe).
     */
    private function splitAssignments(string $s): ?array
    {
        $parts   = [];
        $current = '';
        $inQuote = false;
        $len     = strlen($s);

        for ($i = 0; $i < $len; $i++) {
            $ch = $s[$i];
            if ($ch === "'" && ($i === 0 || $s[$i - 1] !== '\\')) {
                $inQuote = !$inQuote;
                $current .= $ch;
                continue;
            }
            if ($ch === ',' && !$inQuote) {
                $parts[] = $current;
                $current = '';
                continue;
            }
            $current .= $ch;
        }

        if ($inQuote) {
            return null;
        }
        if (trim($current) !== '') {
            $parts[] = $current;
        }
        return $parts;
    }

    public function fetchValue(string $sql, array $params = []): mixed
    {
        $this->error = '';
        $sql = $this->prep($sql);
        if ($this->driver === 'sqlite' && $this->isLastInsertIdQuery($sql)) {
            return $this->pdo->lastInsertId();
        }
        try {
            $stmt = $this->pdo->prepare($sql);
            $this->bindParams($stmt, $params);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_NUM);

            // Legacy-Verhalten: null → '' (wie die alte Klasse oft gemacht hat)
            return ($row !== false) ? $row[0] : '';
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            if (defined('SQL_DEBUG') && SQL_DEBUG) {
                trigger_error('SQL fetchValue Error: ' . $e->getMessage(), E_USER_WARNING);
            }
            return '';
        }
    }
    public function fetchAll(string $sql, array $params = []): array
    {
        $this->error = '';
        $sql = $this->prep($sql);
        try {
            $stmt = $this->pdo->prepare($sql);
            $this->bindParams($stmt, $params);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            if (defined('SQL_DEBUG') && SQL_DEBUG) {
                trigger_error('SQL fetchAll Error: ' . $e->getMessage(), E_USER_WARNING);
            }
            return [];
        }
    }

    public function fetchRow(string $sql, array $params = []): ?array
    {
        $this->error = '';
        $sql = $this->prep($sql);
        try {
            $stmt = $this->pdo->prepare($sql);
            $this->bindParams($stmt, $params);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($row !== false) ? $row : null;   // hier null ist meistens ok
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            if (defined('SQL_DEBUG') && SQL_DEBUG) {
                trigger_error('SQL fetchRow Error: ' . $e->getMessage(), E_USER_WARNING);
            }
            return null;
        }
    }
    /**
     * Runs $fn inside a transaction. Commits on success, rolls back and
     * rethrows on any Throwable. Also rolls back if $fn leaves an error on
     * the last query without throwing (query()/upsertRow()/etc. swallow
     * PDOException internally and only set $this->error — this closes that
     * gap so callers don't have to check hasError() after every statement).
     */
    public function transaction(callable $fn): mixed
    {
        $this->pdo->beginTransaction();
        try {
            $result = $fn($this);
            if ($this->hasError()) {
                throw new \RuntimeException($this->getError());
            }
            $this->pdo->commit();
            return $result;
        } catch (\Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    // ── CRUD helpers ─────────────────────────────────────────────────────────────────────

    public function insertRow(string $table, array $data): bool|string
    {
        $this->error = '';
        $table = $this->prep($table);
        $cols  = array_keys($data);
        $colStr = '`' . implode('`, `', $cols) . '`';
        $ph    = implode(', ', array_fill(0, count($cols), '?'));

        try {
            $this->pdo->prepare("INSERT INTO `$table` ($colStr) VALUES ($ph)")
                      ->execute(array_values($data));
            return true;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            if (defined('SQL_DEBUG') && SQL_DEBUG) {
                trigger_error('SQL insertRow Error: ' . $e->getMessage(), E_USER_WARNING);
            }
            return $e->getMessage();
        }
    }

    public function upsertRow(string $table, string|array $refKey, array $data): bool|string
    {
        $this->error = '';
        $table = $this->prep($table);

        if (is_string($refKey) && str_contains($refKey, ',')) {
            $refKey = array_map('trim', explode(',', $refKey));
        }
        $keys = array_values(array_filter((array)$refKey));

        if (empty($keys) || empty($data)) {
            return 'upsertRow: refKey and data must not be empty';
        }

        try {
            return match ($this->driver) {
                'mysql'  => $this->upsertMySQL($table, $keys, $data),
                // SQLite's native `INSERT ... ON CONFLICT DO UPDATE` still
                // validates the INSERT clause against every NOT NULL column
                // in the table, even when the conflict branch will only ever
                // run an UPDATE — so a partial-column upsertRow() call (the
                // common WBCE pattern: insertRow() the full row first, then
                // upsertRow() to patch a few derived columns on that same,
                // already-existing row) fails with a NOT NULL constraint
                // error on every column the caller didn't list. MySQL's
                // ON DUPLICATE KEY UPDATE doesn't have this problem because
                // it only validates NOT NULL when it actually inserts.
                // upsertFallback()'s check-then-UPDATE-or-INSERT approach
                // sidesteps this: its UPDATE branch only touches the listed
                // columns, so it never triggers this validation for rows
                // that already exist.
                'sqlite' => $this->upsertFallback($table, $keys, $data),
                default  => $this->upsertFallback($table, $keys, $data),
            };
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            if (defined('SQL_DEBUG') && SQL_DEBUG) {
                trigger_error('SQL upsertRow Error: ' . $e->getMessage(), E_USER_WARNING);
            }
            return $e->getMessage();
        }
    }

    /**
     * MySQL/MariaDB native upsert (compatible with older versions).
     * Uses the classic VALUES() syntax for maximum compatibility.
     */
    private function upsertMySQL(string $table, array $keys, array $data): bool
    {
        $cols = array_keys($data);
        $vals = array_values($data);

        $colList = '`' . implode('`, `', $cols) . '`';
        $ph      = implode(', ', array_fill(0, count($cols), '?'));

        // Only update non-key columns
        $updateCols = array_filter($cols, fn($c) => !in_array($c, $keys, true));

        if (empty($updateCols)) {
            // All columns are key columns → INSERT IGNORE
            $this->pdo->prepare("INSERT IGNORE INTO `$table` ($colList) VALUES ($ph)")
                      ->execute($vals);
            return true;
        }

        // Classic compatible syntax using VALUES(col)
        $updateClause = implode(', ', array_map(
            fn($c) => "`$c` = VALUES(`$c`)", 
            $updateCols
        ));

        $this->pdo->prepare(
            "INSERT INTO `$table` ($colList) VALUES ($ph)
             ON DUPLICATE KEY UPDATE $updateClause"
        )->execute($vals);

        return true;
    }
    

    private function upsertFallback(string $table, array $keys, array $data): bool
    {
        $cols      = array_keys($data);
        $vals      = array_values($data);
        $where     = implode(' AND ', array_map(fn($k) => "`$k` = ?", $keys));
        $whereVals = array_map(fn($k) => $data[$k], $keys);

        $chk = $this->pdo->prepare("SELECT COUNT(*) FROM `$table` WHERE $where");
        $chk->execute($whereVals);
        $exists = (int)$chk->fetchColumn() > 0;

        if ($exists) {
            $updateCols = array_filter($cols, fn($c) => !in_array($c, $keys, true));
            $set        = implode(', ', array_map(fn($c) => "`$c` = ?", $updateCols));
            $setVals    = array_map(fn($c) => $data[$c], $updateCols);
            $this->pdo->prepare("UPDATE `$table` SET $set WHERE $where")
                      ->execute(array_merge(array_values($setVals), $whereVals));
        } else {
            $colList = '`' . implode('`, `', $cols) . '`';
            $ph      = implode(', ', array_fill(0, count($cols), '?'));
            $this->pdo->prepare("INSERT INTO `$table` ($colList) VALUES ($ph)")
                      ->execute($vals);
        }

        return true;
    }

    /**
     * Delete one or more rows from a table by matching a single column.
     *
     * Deletes all rows where `$refKey` matches any value in `$values`.
     * A single value can be passed as a scalar — it will be wrapped in an array.
     *
     * @param  string       $table   Table name (supports {TP} placeholder)
     * @param  string       $refKey  Column name to match against
     * @param  mixed        $values  Scalar or array of values — uses IN (?, ?, ...)
     * @return bool|string           true on success, error message on failure
     *
     * @example
     *   $database->deleteRow('{TP}sections', 'page_id', 42);
     *   $database->deleteRow('{TP}sections', 'page_id', [10, 11, 12]);
     */
    public function deleteRow(string $table, string $refKey, mixed $values): bool|string
    {
        $this->error = '';
        $table  = $this->prep($table);
        $values = (array)$values;
        $ph     = implode(', ', array_fill(0, count($values), '?'));

        try {
            $this->pdo->prepare("DELETE FROM `$table` WHERE `$refKey` IN ($ph)")
                      ->execute($values);
            return true;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            if (defined('SQL_DEBUG') && SQL_DEBUG) {
                trigger_error('SQL deleteRow Error: ' . $e->getMessage(), E_USER_WARNING);
            }
            return $e->getMessage();
        }
    }

    // ── Schema helpers ──────────────────────────────────────────────────────────────────

    public function fieldExists(string $table, string $field): bool
    {
        $table = $this->prep($table);
        try {
            if ($this->driver === 'sqlite') {
                $rows = $this->pdo->query("PRAGMA table_info(`$table`)")->fetchAll();
                foreach ($rows as $row) {
                    if ($row['name'] === $field) return true;
                }
                return false;
            }
            if ($this->driver === 'mysql') {
                $stmt = $this->pdo->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
                $stmt->execute([$field]);
                return $stmt->rowCount() > 0;
            }
        } catch (PDOException) {
            return false;
        }
    }

    /**
     * Portable replacement for MySQL's `SHOW TABLES LIKE '...'` — that
     * statement has no SQLite equivalent at all (not a DDL/DML pattern
     * normalizeSql()/importSql() can rewrite, since it's not part of a
     * CREATE/INSERT statement).
     *
     * @param  string $table  Table name (supports {TP}/custom prefixes)
     * @return bool
     */
    public function tableExists(string $table): bool
    {
        $table = $this->prep($table);
        try {
            if ($this->driver === 'sqlite') {
                $stmt = $this->pdo->prepare(
                    "SELECT 1 FROM sqlite_master WHERE type = 'table' AND name = ?"
                );
                $stmt->execute([$table]);
                return (bool) $stmt->fetchColumn();
            }
            $stmt = $this->pdo->prepare('SHOW TABLES LIKE ?');
            $stmt->execute([$table]);
            return $stmt->rowCount() > 0;
        } catch (PDOException) {
            return false;
        }
    }

    public function addField(string $table, string $field, string $definition, bool $setError = true): bool
    {
        $this->error = '';
        $table = $this->prep($table);

        // Field already exists — desired state achieved, nothing to do
        if ($this->fieldExists($table, $field)) {
            return true;  // not an error — idempotent
        }

        if ($this->driver === 'sqlite') {
            $definition = preg_replace('/\s+AFTER\s+`?\w+`?\s*$/i', '', trim($definition));
        }
        try {
            $this->pdo->exec("ALTER TABLE `$table` ADD `$field` $definition");
            return true;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            if (defined('SQL_DEBUG') && SQL_DEBUG) {
                trigger_error('SQL addField Error: ' . $e->getMessage(), E_USER_WARNING);
            }
            return false;
        }
    }

    public function modifyField(string $table, string $field, string $definition): bool
    {
        $this->error = '';
        $table = $this->prep($table);
        if ($this->driver === 'sqlite') {
            $this->error = 'SQLite does not support MODIFY COLUMN';
            return false;
        }
        try {
            $this->pdo->exec("ALTER TABLE `$table` MODIFY `$field` $definition");
            return true;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            if (defined('SQL_DEBUG') && SQL_DEBUG) {
                trigger_error('SQL modifyField Error: ' . $e->getMessage(), E_USER_WARNING);
            }
            return false;
        }
    }

    public function removeField(string $table, string $field): bool
    {
        $this->error = '';
        $table = $this->prep($table);
        try {
            $this->pdo->exec("ALTER TABLE `$table` DROP COLUMN `$field`");
            return true;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            if (defined('SQL_DEBUG') && SQL_DEBUG) {
                trigger_error('SQL removeField Error: ' . $e->getMessage(), E_USER_WARNING);
            }
            return false;
        }
    }

    /**
     * Add an index to a table. Drops existing index with same name first.
     *
     * @param string $table      Table name (supports {TP} placeholder)
     * @param string $indexName  Index name (ignored for PRIMARY KEY)
     * @param string $fields     Comma-separated field list
     * @param string $indexType  KEY | UNIQUE | PRIMARY
     * @return bool
     */
    public function addIndex(string $table, string $indexName, string $fields, string $indexType = 'KEY'): bool
    {
       $this->error = '';
       $table  = $this->prep($table);
       $cols   = '`' . implode('`,`', array_map('trim', explode(',', $fields))) . '`';
       $isPrimary = strtoupper($indexType) === 'PRIMARY';

       // Drop existing index first (ignore error if absent)
       $this->removeIndex($table, $indexName);

       if ($this->driver === 'sqlite') {
           // SQLite: PRIMARY KEY cannot be added after table creation
           if ($isPrimary) {
               $this->error = 'SQLite does not support adding PRIMARY KEY after table creation';
               return false;
           }
           $unique = (strtoupper($indexType) === 'UNIQUE') ? 'UNIQUE ' : '';
           $sql = "CREATE {$unique}INDEX IF NOT EXISTS `$indexName` ON `$table` ($cols)";
       } else {
           if ($isPrimary) {
               $sql = "ALTER TABLE `$table` ADD PRIMARY KEY ($cols)";
           } else {
               $type = strtoupper($indexType) === 'UNIQUE' ? 'UNIQUE' : 'INDEX';
               $sql = "ALTER TABLE `$table` ADD $type `$indexName` ($cols)";
           }
       }

       try {
           $this->pdo->exec($sql);
           return true;
       } catch (PDOException $e) {
           $this->error = $e->getMessage();
           return false;
       }
    }

    /**
     * Remove an index from a table. Returns true if removed or already absent.
     *
     * @param string $table      Table name (supports {TP} placeholder)
     * @param string $indexName  Index name
     * @return bool
     */
    public function removeIndex(string $table, string $indexName): bool
    {
       $this->error = '';
       $table = $this->prep($table);

       if ($this->driver === 'sqlite') {
           $sql = "DROP INDEX IF EXISTS `$indexName`";
       } else {
           // Check existence first — DROP INDEX on missing index is an error in MySQL
           $stmt = $this->pdo->prepare("SHOW INDEX FROM `$table` WHERE Key_name = ?");
           $stmt->execute([$indexName]);
           if ($stmt->rowCount() === 0) return true; // already absent — not an error

           $sql = "ALTER TABLE `$table` DROP INDEX `$indexName`";
       }

       try {
           $this->pdo->exec($sql);
           return true;
       } catch (PDOException $e) {
           $this->error = $e->getMessage();
           return false;
       }
    }

    // ── Utility ───────────────────────────────────────────────────────────────────────────

    public function lastInsertId(): string|false
    {
        return $this->pdo->lastInsertId();
    }

    public function hasError(): bool
    {
        return $this->error !== '';
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function setError(string $message): void
    {
        $this->error = $message;
    }

    public function getDriver(): string
    {
        return $this->driver;
    }

    public function getPDO(): PDO
    {
        return $this->pdo;
    }    
    
    // ── SQL import ───────────────────────────────────────────────────────────────────────

    /**
     * Executes one or more SQL statements from a file or a raw string.
     *
     * The entire import runs inside a transaction. If any statement fails,
     * the transaction is rolled back and the error is recorded in the last
     * result entry.
     *
     * Source SQL is assumed to be written for MySQL/MariaDB. When SQLite is
     * active, normalizeSql() rewrites the content to be compatible before
     * splitting into individual statements. The normalisation order matters:
     *
     *   1. Strip MySQL-only SET session variables (SET SQL_MODE = ..., etc.)
     *   2. Remove MySQL-only table options (ENGINE, CHARSET, COLLATE, UNSIGNED ...)
     *   3. Replace ENUM with TEXT
     *   4. Resolve TINYINT(1) to INTEGER via a temporary placeholder
     *   5. Rename integer type aliases longest-first to avoid partial matches;
     *      display-width suffixes like (11) are consumed by the pattern
     *   6. Rewrite AUTO_INCREMENT: promote to inline INTEGER PRIMARY KEY AUTOINCREMENT
     *      and remove the now-redundant table-level PRIMARY KEY clause
     *   7. Rewrite date/time types to TEXT; backtick-quoted column names like
     *      `timestamp` or `date_format` are protected from replacement
     *   8. Clean up stray trailing commas left after option removal
     *
     * Placeholder substitution ({TABLE_PREFIX}, {TP}, {TABLE_ENGINE},
     * {TABLE_COLLATION}, and any custom prefixes) is applied per-statement
     * after normalisation, just before execution.
     *
     * @param  string        $sqlSource         Path to a .sql file, or a raw SQL string.
     * @param  string|null   $customPrefix      Overrides {TABLE_PREFIX} and {TP} if set.
     * @param  bool          $preserveExisting  When true, DROP TABLE statements are skipped.
     * @param  string|null   $engine            MySQL ENGINE clause, e.g. 'ENGINE=InnoDB'.
     * @param  string|null   $collation         MySQL CHARSET/COLLATE clause.
     * @param  callable|null $progress          Optional callback(array $result, int $index, int $total).
     * @return array  One entry per executed statement:
     *                ['statement' => string, 'ok' => bool, 'msg' => string]
     */
    public function importSql(
        string      $sqlSource,
        ?string     $customPrefix     = null,
        bool        $preserveExisting = true,
        ?string     $engine           = null,
        ?string     $collation        = null,
        ?callable   $progress         = null
    ): array {

        // 1. Load source
        if (is_file($sqlSource) && is_readable($sqlSource)) {
            $content    = file_get_contents($sqlSource);
            $sourceName = basename($sqlSource);
            if ($content === false) {
                return [['statement' => $sourceName, 'ok' => false, 'msg' => 'Cannot read file']];
            }
        } else {
            $content    = trim($sqlSource);
            $sourceName = 'raw SQL';
            if ($content === '') {
                return [['statement' => $sourceName, 'ok' => false, 'msg' => 'Empty SQL input']];
            }
            if (!preg_match('/^(CREATE|ALTER|DROP|INSERT|UPDATE|DELETE|REPLACE|TRUNCATE|SET|PRAGMA)/i', $content)) {
                return [['statement' => $sourceName, 'ok' => false, 'msg' => 'Input does not look like SQL']];
            }
        }

        // 2. Strip SQL comments — before normalisation and before splitting
        $content = preg_replace('/-{2}[^\n]*/', '', $content);
        $content = preg_replace('/\/\*.*?\*\//s', '', $content);
        $content = trim($content);

        // 3. SQLite normalisation
        if ($this->driver === 'sqlite') {
            $content = $this->normalizeSql($content);
        }

        // 4. Split into individual statements
        $statements = array_values(array_filter(
            array_map('trim', preg_split('/;\s*(?=\n|$)/m', $content)),
            static fn(string $s): bool => $s !== ''
        ));

        $total = count($statements);
        if ($total === 0) {
            return [['statement' => $sourceName, 'ok' => false, 'msg' => 'No valid statements found']];
        }

        // 5. Build placeholder replacement map
        $replacements = $this->prefixes;

        if ($customPrefix !== null && $customPrefix !== '') {
            $replacements['{TABLE_PREFIX}'] = $customPrefix;
            $replacements['{TP}']           = $customPrefix;
        }

        $effectiveEngine    = ($engine    !== null && $engine    !== '') ? $engine    : 'ENGINE=InnoDB';
        $effectiveCollation = ($collation !== null && $collation !== '') ? $collation : 'DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

        if ($this->driver === 'mysql') {
            $replacements['{TABLE_ENGINE}']    = ' ' . $effectiveEngine;
            $replacements['{TABLE_COLLATION}'] = ' ' . $effectiveCollation;
        } else {
            $replacements['{TABLE_ENGINE}']    = '';
            $replacements['{TABLE_COLLATION}'] = '';
        }

        // 6. Execute statements
        //
        // MySQL/MariaDB: DDL statements (CREATE TABLE, DROP TABLE, ALTER TABLE)
        // cause an IMPLICIT COMMIT and cannot be wrapped in a transaction.
        // Using beginTransaction() around DDL leads to "There is no active
        // transaction" errors when PDO tries to commit/rollback afterward.
        //
        // SQLite supports transactional DDL — we use a transaction there
        // to get atomicity. For MySQL we execute each statement individually.
        //
        // Per-statement execution means: one failure does NOT abort the rest.
        // Each statement is reported independently via the $progress callback.

        $results      = [];
        $useTxn       = ($this->driver === 'sqlite');
        $hasFatalError = false;

        if ($useTxn) {
            $this->pdo->beginTransaction();
        }

        foreach ($statements as $index => $rawSql) {
            $sql = strtr($rawSql, $replacements);
            $sql = trim($sql);

            if ($sql === '') continue;

            // Skip DROP TABLE in preserve mode
            if ($preserveExisting && preg_match('/^DROP\s+TABLE/i', $sql)) {
                $r = ['statement' => 'DROP TABLE (skipped)', 'ok' => true, 'msg' => 'Skipped — preserve mode'];
                $results[] = $r;
                if ($progress) $progress($r, $index, $total);
                continue;
            }

            // Build a short human-readable label for the result entry
            preg_match(
                '/(?:CREATE\s+TABLE(?:\s+IF\s+NOT\s+EXISTS)?\s+["`]?(\w+)["`]?'
                . '|INSERT\s+(?:INTO\s+)?["`]?(\w+)["`]?'
                . '|ALTER\s+TABLE\s+["`]?(\w+)["`]?'
                . '|UPDATE\s+["`]?(\w+)["`]?'
                . '|DELETE\s+FROM\s+["`]?(\w+)["`]?'
                . '|DROP\s+TABLE\s+["`]?(\w+)["`]?)/i',
                $sql, $m
            );
            $label = $m[1] ?? $m[2] ?? $m[3] ?? $m[4] ?? $m[5] ?? $m[6] ?? substr($sql, 0, 60);

            try {
                $affected  = $this->pdo->exec($sql);
                $execError = false;

                if ($affected === false) {
                    $info      = $this->pdo->errorInfo();
                    $execError = $info[2] ?? 'Execution failed (SQLSTATE ' . ($info[0] ?? '?') . ')';
                }
            } catch (PDOException $e) {
                $affected  = false;
                $execError = $e->getMessage();
            }

            if ($execError !== false) {
                $this->error    = $execError;
                $hasFatalError  = true;
                $r = ['statement' => $label, 'ok' => false, 'msg' => $execError];
                $results[] = $r;
                if ($progress) $progress($r, $index, $total);

                // SQLite: abort the transaction on first error
                if ($useTxn) {
                    $this->pdo->rollBack();
                    $r = ['statement' => 'Transaction rolled back', 'ok' => false, 'msg' => $execError];
                    $results[] = $r;
                    if ($progress) $progress($r, -1, $total);
                    return $results;
                }

                // MySQL: continue with remaining statements (DDL can't be rolled back anyway)
                continue;
            }

            $r = ['statement' => $label, 'ok' => true, 'msg' => "OK (rows: $affected)"];
            $results[] = $r;
            if ($progress) $progress($r, $index, $total);
        }

        if ($useTxn && $this->pdo->inTransaction()) {
            $this->pdo->commit();
        }

        return $results;
    }

    /**
     * Normalises MySQL-dialect SQL for SQLite.
     *
     * Called once on the entire source content before it is split into
     * individual statements. All transformations are regex-based and must
     * be ordered carefully to avoid partial matches (e.g. BIGINT before INT).
     *
     * @param  string $content  Raw SQL content, comments already stripped.
     * @return string           Normalised SQL content.
     */
    private function normalizeSql(string $content): string
    {
        // FIX 1: Strip MySQL-only SET session variable statements (e.g. SET SQL_MODE = ...)
        // SQLite does not understand these and would abort the transaction.
        $content = preg_replace('/\bSET\s+\w+\s*=\s*[^;]+/i', '', $content);

        // Remove MySQL-only table and column options.
        // [^\s;]+ (not \S+) so a directly-attached statement terminator like
        // `ENGINE=MyISAM;` keeps its `;` — \S+ greedily swallowed it too,
        // silently merging that CREATE TABLE with whatever statement came
        // next in a multi-statement file (only visible once a file actually
        // had more than one statement after the stripped clause).
        $content = preg_replace('/\s*ENGINE\s*=\s*[^\s;]+/i',                  '', $content);
        $content = preg_replace('/\s*(DEFAULT\s+)?CHARSET\s*=\s*[^\s;]+/i',    '', $content);
        $content = preg_replace('/\s*COLLATE\s*=\s*[^\s;]+/i',                 '', $content);
        // Column-level "CHARACTER SET x [COLLATE y]" (no "=" — distinct from the
        // table-level CHARSET=/COLLATE= above). Common in older per-column DDL,
        // e.g. `name` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci.
        // The collation name is sometimes quoted (COLLATE 'latin1_swedish_ci'),
        // sometimes bare (COLLATE utf8_unicode_ci) — '?\w+'? handles both.
        $content = preg_replace('/\s*CHARACTER\s+SET\s+\w+/i',             '', $content);
        $content = preg_replace("/\\s*COLLATE\\s+'?\\w+'?/i",              '', $content);
        $content = preg_replace('/\s*AUTO_INCREMENT\s*=\s*\d+/i',          '', $content);
        $content = preg_replace('/\s+UNSIGNED\b/i',                        '', $content);
        $content = preg_replace('/\s+ZEROFILL\b/i',                        '', $content);
        // Index-algorithm hint on PRIMARY KEY/INDEX/KEY clauses, e.g.
        // `PRIMARY KEY (\`id\`) USING BTREE` — must run before the PRIMARY
        // KEY / INDEX stripping below, which don't expect trailing text
        // after the closing paren and would otherwise leave "USING BTREE"
        // dangling in the output (a syntax error on SQLite).
        $content = preg_replace('/\s+USING\s+(?:BTREE|HASH)\b/i',          '', $content);
        $content = preg_replace('/\s+ON\s+UPDATE\s+CURRENT_TIMESTAMP\b/i', '', $content);

        // ENUM -> TEXT (before integer replacements)
        $content = preg_replace('/\bENUM\s*\([^)]+\)/i', 'TEXT', $content);

        // Boolean: TINYINT(1) -> placeholder, resolved after other INT replacements
        $content = preg_replace('/\bTINYINT\s*\(\s*1\s*\)/i', '__DD_BOOL__', $content);

        // FIX 2: Integer type aliases — use (?!\w) so the display-width (N) is consumed.
        // The old \b anchor left "INTEGER(11)" which SQLite rejects with AUTOINCREMENT.
        $content = preg_replace('/\bMEDIUMINT\b/i',                             'INTEGER',  $content);
        $content = preg_replace('/\bBIGINT(?:\s*\(\s*\d+\s*\))?(?!\w)/i',       'INTEGER',  $content);
        $content = preg_replace('/\bTINYINT(?:\s*\(\s*\d+\s*\))?(?!\w)/i',      'SMALLINT', $content);
        $content = preg_replace('/\bINT(?:EGER)?(?:\s*\(\s*\d+\s*\))?(?!\w)/i', 'INTEGER',  $content);

        // Inline AUTO_INCREMENT PRIMARY KEY (already combined on the column definition)
        $content = preg_replace(
            '/\bINTEGER(\s+NOT\s+NULL)?\s+AUTO_INCREMENT\s+PRIMARY\s+KEY\b/i',
            'INTEGER PRIMARY KEY AUTOINCREMENT',
            $content
        );

        // FIX 3: AUTO_INCREMENT on a column whose PRIMARY KEY is declared separately.
        // SQLite requires AUTOINCREMENT to be on an INTEGER PRIMARY KEY column inline.
        // Step A: promote the column to inline INTEGER PRIMARY KEY AUTOINCREMENT
        $content = preg_replace(
            '/(`\w+`)\s+INTEGER\s+(?:NOT\s+NULL\s+)?AUTO_INCREMENT(?!\s+PRIMARY)/i',
            '$1 INTEGER PRIMARY KEY AUTOINCREMENT',
            $content
        );
        // Step B: drop the table-level PRIMARY KEY clause ONLY when it is now
        // redundant — i.e. Step A actually inlined that same column as
        // `col` INTEGER PRIMARY KEY AUTOINCREMENT. Previously this stripped
        // ANY single-column PRIMARY KEY clause unconditionally, which silently
        // deleted the primary key entirely for non-integer/non-autoincrement
        // keys (e.g. a VARCHAR `id` PRIMARY KEY) that Step A never touched —
        // leaving the table with no uniqueness constraint at all.
        // Backticks around the column name are optional here — MySQL allows
        // both `PRIMARY KEY (col)` and `PRIMARY KEY (\`col\`)`; requiring
        // backticks meant the unquoted form was left in place alongside the
        // now-inlined PK, producing "table has more than one primary key".
        $content = preg_replace_callback(
            '/,\s*\n?\s*PRIMARY\s+KEY\s*\(\s*`?(\w+)`?\s*\)/i',
            function (array $m) use ($content): string {
                $inlined = preg_match(
                    '/`' . preg_quote($m[1], '/') . '`\s+INTEGER\s+PRIMARY\s+KEY\s+AUTOINCREMENT\b/i',
                    $content
                );
                return $inlined ? '' : $m[0];
            },
            $content
        );

        // Remaining bare AUTO_INCREMENT (safety fallback — should not appear after above)
        $content = preg_replace('/\bAUTO_INCREMENT\b/i', 'AUTOINCREMENT', $content);

        // Resolve the boolean placeholder
        $content = str_replace('__DD_BOOL__', 'INTEGER', $content);

        // FIX 4: Date/time type replacement — protect backtick-quoted column names.
        // (?<!`) prevents replacing names like `timestamp`, `date_format`, `time_format`.
        $content = preg_replace('/(?<!`)DATETIME(?!`)/i',  'TEXT', $content);
        $content = preg_replace('/(?<!`)TIMESTAMP(?!`)/i', 'TEXT', $content);
        $content = preg_replace('/(?<!`)DATE(?!`)/i',      'TEXT', $content);
        $content = preg_replace('/(?<!`)TIME(?!`)/i',      'TEXT', $content);

        // Strip inline INDEX/KEY declarations (not supported in SQLite CREATE TABLE).
        // The index name is optional — MySQL allows the unnamed short form
        // `INDEX (col)` in addition to `INDEX name (col)`. The name pattern
        // uses [^`\s(]+ rather than \w+ because constraint/index names often
        // embed the {TP}/{TABLE_PREFIX} placeholder (e.g. `FK_{TP}posts_img_
        // {TP}img`) — normalizeSql() runs BEFORE importSql() substitutes that
        // placeholder, and \w+ can't span the literal "{"/"}" characters, so
        // the whole alternation failed to match and left the KEY clause (and
        // everything after it, including real FOREIGN KEY constraints) in
        // the output verbatim — a syntax error on SQLite.
        $content = preg_replace(
            '/,\s*(?:UNIQUE\s+)?(?:INDEX|KEY)\s*(?:`?[^`\s(]+`?\s*)?\([^)]+\)/i',
            '',
            $content
        );

        // Strip MySQL GENERATED/VIRTUAL columns (SQLite does not support them).
        // The expression `\([^()]*(?:\([^()]*\)[^()]*)*\)` allows one level of
        // nesting (e.g. `AS (FROM_UNIXTIME(\`col\`)) VIRTUAL`) — the previous
        // `\([^)]+\)` stopped at the first inner `)`, leaving a dangling
        // ` VIRTUAL`/`STORED` keyword that SQLite rejected as an unknown
        // table option.
        $content = preg_replace(
            '/,\s*`\w+`[^,]+(?:GENERATED\s+ALWAYS\s+AS|AS)\s*\([^()]*(?:\([^()]*\)[^()]*)*\)\s*(?:STORED|VIRTUAL)?/i',
            '',
            $content
        );

        // Clean up trailing commas before closing parenthesis
        $content = preg_replace('/,\s*\)/s', ')', $content);

        // Collapse whitespace runs introduced by the removals above
        $content = trim(preg_replace('/[ \t]+/', ' ', $content));

        return $content;
    }


    /**
     * Legacy method for backward compatibility.
     * Many old modules and install scripts still call $database->SqlImport().
     * This wrapper calls the new importSql() internally and returns a simple bool.
     */
    public function SqlImport(
       $sSqlDump,
       $sTablePrefix = '',
       $bPreserve = true,
       $sTblEngine = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci',
       $sTblCollation = ' collate utf8_unicode_ci'
    ) {
       if (defined('PDO_CANONICAL_DEBUG') && PDO_CANONICAL_DEBUG) {
           trigger_error(
               "Database::SqlImport() is deprecated — use importSql() instead. " .
               "The new method returns a detailed result array.",
               E_USER_DEPRECATED
           );
       }

       $result = $this->importSql(
           $sSqlDump,
           $sTablePrefix,
           $bPreserve,
           $sTblEngine,
           $sTblCollation
       );

       // Emulate old behavior: return bool
       foreach ($result as $item) {
           if (!$item['ok']) {
               $this->error = $item['msg'] ?? 'Unknown error during SQL import';
               return false;
           }
       }
       return true;
    }

    /**
     * Returns the storage engine of a table (e.g. 'InnoDB', 'MyISAM').
     *
     * @param  string       $table  Table name including prefix
     * @return string|false         Engine name on success, false on error
     */
    public function getTableEngine(string $table): string|false
    {
        if (defined('DB_TYPE') && DB_TYPE === 'sqlite') {
            // SQLite has no storage engines — return a fixed sentinel value
            // so callers that check for 'InnoDB' or 'MyISAM' get a defined result
            return 'SQLite';
        }
        $result = $this->query(
            'SELECT `ENGINE` FROM `information_schema`.`TABLES`
             WHERE `TABLE_SCHEMA` = DATABASE()
               AND `TABLE_NAME`   = ?',
            [$this->prep($table)]
        );

        if (!$result || $this->hasError()) {
            return false;
        }

        $row = $result->fetchRow();
        return $row ? ($row['ENGINE'] ?? $row[0] ?? false) : false;
    }
    
    
    /**
     * @deprecated Use PDO parameter binding instead
     */
    public function escapeString(?string $value): string   
    {
        if (defined('PDO_CANONICAL_DEBUG') && PDO_CANONICAL_DEBUG) {
            trigger_error(
                'Database::escapeString() is deprecated — use PDO parameter binding.',
                E_USER_DEPRECATED
            );
        }

        $value = $value ?? ''; // Handle null gracefully


        if ($this->driver === 'sqlite') {
            return str_replace("'", "''", $value);
        }

       return substr($this->pdo->quote($value), 1, -1);
    }

    // ── JSON column helpers ──────────────────────────────────────────────────────────────
    //
    // Driver-transparent JSON helpers (MySQL/MariaDB + SQLite). Originally
    // developed as modules/DynamicFields/DatabaseJson.php (a Database
    // subclass); merged directly into Database itself so every caller of
    // the shared $database instance gets them for free — no second PDO
    // connection, no separately-registered {TP_xxx} prefixes to keep in
    // sync with a second instance. modules/DynamicFields/DatabaseJson.php
    // is now a trivial empty subclass kept only for backward compatibility.
    //
    // The fragment builders (jsonGet/jsonSet/jsonArrayContains/jsonMerge)
    // return SQL text to embed in a query — they never execute anything
    // themselves. jsonSave()/jsonRead() are the high-level, single-row
    // read/merge-write helpers built on top of them.

    /**
     * Return a SQL fragment that extracts a scalar value from a JSON column.
     *
     * The result is unquoted — suitable for numeric comparisons and ORDER BY.
     *
     *   MySQL:  JSON_UNQUOTE(JSON_EXTRACT(`col`, 'path'))
     *   SQLite: json_extract(col, 'path')
     *
     * Example:
     *   "... WHERE " . $database->jsonGet('xfields', '$.php_min') . " >= ?"
     *
     * @param string $column  Column name (will be backtick-quoted)
     * @param string $path    JSONPath expression, e.g. '$.php_min'
     * @return string         SQL fragment
     */
    public function jsonGet(string $column, string $path): string
    {
        $col = "`$column`";

        return match($this->driver) {
            'sqlite' => "json_extract($col, '$path')",
            default  => "JSON_UNQUOTE(JSON_EXTRACT($col, '$path'))",
        };
    }

    /**
     * Return a SQL fragment that sets a single path inside a JSON column.
     *
     * Use in UPDATE ... SET col = jsonSet(...) for targeted path updates.
     * For saving all fields at once use jsonSave() instead.
     *
     *   MySQL:  JSON_SET(`col`, 'path', CAST(? AS JSON))
     *   SQLite: json_set(col, 'path', ?)
     *
     * The caller must bind the value as a query parameter (?).
     *
     * @param string $column  Column name
     * @param string $path    JSONPath expression, e.g. '$.php_min'
     * @return string         SQL fragment (contains one ? placeholder for the value)
     */
    public function jsonSet(string $column, string $path): string
    {
        $col = "`$column`";

        return match($this->driver) {
            'sqlite' => "json_set($col, '$path', ?)",
            default  => "JSON_SET($col, '$path', CAST(? AS JSON))",
        };
    }

    /**
     * Return a SQL fragment that tests whether a JSON array contains a value.
     *
     * The value is embedded as a JSON-encoded literal — not as a bound parameter,
     * because JSON_CONTAINS / json_each require a literal for the search value.
     * The value is therefore sanitized via json_encode().
     *
     *   MySQL:  JSON_CONTAINS(`col`->'path', '"value"')
     *   SQLite: EXISTS(SELECT 1 FROM json_each(json_extract(col,'path'))
     *                  WHERE value = 'value')
     *
     * @param string $column  Column name
     * @param string $path    JSONPath to the array, e.g. '$.types'
     * @param string $value   Scalar value to search for
     * @return string         SQL fragment (no additional ? placeholder)
     */
    public function jsonArrayContains(string $column, string $path, string $value): string
    {
        $col     = "`$column`";
        $safeVal = json_encode($value);   // adds surrounding quotes, escapes chars

        return match($this->driver) {
            'sqlite' =>
                "EXISTS(SELECT 1 FROM json_each(json_extract($col, '$path'))" .
                " WHERE value = " . json_encode($value, JSON_UNESCAPED_UNICODE) . ")",
            default  =>
                "JSON_CONTAINS($col->'$path', $safeVal)",
        };
    }

    /**
     * Return a SQL fragment that merges a JSON object into a JSON column.
     *
     * JSON_MERGE_PATCH / json_patch replaces top-level keys from the new object
     * while preserving any keys that are not mentioned.
     *
     * The caller must bind the JSON string as a ? parameter.
     *
     *   MySQL:  JSON_MERGE_PATCH(`col`, ?)
     *   SQLite: json_patch(col, ?)
     *
     * Prefer jsonSave() for the common save-all-fields use case.
     *
     * @param string $column  Column name
     * @return string         SQL fragment (one ? placeholder for the JSON string)
     */
    public function jsonMerge(string $column): string
    {
        $col = "`$column`";

        return match($this->driver) {
            'sqlite' => "json_patch($col, ?)",
            default  => "JSON_MERGE_PATCH($col, ?)",
        };
    }

    /**
     * Save a set of field values into the JSON column of a single row.
     *
     * Merges $fields into the existing JSON object — keys present in $fields
     * are updated or added; keys not mentioned are left untouched.
     * Pass null for a key's value to remove it from the JSON object.
     *
     * Automatically casts values using castForJson() so the JSON column stores
     * proper typed values (numbers as numbers, booleans as booleans).
     *
     * Example:
     *   $database->jsonSave('{TP_FORUM}extensions', 'config', 'id', $extId, [
     *       'max_reactions_per_post' => 5,
     *       'allowed_emoji'          => ['+1', 'heart'],
     *   ]);
     *
     * @param string     $table    Table name (supports {TP}/custom prefixes)
     * @param string     $column   JSON column name
     * @param string     $pkCol    Primary key column name
     * @param int|string $pkValue  Primary key value
     * @param array      $fields   Associative array of field name → value
     * @param array      $schema   Optional field schema for type casting
     *                             ['fieldName' => ['type' => 'number', ...], ...]
     * @return bool                true on success
     */
    public function jsonSave(
        string     $table,
        string     $column,
        string     $pkCol,
        int|string $pkValue,
        array      $fields,
        array      $schema = []
    ): bool {
        if (empty($fields)) return true;

        // Cast each value according to its field type
        $casted = [];
        foreach ($fields as $key => $value) {
            $type         = $schema[$key]['type'] ?? null;
            $casted[$key] = $this->castForJson($value, $type);
        }

        $sql = "UPDATE `" . $this->prep($table) . "`
                SET `$column` = " . $this->jsonMerge($column) . "
                WHERE `$pkCol` = ?";

        $this->query($sql, [json_encode($casted, JSON_UNESCAPED_UNICODE), $pkValue]);

        return !$this->hasError();
    }

    /**
     * Read all field values from the JSON column of a single row.
     *
     * Returns the decoded JSON object as an associative array, or an empty
     * array if the row does not exist or the column is NULL.
     *
     * Example:
     *   $fields = $database->jsonRead('{TP_FORUM}extensions', 'config', 'id', $extId);
     *
     * @param string     $table   Table name
     * @param string     $column  JSON column name
     * @param string     $pkCol   Primary key column
     * @param int|string $pkValue Primary key value
     * @return array              Decoded field values (empty array if not found)
     */
    public function jsonRead(
        string     $table,
        string     $column,
        string     $pkCol,
        int|string $pkValue
    ): array {
        $raw = $this->fetchValue(
            "SELECT `$column` FROM `" . $this->prep($table) . "` WHERE `$pkCol` = ?",
            [$pkValue]
        );

        if (empty($raw)) return [];

        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Cast a value to the appropriate PHP type for JSON storage.
     *
     * Without schema info the value is returned as-is (json_encode handles it).
     * With a field type the value is cast so the JSON column stores proper types:
     *   number     → float
     *   int        → int
     *   bool/check → bool
     *   array      → array (json_decode if string)
     *   everything else → string (trimmed)
     *
     * @param mixed       $value      Raw value (typically from $_POST)
     * @param string|null $fieldType  Field type string from schema, or null
     * @return mixed                  Cast value ready for json_encode()
     */
    public function castForJson(mixed $value, ?string $fieldType): mixed
    {
        if ($value === null || $value === '') return null;

        return match($fieldType) {
            'number', 'price'       => (float) $value,
            'int'                   => (int)   $value,
            'checkbox', 'bool'      => (bool)  $value,
            'multiselect', 'array'  => is_array($value)
                                        ? $value
                                        : (json_decode($value, true) ?? []),
            default                 => is_string($value) ? trim($value) : $value,
        };
    }
}

// ── DatabaseResult Wrapper ──────────────────────────────────────────────────────────────

class DatabaseResult
{
    private array $rows    = [];
    private int   $pointer = 0;

    public function __construct(?PDOStatement $stmt)
    {
        if ($stmt !== null) {
            $this->rows = $stmt->fetchAll(PDO::FETCH_BOTH);
        }
    }

    /**
     * Builds a one-row, one-column result without a real PDOStatement —
     * used by query()'s SQLite LAST_INSERT_ID() shim to answer in the same
     * shape a real `SELECT LAST_INSERT_ID()` would have (row[0] and
     * row['LAST_INSERT_ID()'] both set, matching PDO::FETCH_BOTH).
     */
    public static function fromSingleValue(mixed $value): self
    {
        $result = new self(null);
        $result->rows = [[0 => $value, 'LAST_INSERT_ID()' => $value]];
        return $result;
    }

    public function fetchRow(int $type = MYSQLI_BOTH): array|false
    {
        if ($this->pointer >= count($this->rows)) return false;
        $row = $this->rows[$this->pointer++];
        return match($type) {
            MYSQLI_ASSOC => array_filter($row, 'is_string', ARRAY_FILTER_USE_KEY),
            MYSQLI_NUM   => array_filter($row, 'is_int',    ARRAY_FILTER_USE_KEY),
            default      => $row,
        };
    }

    public function numRows(): int  { return count($this->rows); }
    public function seekRow(int $p = 0): void { $this->pointer = max(0, min($p, count($this->rows) - 1)); }
    public function rewind(): void  { $this->pointer = 0; }
    public function error(): string { return ''; }
}

// MYSQLI constants
defined('MYSQLI_ASSOC') or define('MYSQLI_ASSOC', 1);
defined('MYSQLI_NUM')   or define('MYSQLI_NUM',   2);
defined('MYSQLI_BOTH')  or define('MYSQLI_BOTH',  3);
