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

// define the old mysql constants for backward compatibility
if (!defined('MYSQL_CLIENT_COMPRESS')) {
    define('MYSQL_CLIENT_COMPRESS', 32);
    define('MYSQL_CLIENT_IGNORE_SPACE', 256);
    define('MYSQL_CLIENT_INTERACTIVE', 1024);
    define('MYSQL_CLIENT_SSL', 2048);
}

class database
{
    /**
     * @var $db_handle - DB connection handle (returned from mysqli_connect())
     **/
    private $db_handle = null; // readonly from outside
    /**
     * @var $db_name - DB name (DB_NAME in config.php)
     **/
    private $db_name = '';
    /**
     * @var $connected - wether a connection is established
     **/
    private $connected = false;
    /**
     * @var sCharset - DB_CHARSET in config.php; defaults to empty value
     **/
    private $sCharset = '';
    /**
     * @var $error - last DB error message (set by function set_error())
     **/
    private $error = '';
    /**
     * @var $error_type - set by function set_error() which always sets 'unknown'
     **/
    private $error_type = '';
    /**
     * @var $message - never used (?)
     **/
    private $message = array();
    /**
     * @var $_prefixes - array of known prefixes (at least DB_PREFIX from config)
     **/
    private $_prefixes = array();

    /**
     * constructor
     * @return object
     **/
    public function __construct()
    {
        // Connect to database
        if (!$this->connect()) {
            throw new DatabaseException($this->get_error());
        }
        $this->_prefixes = $this->_tablePrefixes();
    }

    /**
     * establish DB connection
     * + sets $this->db_handle to mysqli_connect() result
     * + sets $this->connected
     * + sets $this->error if connection error occurs
     * + sets DB charset using 'SET NAMES' if DB_CHARSET is present in config
     * + disables strict mode if USE_MYSQL_STRICT is not present in config
     * @return bool (connection established or not)
     **/
    public function connect()
    {
        $this->sCharset = strtolower(preg_replace('/[^a-z0-9]/i', '', (defined('DB_CHARSET') ? DB_CHARSET : '')));

        if (defined('DB_PORT')) {
            $port = DB_PORT;
        } else {
            $port = ini_get('mysqli.default_port');
        }
        if (!($this->db_handle = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, $port))) {
            $this->connected = false;
            $this->error = mysqli_connect_error();
        } else {
            if ($this->sCharset) {
                @mysqli_query($this->db_handle, 'SET NAMES ' . $this->sCharset);
                mysqli_set_charset($this->db_handle, $this->sCharset);
            }
            $this->db_name = DB_NAME;
            $this->connected = true;
            //added cause of problems whith mysql strict mode
            if (!defined('USE_MYSQL_STRICT') || USE_MYSQL_STRICT === false) {
                mysqli_query($this->db_handle, "SET @@sql_mode=''");
            }
        }
        return $this->connected;
    }

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
    public function delRow($table, $refKey = '', $uRows = null)
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
                if (isset($strQuery) && $this->query($strQuery)) {
                    $retVal = true;
                } else {
                    $retVal = $this->get_error();
                }
            }
        }
        return $retVal;
    }

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
    public function updateRow($table = '', $refKey = '', $data = array())
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
    public function insertRow($table, array $data)
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
    }

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
     * _tablePrefixes()
     * =========================
     * default Prefix tokens
     *
     * @return array
     */
    private function _tablePrefixes()
    {
        return array(
            '{TABLE_PREFIX}' => TABLE_PREFIX,
            '{TP}' => TABLE_PREFIX, // shorthand
        );
        // More Prefixes can be added to this array using the public addPrefix() method
    }

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
    public function addPrefix($sPrefixPH = "", $sValue = "")
    {
        if ($sPrefixPH != "" && $sValue != "") {
            $aNewSet = array(
                $sPrefixPH => $sValue
            );
            $this->_prefixes += $aNewSet;
        } else {
            return false;
        }
    }

    /**
     * Disconnect from the database
     * @return bool
     **/
    public function disconnect()
    {
        if ($this->connected == true) {
            mysqli_close();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Run a query
     * @param string $statement
     * @return new mysql instance
     **/
    public function query($statement)
    {
        $mysql = new mysql($this->db_handle);
        $statement_final = $this->replaceTablePrefix($statement);
        $mysql->query($statement_final);
        if ($mysql->error()) {
            trigger_error(sprintf('STATEMENT: %s',preg_replace('/\s+/', ' ',$statement)));
            $this->set_error($mysql->error());
        }
        return $mysql;
    }

    /**
     * Gets the first column of the first row
     * @param string $statement
     * @return mixed (query result or null)
     **/
    public function get_one($statement)
    {
        $statement = $this->replaceTablePrefix($statement);
        try {
            $q = mysqli_query($this->db_handle, $statement);
            if ($q === false) {
                $this->set_error(mysqli_error($this->db_handle));
                return null;
            }
            $fetch_row = mysqli_fetch_array($q);
            if (is_array($fetch_row)) {
                $result = $fetch_row[0];
            } else {
                $result = '';
            }
            $this->set_error(null);
            if (mysqli_error($this->db_handle)) {
                $this->set_error(mysqli_error($this->db_handle));
                return null;
            } else {
                return $result;
            }
        } catch (\Exception $e) {
            trigger_error(sprintf('EXCEPTION: %s',mysqli_error($this->db_handle)));
            trigger_error(sprintf('STATEMENT: %s',preg_replace('/\s+/', ' ',$statement)));
            $this->set_error(sprintf('EXCEPTION: %s',mysqli_error($this->db_handle)));
            return null;
        }
    }

    /**
     * Return queried array directly for immediate use
     * @param string $statement
     * @return array
     **/
    public function get_array($statement)
    {
        $aData = array();
        try {
            if ($resData = $this->query($statement)) {
                while ($rec = $resData->fetchRow(MYSQLI_ASSOC)) {
                    $aData[] = $rec;
                }
            }
        } catch (\Exception $e) {
            trigger_error(sprintf('EXCEPTION: %s',mysqli_error($this->db_handle)));
            trigger_error(sprintf('STATEMENT: %s',preg_replace('/\s+/', ' ',$statement)));
            $this->set_error(sprintf('EXCEPTION: %s',mysqli_error($this->db_handle)));
        }
        return $aData;
    }

    /**
     * save the DB error
     * @param string $message
     * @return void
     **/
    public function set_error($message = null)
    {
        $this->error = $message;
        $this->error_type = 'unknown';
    }

    /**
     * Return true if there was an error
     * @return bool
     **/
    public function is_error()
    {
        return (!empty($this->error)) ? true : false;
    }

    /**
     * Return the (last) error
     * @return string
     **/
    public function get_error()
    {
        return $this->error;
    }

    /**
     * default Getter for some properties
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
    } // __get()

    /**
     * Escapes special characters in a string for use in an SQL statement
     * @param string $unescaped_string
     * @return string
     */
    public function escapeString($unescaped_string)
    {
        // return empty values as '' but preserve strings that are '0'
        if($unescaped_string == '0'){
            return '0';
        } elseif(empty($unescaped_string)) {
            return '';
        }
        return mysqli_real_escape_string($this->db_handle, $unescaped_string);
    }

    /**
     * Last inserted Id
     * @return bool|int false on error, 0 if no record inserted
     */
    public function getLastInsertId()
    {
        return mysqli_insert_id($this->db_handle);
    }

    /**
    * @param string $table_name: full name of the table (incl. TABLE_PREFIX / {TP})
    * @param string $field_name: name of the field to seek for
    * @return bool: true if field exists
    */
    public function field_exists($table_name, $field_name)
    {
        $sql = "SHOW COLUMNS FROM `$table_name` LIKE '$field_name'";
        $result = $this->query($sql);
        if (!$result) {
            return false;
        }
        $exists = ($result->numRows()) ? true : false;
        return $exists;
    }

    /**
    * @param string $table_name: full name of the table (incl. TABLE_PREFIX / {TP})
    * @param string $index_name: name of the index to seek for
    * @return bool: true if field exists
    */
    public function index_exists($table_name, $index_name, $number_fields = 0)
    {
        $number_fields = intval($number_fields);
        $keys = 0;
        $sql = 'SHOW INDEX FROM `' . $table_name . '`';
        if (($res_keys = $this->query($sql))) {
            while (($rec_key = $res_keys->fetchRow())) {
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
    }

    /**
    * @param string $table_name: full name of the table (incl. TABLE_PREFIX / {TP})
    * @param string $field_name: name of the field to add
    * @param string $description: describes the new field like ( INT NOT NULL DEFAULT '0')
    * @param string set_error: decide whether an existing field should throw an error message or not
    * @return bool: true if successful, otherwise false and error will be set
    */
    public function field_add($table_name, $field_name, $description, $set_error = true)
    {
        if (!$this->field_exists($table_name, $field_name)) {
            // add new field into a table
            $sql = 'ALTER TABLE `' . $table_name . '` ADD ' . $field_name . ' ' . $description . ' ';
            $query = $this->query($sql);
            $this->set_error(mysqli_error($this->db_handle));
            if (!$this->is_error()) {
                return ($this->field_exists($table_name, $field_name)) ? true : false;
            }
        } else {
            if ($set_error === true) {
                $this->set_error('field \'' . $field_name . '\' already exists');
            }
        }
        return false;
    }

    /*
    * @param string $table_name: full name of the table (incl. TABLE_PREFIX / {TP})
    * @param string $field_name: name of the field to add
    * @param string $description: describes the new field like ( INT NOT NULL DEFAULT '0')
    * @return bool: true if successful, otherwise false and error will be set
    */
    public function field_modify($table_name, $field_name, $description)
    {
        $retval = false;
        if ($this->field_exists($table_name, $field_name)) {
            // modify a existing field in a table
            $sql = 'ALTER TABLE `' . $table_name . '` MODIFY `' . $field_name . '` ' . $description;
            $retval = ($this->query($sql) ? true : false);
            $this->set_error(mysqli_error($this->db_handle));
        }
        return $retval;
    }

    /*
    * @param string $table_name: full name of the table (incl. TABLE_PREFIX / {TP})
    * @param string $field_name: name of the field to remove
    * @return bool: true if successful, otherwise false and error will be set
    */
    public function field_remove($table_name, $field_name)
    {
        $retval = false;
        if ($this->field_exists($table_name, $field_name)) {
            // modify a existing field in a table
            $sql = 'ALTER TABLE `' . $table_name . '` DROP `' . $field_name . '`';
            $retval = ($this->query($sql) ? true : false);
        }
        return $retval;
    }

    

    /**
     * add index to table; will overwrite index with same name
     * @param string $table_name
     * @param string $index_name
     * @param string $field_list
     * @param string $index_type
     * @return bool
     **/
	public function index_add($table_name, $index_name, $field_list, $index_type = 'KEY')
    {
       $retval = false;
       $field_list = explode(',', (str_replace(' ', '', $field_list)));
       $number_fields = sizeof($field_list);
       $field_list = '`'.implode('`,`', $field_list).'`';
       $index_name = (($index_type == 'PRIMARY') ? $index_type : $index_name);
       if ( $this->index_exists($table_name, $index_name, $number_fields) ||
            $this->index_exists($table_name, $index_name))
       {
           $sql  = 'ALTER TABLE `'.$table_name.'` ';
           $sql .= 'DROP INDEX `'.$index_name.'`';
           if (!$this->query($sql)) { return false; }
       }
       $sql  = 'ALTER TABLE `'.$table_name.'` ';
       $sql .= 'ADD '.$index_type.' ';
       $sql .= (($index_type == 'PRIMARY') ? 'KEY ' : '`'.$index_name.'` ');
       $sql .= '( '.$field_list.' ); ';
       if ($this->query($sql)) { $retval = true; }
       return $retval;
    }

    /*
    * @param string $table_name: full name of the table (incl. TABLE_PREFIX / {TP})
    * @param string $field_name: name of the field to remove
    * @return bool: true if successful, otherwise false and error will be set
    */
    public function index_remove($table_name, $index_name)
    {
        $retval = false;
        if ($this->index_exists($table_name, $index_name)) {
            // modify a existing field in a table
            $sql = 'ALTER TABLE `' . $table_name . '` DROP INDEX `' . $index_name . '`';
            $retval = ($this->query($sql) ? true : false);
        }
        return $retval;
    }

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
    }

    /**
     * retuns the type of the engine used for requested table
     * @param string $table name of the table, including prefix
     * @return boolean/string false on error, or name of the engine (myIsam/InnoDb)
     */
    public function getTableEngine($table)
    {
        $retVal = false;
        $mysqlVersion = mysqli_get_server_info($this->db_handle);
        $engineValue = (version_compare($mysqlVersion, '5.0') < 0) ? 'Type' : 'Engine';
        $sql = "SHOW TABLE STATUS FROM `" . $this->db_name . "` LIKE '" . $table . "'";
        if (($result = $this->query($sql))) {
            if (($row = $result->fetchRow(MYSQLI_ASSOC))) {
                $retVal = $row[$engineValue];
            }
        }
        return $retVal;
    }
} /// end of class database

defined('MYSQL_SEEK_FIRST') or define('MYSQL_SEEK_FIRST', 0);
defined('MYSQL_SEEK_LAST') or define('MYSQL_SEEK_LAST', -1);
defined('MYSQLI_SEEK_FIRST') or define('MYSQLI_SEEK_FIRST', 0);
defined('MYSQLI_SEEK_LAST') or define('MYSQLI_SEEK_LAST', -1);

class mysql
{
    private $db_handle = null;
    private $result = null;
    private $error = '';
    private $stmt = null;

    public function __construct($handle)
    {
        $this->db_handle = $handle;
    }

    public function getLastStatement()
    {
        return $this->stmt;
    }

    // Run a query
    public function query($statement)
    {
        $stmt = $statement;
        try {
            $this->result = mysqli_query($this->db_handle, $statement);
            $this->error = mysqli_error($this->db_handle);
        } catch ( Exception $e ) {
            $this->error = $e->getMessage();
            error_log($e->getMessage(),0);
            error_log($statement,0);
        }
        return $this->result;
    }

    // Fetch num rows
    public function numRows()
    {
        if(is_object($this->result) && $this->result instanceof mysqli_result) {
            return mysqli_num_rows($this->result);
        } else {
            return 0;
        }
    }

    // Fetch row  $typ = MYSQLI_ASSOC, MYSQLI_NUM, MYSQLI_BOTH
    public function fetchRow($typ = MYSQLI_BOTH)
    {
        return mysqli_fetch_array($this->result, $typ);
    }

    public function rewind()
    {
        return $this->seekRow();
    }

    public function seekRow($position = MYSQLI_SEEK_FIRST)
    {
        $pmax = $this->numRows() - 1;
        $offset = (($position < 0 || $position > $pmax) ? $pmax : $position);
        return mysqli_data_seek($this->result, $offset);
    }

    // Get error
    public function error()
    {
        if (isset($this->error)) {
            return $this->error;
        } else {
            return null;
        }
    }
} // end of class mysql

class DatabaseException extends Exception
{
}
