<?php
/**
 * WBCE CMS
 * Way Better Content Editing.
 * Visit https://wbce.org to learn more and to join the community.
 *
 * @copyright Ryan Djurovich (2004-2009)
 * @copyright WebsiteBaker Org. e.V. (2009-2015)
 * @copyright WBCE Project (2015-)
 * @license GNU GPL2 (or any later version)
 */

/**
 * Class database
 * ==============
 * This class will be used to interface with the database and the WBCE CMS code
 */

//no direct file access
if (count(get_included_files()) == 1) {
    die(header("Location: ../index.php", true, 301));
}

use Doctrine\Common\ClassLoader;

require dirname(__FILE__) . '/../include/Doctrine/Common/ClassLoader.php';

// define the old mysql constants for backward compatibility
if (!defined('MYSQL_CLIENT_COMPRESS')) {
    define('MYSQL_CLIENT_COMPRESS', 32);
    define('MYSQL_CLIENT_IGNORE_SPACE', 256);
    define('MYSQL_CLIENT_INTERACTIVE', 1024);
    define('MYSQL_CLIENT_SSL', 2048);
}

class database
{
    private static $obj = null;
    private $db_handle = null;    // readonly from outside
    private $db_name = '';
    private $connected = false;
    private $sCharset = '';
    private $error = '';
    private $error_type = '';
    private $message = array();
    private $_prefixes = array();
    private $classLoader = null;

    /**
     * constructor
     *
     * @access    public
     * @param array   - optional options to be passed to connect()
     * @return    object
     **/
    public function __construct(?array $opt = array())
    {
        if (empty(self::$obj)) {
            if (!$this->classLoader) {
                $this->classLoader = new ClassLoader('Doctrine', dirname(__FILE__) . '/../include');
                $this->classLoader->register();
            }
            $this->_prefixes = $this->_tablePrefixes();
            $this->connect($opt);
        }
        return self::$obj;
    }   // end function __construct()

    /**
     * _tablePrefixes()
     * =========================
     * default Prefix tokens
     * More Prefixes can be added using the public addPrefix() method
     *
     * @return array
     */
    private function _tablePrefixes()
    {
        return array(
            '{TABLE_PREFIX}' => TABLE_PREFIX,
            '{TP}' => TABLE_PREFIX, // shorthand
        );
    }   // end function __get()

    /**
     * establish a database connection; uses \Doctrine\DBAL\DriverManager
     *
     * @access    public
     * @return    bool
     **/
    public function connect(): bool
    {
        $this->sCharset = strtolower(preg_replace('/[^a-z0-9]/i', '', (defined('DB_CHARSET') ? DB_CHARSET : '')));

        if (defined('DB_PORT')) {
            $port = DB_PORT;
        } else {
            $port = ini_get('mysqli.default_port');
        }
        // for debugging purposes
        $config = new \Doctrine\DBAL\Configuration();
        $config->setSQLLogger(new Doctrine\DBAL\Logging\DebugStack());
        $connectionParams = array(
            'charset' => $this->sCharset,
            'driver' => 'pdo_mysql',
            'dbname' => DB_NAME,
            'host' => DB_HOST,
            'password' => DB_PASSWORD,
            'user' => DB_USERNAME,
            'port' => $port,
        );

        try {
            $this->db_handle = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
            $this->db_name = DB_NAME;
            $this->connected = true;
            //added cause of problems whith mysql strict mode
            if (!defined('USE_MYSQL_STRICT') || USE_MYSQL_STRICT === false) {
                $this->db_handle->query("SET @@sql_mode=''");
            }
        } catch (\PDO\PDOException $e) {
            $this->connected = false;
            $this->error = $e->getMessage();
        }
        return $this->connected;
    }   // end function addPrefix()

    /**
     * default Getter for some properties
     *
     * @param string $sPropertyName
     * @return mixed NULL on error or missing property
     */
    public function __get($sPropertyName)
    {
        switch ($sPropertyName) {
            case 'db_handle':
            case 'DbHandle':
                $retval = $this->db_handle;
                break;
            case 'db_name':
            case 'DbName':
                $retval = $this->db_name;
                break;
            default:
                $retval = null;
                break;
        }
        return $retval;
    }   // end function connect()

    /**
     * addPrefix()
     * =========================
     *
     * Makes it possible to add additional DB table prefixes.
     * You can simply add new prefix/placeholder pairs from
     * inside modules, plugins or what have you...
     *
     * Example usage: $database->addPrefix('{DROPLETS_TABLE}', TABLE_PREFIX.'mod_droplets');
     *
     * @param string $sPrefixPH
     * @param string $sValue
     *
     * @return bool    bool false if empty values given; otherwise adds to prefixes array
     */
    public function addPrefix(?string $sPrefixPH = "", ?string $sValue = ""): bool
    {
        if ($sPrefixPH != "" && $sValue != "") {
            $aNewSet = array(
                $sPrefixPH => $sValue
            );
            $this->_prefixes += $aNewSet;
            return true;
        } else {
            return false;
        }
    }   // end function delRow()

    /**
     * delRow() (Delete row)
     * =====================
     *
     * Example usage: $database->delRow('{TP}mod_mymod', 'entry_id', '5');                  // single row
     * Example usage: $database->delRow('{TP}mod_mymod', 'entry_id', array('3', '6', '9')); // multiple rows
     *
     * @param string $table
     * @param string $refKey reference key (column)
     * @param unspec $mRows may be a string or integer OR an array of rows
     *
     * @return unspec  bool true on completion, otherwise error string
     */
    public function delRow(string $table, ?string $refKey = '', $uRows)
    {
        $retVal = false;
        if (!is_array($uRows)) {
            $uRows = array($uRows);
        }
        if (!empty($uRows)) {
            foreach ($uRows as $key) {
                $sqlRowCheck = sprintf("SELECT COUNT(*) FROM `%s` WHERE `%s` = '%s'", $table, $refKey, $key);
                if ($this->get_one($sqlRowCheck)) {
                    $strQuery = sprintf("DELETE FROM `%s` WHERE `%s` = '%s'", $table, $refKey, $key);
                }
                if ($this->query($strQuery)) {
                    $retVal = true;
                } else {
                    $retVal = $this->get_error();
                }
            }
        }
        return $retVal;
    }   // end function disconnect()

    /**
     *
     **/
    public function get_one(string $statement)
    {
        $statement = $this->replaceTablePrefix($statement);
        try {
            $q = $this->db_handle->query($statement);

            if (!empty($q) && is_object($q)) {
                $r = $q->fetchColumn();
                return (empty($r) ? null : $r);
            }
        } catch (\PDO\PDOException $e) {
            $this->set_error($e->getMessage());
            return null;
        }
    }   // end function escapeString()

    /**
     * replaceTablePrefix()
     * =========================
     * Replace DB table prefix placeholders/tokens
     *
     * @param string $statement
     * @return string
     */
    protected function replaceTablePrefix($statement)
    {
        if (strpos($statement, '{') !== false) {
            $statement = strtr($statement, $this->_prefixes);
        }
        return $statement;
    }

    /**
     * execute database query
     *
     * @access   public
     * @param string $statement
     * @return   mixed     statement handle or null
     **/
    public function query(string $statement)
    {
        $statement = $this->replaceTablePrefix($statement);
        try {
            $stmt = $this->db_handle->query($statement);
            return new WBCE_PDOStatementDecorator($stmt);
        } catch (\PDO\PDOException $e) {
            $this->set_error($e->getMessage());
        } catch (\Exception $e) {
            $this->set_error($e->getMessage());
        }
        return null;
    }   // end function field_exists()

    /**
     * return last error
     *
     * @access    public
     * @return    string
     **/
    public function get_error()
    {
        return $this->error;
    }   // end function field_modify()

    /**
     * save error
     *
     * @access    public
     * @param string $message
     * @return    void
     **/
    public function set_error(?string $message = null)
    {
        $this->error = $message;
        $this->error_type = 'unknown';
    }   // end function field_remove()

    /**
     * disconnect
     *
     * @access    public
     * @return    bool
     **/
    public function disconnect(): bool
    {
        if ($this->connected == true) {
            $this->db_handle = null;
            $this->connected = false;
            return true;
        } else {
            return false;
        }
    }   // end function get_array()

    /**
     * Escapes special characters in a string for use in an SQL statement
     *
     * @param string $unescaped_string
     * @return   string
     */
    public function escapeString(?string $unescaped_string = null): string
    {
        if (!strlen($unescaped_string)) {
            return '';
        }
        if (is_array($unescaped_string)) {
            return array_map(__METHOD__, $unescaped_string);
        }
        if (!empty($unescaped_string) && is_string($unescaped_string)) {
            return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $unescaped_string);
        }
        return $unescaped_string;
    }   // end function get_error()

    /**
     * adds a column to a table
     *
     * @access    public
     * @param string $table_name : full name of the table (incl. TABLE_PREFIX / {TP})
     * @param string $field_name : name of the field to add
     * @param string $description : describes the new field like ( INT NOT NULL DEFAULT '0')
     * @param string set_error: decide whether an existing field should throw an error message or not
     * @return    bool: true if successful, otherwise false and error will be set
     */
    public function field_add(string $table_name, string $field_name, string $description, bool $set_error = true): bool
    {
        if (!$this->field_exists($table_name, $field_name)) {
            // add new field into a table
            $sql = 'ALTER TABLE `' . $table_name . '` ADD ' . $field_name . ' ' . $description . ' ';
            try {
                $query = $this->query($sql);
            } catch (\PDO\PDOException $e) {
                $this->set_error($e->getMessage());
            }
            if (!$this->is_error()) {
                return ($this->field_exists($table_name, $field_name));
            }
        } else {
            if ($set_error === true) {
                $this->set_error('field \'' . $field_name . '\' already exists');
            }
        }
        return false;
    }   // end functino get_one()

    /**
     * checks for a given field in the table
     *
     * @access    public
     * @param string $table_name : full name of the table (incl. TABLE_PREFIX / {TP})
     * @param string $field_name : name of the field to seek for
     * @return    bool: true if field exists
     **/
    public function field_exists(string $table_name, string $field_name): bool
    {
        $sql = "SHOW COLUMNS FROM `$table_name` LIKE '$field_name'";
        $result = $this->query($sql);
        if (!$result) {
            return false;
        }
        return ($result->rowCount() > 0);
    }   // end function getLastInsertId()

    /**
     * check for error(s)
     *
     * @access    public
     * @return    bool
     **/
    public function is_error(): bool
    {
        return (!empty($this->error));
    }   // end function getTableEngine()

    /**
     * modifies a table column
     *
     * @access    public
     * @param string $table_name : full name of the table (incl. TABLE_PREFIX / {TP})
     * @param string $field_name : name of the field to add
     * @param string $description : describes the new field like ( INT NOT NULL DEFAULT '0')
     * @return    bool: true if successful, otherwise false and error will be set
     */
    public function field_modify(string $table_name, string $field_name, string $description): bool
    {
        if ($this->field_exists($table_name, $field_name)) {
            // modify a existing field in a table
            $sql = 'ALTER TABLE `' . $table_name . '` MODIFY `' . $field_name . '` ' . $description;
            try {
                $query = $this->query($sql);
            } catch (\PDO\PDOException $e) {
                $this->set_error($e->getMessage());
            }
        }
        return !$this->is_error();
    }   // end function index_add()

    /**
     * remove a table column
     *
     * @access    public
     * @param string $table_name : full name of the table (incl. TABLE_PREFIX / {TP})
     * @param string $field_name : name of the field to remove
     * @return    bool: true if successful, otherwise false and error will be set
     */
    public function field_remove(string $table_name, string $field_name): bool
    {
        if ($this->field_exists($table_name, $field_name)) {
            $sql = 'ALTER TABLE `' . $table_name . '` DROP `' . $field_name . '`';
            try {
                $query = $this->query($sql);
            } catch (\PDO\PDOException $e) {
                $this->set_error($e->getMessage());
            }
        }
        return !$this->is_error();
    }   // end function index_exists()

    /**
     * Return queried array directly for immediate use
     *
     * @access    public
     * @param string $statement
     * @return    array
     **/
    public function get_array(string $statement): array
    {
        $aData = array();
        if ($resData = $this->query($statement)) {
            while ($rec = $resData->fetch()) {
                $aData[] = $rec;
            }
        }
        return $aData;
    }   // end function index_remove()

    /**
     * Last inserted Id
     * @return bool|int false on error, 0 if no record inserted
     */
    public function getLastInsertId()
    {
        return $this->db_handle->lastInsertId();
    }   // end function insertRow()

    /**
     * returns the type of the engine used for requested table
     *
     * @access public
     * @param string $table name of the table, including prefix
     * @return boolean/string false on error, or name of the engine (myIsam/InnoDb)
     */
    public function getTableEngine(string $table)
    {
        $retVal = false;
        $stmt = $this->db_handle->query('select version() as `version`');
        $mysqlVersion = $stmt->fetchColumn();
        $engineValue = (version_compare($mysqlVersion, '5.0') < 0) ? 'Type' : 'Engine';
        $sql = "SHOW TABLE STATUS FROM `" . $this->db_name . "` LIKE '" . $table . "'";
        if ($result = $this->query($sql)) {
            if (($row = $result->fetch())) {
                $retVal = $row[$engineValue];
            }
        }
        return $retVal;
    }   // end function is_error()

    /**
     * adds an index to a table
     *
     * @param string $table_name : full name of the table (incl. TABLE_PREFIX / {TP})
     * @param string $index_name : name of the new index
     * @param string $field_list : comma seperated list of fields for this index
     * @param string $index_type : kind of index (UNIQUE, PRIMARY, '')
     * @return bool: true if successful, otherwise false and error will be set
     */
    public function index_add(string $table_name, string $index_name, string $field_list, string $index_type = ''): bool
    {
        $retval = false;
        $field_list = str_replace(' ', '', $field_list);
        $field_list = explode(',', $field_list);
        $number_fields = sizeof($field_list);
        $field_list = '`' . implode('`,`', $field_list) . '`';
        if ($this->index_exists($table_name, $index_name, $number_fields) ||
            $this->index_exists($table_name, $index_name)) {
            $sql = 'ALTER TABLE `' . $table_name . '` ';
            $sql .= 'DROP INDEX `' . $index_name . '`';
            if ($this->query($sql)) {
                $sql = 'ALTER TABLE `' . $table_name . '` ';
                $sql .= 'ADD ' . $index_type . ' `' . $index_name . '` ( ' . $field_list . ' ); ';
                if ($this->query($sql)) {
                    $retval = true;
                }
            }
        }
        return $retval;
    }   // end function query()

    /**
     * checks if the given index (name) exists in the the table
     *
     * @access    public
     * @param string $table_name : full name of the table (incl. TABLE_PREFIX / {TP})
     * @param string $index_name : name of the index to seek for
     * @param int $number_fields
     * @return    bool: true if field exists
     **/
    public function index_exists(string $table_name, string $index_name, $number_fields = 0): bool
    {
        $number_fields = intval($number_fields);
        $keys = 0;
        $sql = 'SHOW INDEX FROM `' . $table_name . '`';
        if (($res_keys = $this->query($sql))) {
            while (($rec_key = $res_keys->fetch())) {
                if ($rec_key['Key_name'] == $index_name) {
                    $keys++;
                }
            }
        }
        if ($number_fields == 0) {
            return ($keys != $number_fields);
        } else {
            return ($keys == $number_fields);
        }
    }   // end function set_error()

    /**
     * remove an index from a table
     *
     * @param string $table_name : full name of the table (incl. TABLE_PREFIX / {TP})
     * @param string $field_name : name of the field to remove
     * @return bool: true if successful, otherwise false and error will be set
     */
    public function index_remove(string $table_name, string $index_name): bool
    {
        $retval = false;
        if ($this->index_exists($table_name, $index_name)) {
            // modify a existing field in a table
            $sql = 'ALTER TABLE `' . $table_name . '` DROP INDEX `' . $index_name . '`';
            $retval = $this->query($sql);
        }
        return $retval;
    }   // end function SqlImport()

    /**
     * insertRow() Insert row
     * ======================
     *
     * Example usage:
     * $aInsert = array(
     *    'name'     => 'Jane Doe',
     *    'zip_code' => '23450-7',
     * );
     * $database->updateRow('{TP}mod_mymod', $aInsert);
     *
     * @param string $table
     * @param array $data
     *
     * @return Result
     */
    public function insertRow(string $table, array $data)
    {
        $retVal = false;
        $parameters = array();
        foreach ($data as $column => $value) {
            $parameters[] = "`" . trim($column) . "` = '" . $value . "', ";
        }
        $sValues = implode("", $parameters);
        $sValues = substr($sValues, 0, -2);
        $strQuery = sprintf("INSERT INTO `%s` SET %s", $table, $sValues);
        if ($this->query($strQuery)) {
            $retVal = true;
        } else {
            $retVal = $this->get_error();
        }
        return $retVal;
    }   // end function updateRow()

    /**
     * Import a standard *.sql dump file
     * @param string $sSqlDump link to the sql-dumpfile
     * @param string $sTablePrefix
     * @param bool $bPreserve set to true will ignore all DROP TABLE statements
     * @param string $sTblEngine
     * @param string $sTblCollation
     * @return boolean true if import successful
     */
    public function SqlImport(
        $sSqlDump,
        $sTablePrefix = '',
        $bPreserve = true,
        $sTblEngine = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci',
        $sTblCollation = ' collate utf8_unicode_ci'
    )
    {
        $retval = true;
        $this->error = '';
        $aReplaceTokens = array(
            '{TP}' => $sTablePrefix,
            '{TABLE_PREFIX}' => $sTablePrefix,
            '{TABLE_ENGINE}' => $sTblEngine,
            '{TABLE_COLLATION}' => $sTblCollation
        );
        $sql = '';
        $aSql = file($sSqlDump);
        while (sizeof($aSql) > 0) {
            $sSqlLine = trim(array_shift($aSql));
            if (!preg_match('/^[-\/]+.*/', $sSqlLine)) {
                $sql = $sql . ' ' . $sSqlLine;
                if ((substr($sql, -1, 1) == ';')) {
                    $sql = trim(strtr($sql, $aReplaceTokens));
                    if (!($bPreserve && preg_match('/^\s*DROP TABLE IF EXISTS/siU', $sql))) {
                        if (!$this->query($sql)) {
                            $retval = false;
                            unset($aSql);
                            break;
                        }
                    }
                    $sql = '';
                }
            }
        }
        return $retval;
    }   // end function replaceTablePrefix()

    /**
     * updateRow() Update row
     * ======================
     *
     * Example usage:
     * $aUpdate = array(
     *    'entry_id' => 5,
     *    'name'     => 'Jane Doe',
     *    'zip_code' => '23450-7',
     * );
     * $database->updateRow('{TP}mod_mymod', 'entry_id', $aUpdate);
     *
     * @param string $table
     * @param unspec $refKey reference key (column)
     *                         since vers. 1.4.0 we can have more than one
     *                         column to check against in the WHERE clause.
     *                         Use CSV or array for this feature
     * @param array $data
     *
     * @return result
     */
    public function updateRow(string $table = '', $refKey = '', $data = array())
    {
        $retVal = false;
        if ($table != '' && isset($refKey) && !empty($data)) {
            // Prepare WHERE clause
            // check if $refKey is CSV-string and create an array if so
            if (is_string($refKey) && strpos($refKey, ',') !== false) {
                $refKey = explode(',', $refKey);
            }

            $sWhere = ''; // init WHERE command
            if (is_array($refKey)) {
                $aWhere = array();
                foreach ($refKey as $column) {
                    $column = trim($column);
                    $aWhere[] = "`" . trim($column) . "` = '" . $data[$column] . "'";
                }
                $sWhere = implode(' AND ', $aWhere);
            } else {
                $sWhere = "`" . trim($refKey) . "` = '" . $data[$refKey] . "'";
            }

            // prepare data
            $parameters = array();
            foreach ($data as $column => $value) {
                $parameters[] = "`" . trim($column) . "` = '" . $value . "', ";
            }
            $sValues = implode("", $parameters);
            $sValues = substr($sValues, 0, -2);
            // check whether UPDATE or INSERT will be used
            // (insert will be used in case record doesn't exist yet)
            if ($this->get_one(sprintf("SELECT COUNT(*) FROM `%s` WHERE %s", $table, $sWhere))) {
                $strQuery = sprintf("UPDATE `%s` SET %s WHERE %s", $table, $sValues, $sWhere);
            } else {
                $strQuery = sprintf("INSERT INTO `%s` SET %s", $table, $sValues);
            }
            $retVal = $this->query($strQuery) ? true : $this->get_error();
        }
        return $retVal;
    }
} /// end of class database

/**
 * decorates PDOStatement object with old WB methods numRows() and fetchRow()
 * for backward compatibility
 **/
class WBCE_PDOStatementDecorator
{
    private $pdo_stmt = null;

    public function __construct($stmt)
    {
        $this->pdo_stmt = $stmt;
    }

    // route all other method calls directly to PDOStatement
    public function __call($method, $args)
    {
        return call_user_func_array(array($this->pdo_stmt, $method), $args);
    }

    public function numRows()
    {
        return $this->pdo_stmt->rowCount();
    }

    public function fetchRow($type = \PDO::FETCH_BOTH)
    {
        // this is for backward compatibility
        if (!defined('MYSQLI_ASSOC') || $type === MYSQLI_ASSOC) {
            $type = \PDO::FETCH_ASSOC;
        }
        return $this->pdo_stmt->fetch($type);
    }
}

class DatabaseException extends Exception
{
}
