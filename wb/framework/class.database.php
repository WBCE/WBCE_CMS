<?php
/**
 *
 * @category        framework
 * @package         database
 * @copyright       WebsiteBaker Org. e.V.
 * @link            http://websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.3
 * @requirements    PHP 5.3.6 and higher
 * @version         $Id: class.database.php 1613 2012-02-16 12:12:17Z darkviper $
 * @filesource      $HeadURL: svn://isteam.dynxs.de/wb_svn/wb280/branches/2.8.x/wb/framework/class.database.php $
 * @lastmodified    $Date: 2012-02-16 13:12:17 +0100 (Do, 16. Feb 2012) $
 *
 */
/*
Database class
This class will be used to interface between the database
and the Website Baker code
*/


    define('DATABASE_CLASS_LOADED', true);
    // define the old mysql consts for Backward compatibility
    if (!defined('MYSQL_ASSOC')) {
        define('MYSQL_ASSOC',                 1);
        define('MYSQL_NUM',                   2);
        define('MYSQL_BOTH',                  3);
        define('MYSQL_CLIENT_COMPRESS',      32);
        define('MYSQL_CLIENT_IGNORE_SPACE', 256);
        define('MYSQL_CLIENT_INTERACTIVE', 1024);
        define('MYSQL_CLIENT_SSL',         2048);
    }

class database {

    private $db_handle  = null; // readonly from outside
    private $db_name    = '';
    private $connected  = false;
    private $sCharset   = '';
    private $error      = '';
    private $error_type = '';
    private $message    = array();


    // Set DB_URL
    function __construct($url = '') {
        // Connect to database
        if (!$this->connect()) {
            throw new DatabaseException($this->get_error());
        }
    }
    
    // Connect to the database   DB_CHARSET
    function connect() {

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
                @mysqli_query($this->db_handle, 'SET NAMES '.$this->sCharset);
                mysqli_set_charset($this->db_handle, $this->sCharset);
            }
            $this->db_name = DB_NAME;
            $this->connected = true;
        }
        return $this->connected;
    }
    
    // Disconnect from the database
    function disconnect() {
        if($this->connected==true) {
            mysqli_close();
            return true;
        } else {
            return false;
        }
    }
    
    // Run a query
    function query($statement) {
        $mysql = new mysql($this->db_handle);
        $mysql->query($statement);
        $this->set_error($mysql->error());
        if($mysql->error()) {
            return null;
        } else {
            return $mysql;
        }
    }

    // Gets the first column of the first row
    function get_one( $statement )
    {
        $fetch_row = mysqli_fetch_array(mysqli_query($this->db_handle, $statement) );
        $result = $fetch_row[0];
        $this->set_error(null);
        if(mysqli_error($this->db_handle)) {
            $this->set_error(mysqli_error($this->db_handle));
            return null;
        } else {
            return $result;
        }
    }
    
    // Set the DB error
    function set_error($message = null) {
        $this->error = $message;
        $this->error_type = 'unknown';
    }
    
    // Return true if there was an error
    function is_error() {
        return (!empty($this->error)) ? true : false;
    }
    
    // Return the error
    function get_error() {
        return $this->error;
    }

/**
 * default Getter for some properties
 * @param string $sPropertyName
 * @return mixed NULL on error or missing property
 */
    public function __get($sPropertyName)
    {
        switch ($sPropertyName):
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
        endswitch;
        return $retval;
    } // __get()
/**
 * Escapes special characters in a string for use in an SQL statement
 * @param string $unescaped_string
 * @return string
 */
    public function escapeString($unescaped_string)
    {
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

/*
 * @param string $table_name: full name of the table (incl. TABLE_PREFIX)
 * @param string $field_name: name of the field to seek for
 * @return bool: true if field exists
 */
    public function field_exists($table_name, $field_name)
    {
        $sql = 'DESCRIBE `'.$table_name.'` `'.$field_name.'` ';
        $query = $this->query($sql);
        return ($query->numRows() != 0);
    }

/*
 * @param string $table_name: full name of the table (incl. TABLE_PREFIX)
 * @param string $index_name: name of the index to seek for
 * @return bool: true if field exists
 */
    public function index_exists($table_name, $index_name, $number_fields = 0)
    {
        $number_fields = intval($number_fields);
        $keys = 0;
        $sql = 'SHOW INDEX FROM `'.$table_name.'`';
        if( ($res_keys = $this->query($sql)) )
        {
            while(($rec_key = $res_keys->fetchRow()))
            {
                if( $rec_key['Key_name'] == $index_name )
                {
                    $keys++;
                }
            }

        }
        if( $number_fields == 0 )
        {
            return ($keys != $number_fields);
        }else
        {
            return ($keys == $number_fields);
        }
    }
/*
 * @param string $table_name: full name of the table (incl. TABLE_PREFIX)
 * @param string $field_name: name of the field to add
 * @param string $description: describes the new field like ( INT NOT NULL DEFAULT '0')
 * @return bool: true if successful, otherwise false and error will be set
 */
    public function field_add($table_name, $field_name, $description)
    {
        if( !$this->field_exists($table_name, $field_name) )
        { // add new field into a table
            $sql = 'ALTER TABLE `'.$table_name.'` ADD '.$field_name.' '.$description.' ';
            $query = $this->query($sql);
            $this->set_error(mysqli_error($this->db_handle));
            if( !$this->is_error() )
            {
                return ( $this->field_exists($table_name, $field_name) ) ? true : false;
            }
        }else
        {
            $this->set_error('field \''.$field_name.'\' already exists');
        }
        return false;
    }

/*
 * @param string $table_name: full name of the table (incl. TABLE_PREFIX)
 * @param string $field_name: name of the field to add
 * @param string $description: describes the new field like ( INT NOT NULL DEFAULT '0')
 * @return bool: true if successful, otherwise false and error will be set
 */
    public function field_modify($table_name, $field_name, $description)
    {
        $retval = false;
        if( $this->field_exists($table_name, $field_name) )
        { // modify a existing field in a table
            $sql  = 'ALTER TABLE `'.$table_name.'` MODIFY `'.$field_name.'` '.$description;
            $retval = ( $this->query($sql) ? true : false);
            $this->set_error(mysqli_error($this->db_handle));
        }
        return $retval;
    }

/*
 * @param string $table_name: full name of the table (incl. TABLE_PREFIX)
 * @param string $field_name: name of the field to remove
 * @return bool: true if successful, otherwise false and error will be set
 */
    public function field_remove($table_name, $field_name)
    {
        $retval = false;
        if( $this->field_exists($table_name, $field_name) )
        { // modify a existing field in a table
            $sql  = 'ALTER TABLE `'.$table_name.'` DROP `'.$field_name.'`';
            $retval = ( $this->query($sql) ? true : false );
        }
        return $retval;
    }

/*
 * @param string $table_name: full name of the table (incl. TABLE_PREFIX)
 * @param string $index_name: name of the new index
 * @param string $field_list: comma seperated list of fields for this index
 * @param string $index_type: kind of index (UNIQUE, PRIMARY, '')
 * @return bool: true if successful, otherwise false and error will be set
 */
    public function index_add($table_name, $index_name, $field_list, $index_type = '')
    {
        $retval = false;
        $field_list = str_replace(' ', '', $field_list);
        $field_list = explode(',', $field_list);
        $number_fields = sizeof($field_list);
        $field_list = '`'.implode('`,`', $field_list).'`';
        if( $this->index_exists($table_name, $index_name, $number_fields) ||
            $this->index_exists($table_name, $index_name))
        {
            $sql  = 'ALTER TABLE `'.$table_name.'` ';
            $sql .= 'DROP INDEX `'.$index_name.'`';
            if( $this->query($sql))
            {
                $sql  = 'ALTER TABLE `'.$table_name.'` ';
                $sql .= 'ADD '.$index_type.' `'.$index_name.'` ( '.$field_list.' ); ';
                if( $this->query($sql)) { $retval = true; }
            }
        }
        return $retval;
    }

/*
 * @param string $table_name: full name of the table (incl. TABLE_PREFIX)
 * @param string $field_name: name of the field to remove
 * @return bool: true if successful, otherwise false and error will be set
 */
    public function index_remove($table_name, $index_name)
    {
        $retval = false;
        if( $this->index_exists($table_name, $index_name) )
        { // modify a existing field in a table
            $sql  = 'ALTER TABLE `'.$table_name.'` DROP INDEX `'.$index_name.'`';
            $retval = ( $this->query($sql) ? true : false );
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
    public function SqlImport($sSqlDump,
                              $sTablePrefix = '',
                              $bPreserve = true,
                              $sTblEngine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci',
                              $sTblCollation = ' collate utf8_unicode_ci')
    {
        $retval = true;
        $this->error = '';
        $aSearch  = array('{TABLE_PREFIX}','{TABLE_ENGINE}', '{TABLE_COLLATION}');
        $aReplace = array($sTablePrefix, $sTblEngine, $sTblCollation);
        $sql = '';
        $aSql = file($sSqlDump);
        while ( sizeof($aSql) > 0 ) {
            $sSqlLine = trim(array_shift($aSql));
            if (!preg_match('/^[-\/]+.*/', $sSqlLine)) {
                $sql = $sql.' '.$sSqlLine;
                if ((substr($sql,-1,1) == ';')) {
                    $sql = trim(str_replace( $aSearch, $aReplace, $sql));
                    if (!($bPreserve && preg_match('/^\s*DROP TABLE IF EXISTS/siU', $sql))) {
                        if(! $this->query($sql)) {
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
        $sql = "SHOW TABLE STATUS FROM " . $this->db_name . " LIKE '" . $table . "'";
        if(($result = $this->query($sql))) {
            if(($row = $result->fetchRow(MYSQLI_ASSOC))) {
                $retVal = $row[$engineValue];
            }
        }
        return $retVal;
    }


} /// end of class database

define('MYSQL_SEEK_FIRST', 0);
define('MYSQL_SEEK_LAST', -1);
define('MYSQLI_SEEK_FIRST', 0);
define('MYSQLI_SEEK_LAST', -1);

class mysql {

    private $db_handle = null;
    private $result = null;
    private $error = '';

    public function __construct($handle) {
        $this->db_handle = $handle;
    }

    // Run a query
    public function query($statement) {
        $this->result = mysqli_query($this->db_handle, $statement);
        $this->error = mysqli_error($this->db_handle);
        return $this->result;
    }
    
    // Fetch num rows
    public function numRows() {
        return mysqli_num_rows($this->result);
    }

    // Fetch row  $typ = MYSQLI_ASSOC, MYSQLI_NUM, MYSQLI_BOTH
    public function fetchRow($typ = MYSQLI_BOTH) {
        return mysqli_fetch_array($this->result, $typ);
    }

    public function rewind()
    {
        return $this->seekRow();
    }

    public function seekRow( $position = MYSQLI_SEEK_FIRST )
    {
        $pmax = $this->numRows() - 1;
        $offset = (($position < 0 || $position > $pmax) ? $pmax : $position);
        return mysqli_data_seek($this->result, $offset);
    }

    // Get error
    public function error() {
        if(isset($this->error)) {
            return $this->error;
        } else {
            return null;
        }
    }

} // end of class mysql

class DatabaseException extends Exception {}

/* this function is placed inside this file temporarely until a better place is found */
/*  function to update a var/value-pair(s) in table ****************************
 *  nonexisting keys are inserted
 *  @param string $table: name of table to use (without prefix)
 *  @param mixed $key:    a array of key->value pairs to update
 *                        or a string with name of the key to update
 *  @param string $value: a sting with needed value, if $key is a string too
 *  @return bool:  true if any keys are updated, otherwise false
 */
    function db_update_key_value($table, $key, $value = '')
    {
        global $database;
        if( !is_array($key))
        {
            if( trim($key) != '' )
            {
                $key = array( trim($key) => trim($value) );
            } else {
                $key = array();
            }
        }
        $retval = true;
        foreach( $key as $index=>$val)
        {
            $index = strtolower($index);
            $sql = 'SELECT COUNT(`setting_id`) FROM `'.TABLE_PREFIX.$table.'` WHERE `name` = \''.$index.'\' ';
            if($database->get_one($sql))
            {
                $sql = 'UPDATE ';
                $sql_where = 'WHERE `name` = \''.$index.'\'';
            }else {
                $sql = 'INSERT INTO ';
                $sql_where = '';
            }
            $sql .= '`'.TABLE_PREFIX.$table.'` ';
            $sql .= 'SET `name` = \''.$index.'\', ';
            $sql .= '`value` = \''.$val.'\' '.$sql_where;
            if( !$database->query($sql) )
            {
                $retval = false;
            }
        }
        return $retval;
    }
