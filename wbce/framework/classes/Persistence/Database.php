<?php

namespace Persistence;

class Database
{

    /**
     * Error message.
     *
     * @var string
     */
    protected $error;

    /**
     * PDO driver.
     *
     * @var string
     */
    protected $driver = 'mysql';

    /**
     * Table prefix
     *
     * @var string
     */
    protected $tablePrefix = '';

    /**
     * Database host.
     *
     * @var string
     */
    protected $host = 'localhost';

    /**
     * Database name.
     *
     * @var string
     */
    protected $dbname = '';

    /**
     * Database username.
     *
     * @var string
     */
    protected $username = '';

    /**
     * Database password.
     *
     * @var string
     */
    protected $password = '';

    /**
     * Database charset.
     *
     * @var string
     */
    protected $charset = 'utf8';

    /**
     * Database DSN.
     *
     * @var string
     */
    protected $dsn;

    /**
     * PDO connection.
     *
     * @var \PDO
     */
    protected $pdo;

    /**
     * Constructor.
     */
    public function __construct()
    {
        if (defined('DB_DSN') && strlen(DB_DSN) > 0) {
            $this->dsn = DB_DSN;
        } else {
            if (defined('DB_DRIVER')) {
                $this->driver = DB_DRIVER;
            }

            if (defined('DB_HOST')) {
                $this->host = DB_HOST;

                if (defined('DB_PORT')) {
                    $this->host . ':' . DB_PORT;
                }
            }

            if (defined('DB_NAME')) {
                $this->dbname = DB_NAME;
            } else {
                throw new DatabaseException('Database name (DB_NAME) not defined');
            }

            if (defined('DB_USERNAME')) {
                $this->username = DB_USERNAME;
            }

            if (defined('DB_PASSWORD')) {
                $this->password = DB_PASSWORD;
            }

            if (defined('DB_CHARSET')) {
                $this->charset = preg_replace('/[^a-z0-9]/i', '', DB_CHARSET);
            }

            if (defined('TABLE_PREFIX')) {
                $this->tablePrefix = TABLE_PREFIX;
            }
        }

        $this->connect();
    }

    /**
     * Connect to database.
     *
     * @return bool
     */
    public function connect()
    {
        $options = array(
            \PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL,
        );

        if ($this->dsn) {
            $this->pdo = new \WDO($this->dsn);
        } else {
            $this->pdo = new \WDO($this->driver . ':host=' . $this->host . ';dbname=' . $this->dbname, $this->username, $this->password, $options, $this->tablePrefix);
            $this->pdo->exec('SET NAMES ' . $this->charset);
        }

        if ($this->driver === 'mysql') {
            $this->pdo->exec('SET @@sql_mode=""');
        }

        return true;
    }

    /**
     * Disconnect from database.
     *
     * @return bool
     */
    public function disconnect()
    {
        $this->pdo = null;

        return true;
    }

    /**
     * Execute query.
     *
     * @param string $query
     *
     * @return Result
     */
    public function query($query)
    {
        $statement = $this->pdo->query($query);
        $this->setError($statement);

        if ($statement) {
            return new Result($statement);
        }

        return;
    }

    /**
     * Execute prepared query.
     *
     * @param string $query
     * @param array  $parameters
     *
     * @return Result
     */
    public function preparedQuery($query, array $parameters = array())
    {
        $statement = $this->pdo->prepare($query);
        $statement->execute($parameters);
        $this->setError($statement);

        if ($statement) {
            return new Result($statement);
        }

        return;
    }

    /**
     * Get the first column of the first row.
     *
     * @param string $query
     *
     * @return mixed
     */
    public function getOne($query)
    {
        $statement = $this->pdo->query($query);
        $this->setError($statement);

        if ($statement) {
            return $statement->fetchColumn();
        }

        return;
    }

    /**
     * Has error.
     *
     * @return string
     */
    public function hasError()
    {
        return $this->getError();
    }

    /**
     * Get error message.
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set error.
     *
     * @param string $statement \PDOStatement
     */
    public function setError(\PDOStatement $statement = null)
    {
        $errorInfo = $this->pdo->errorInfo();
        if ($statement) {
            $errorInfo = $statement->errorInfo();
        }

        if (isset($errorInfo[2])) {
            $this->error = $errorInfo[2];
        }
        $this->error = null;
    }

    /**
     * Get PDO connection.
     *
     * @return \PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * Get database name.
     *
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->dbname ? : $this->dsn;
    }

    /**
     * Quote a string for use in a query.
     *
     * @param string $string
     *
     * @return string
     */
    public function quote($string)
    {
        return trim($this->pdo->quote($string), '\'');
    }

    /**
     * Get last inserted Id.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getLastInsertId($name = null)
    {
        return $this->pdo->lastInsertId($name);
    }

    /**
     * Get table engine.
     *
     * @param string $table
     *
     * @return string
     */
    public function getTableEngine($table)
    {
        if ($this->driver === 'mysql') {
            $result = $this->preparedQuery('SHOW TABLE STATUS LIKE :table', array(
                'table' => $table,
            ));

            if ($result && !$this->getError()) {
                $row = $result->fetchRow();

                return $row['Engine'];
            }
        }

        return $this->driver;
    }

    /**
     * Check wether column in table exists.
     *
     * @param string $table
     * @param string $column
     *
     * @return bool
     */
    public function hasColumn($table, $column)
    {
        $result = $this->preparedQuery('SHOW COLUMNS FROM ' . $table . ' LIKE :column', array(
            'column' => $column,
        ));

        if ($result && !$this->getError()) {
            return $result->numRows() > 0;
        }

        return false;
    }

    /**
     * Add column to table.
     *
     * @param string $table
     * @param string $column
     * @param string $description
     *
     * @return bool
     */
    public function addColumn($table, $column, $description)
    {
        if (!$this->hasColumn($table, $column)) {
            $result = $this->query('ALTER TABLE ' . $table . ' ADD ' . $column . ' ' . $description);

            if ($result && !$this->getError()) {
                return $this->hasColumn($table, $column);
            }
        } else {

            //throw new DatabaseException('Cannot add column ' . $column . ' in table ' . $table . ', because a column with that name already exists');
            $this->error = 'Cannot add column ' . $column . ' in table ' . $table . ', because a column with that name already exists';
        }

        return false;
    }

    /**
     * Modify column from table.
     *
     * @param string $table
     * @param string $column
     * @param string $description
     *
     * @return bool
     */
    public function modifyColumn($table, $column, $description)
    {
        if ($this->hasColumn($table, $column)) {
            $result = $this->query('ALTER TABLE ' . $table . ' MODIFY ' . $column . ' ' . $description);

            return $result && !$this->getError();
        } else {

            //throw new DatabaseException('Cannot modify column ' . $column . ' in table ' . $table . ', because a column with that name doesn\'t exists');
            $this->error = 'Cannot modify column ' . $column . ' in table ' . $table . ', because a column with that name doesn\'t exists';
        }

        return false;
    }

    /**
     * Drop column from table.
     *
     * @param string $table
     * @param string $column
     *
     * @return bool
     */
    public function dropColumn($table, $column)
    {
        if ($this->hasColumn($table, $column)) {
            $result = $this->query('ALTER TABLE ' . $table . ' DROP ' . $column);

            return $result && !$this->getError();
        } else {

            //throw new DatabaseException('Cannot drop column ' . $column . ' in table ' . $table . ', because a column with that name doesn\'t exists');
            $this->error = 'Cannot drop column ' . $column . ' in table ' . $table . ', because a column with that name doesn\'t exists';
        }

        return false;
    }

    /**
     * Import SQL dump file.
     *
     * @param string $dumpFile
     * @param string $tablePrefix
     * @param bool   $preserve
     * @param string $tableEngine
     * @param string $tableCollation
     *
     * @return mixed
     */
    public function import($dumpFile, $tablePrefix = '', $preserve = true, $tableEngine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci', $tableCollation = ' collate utf8_unicode_ci')
    {
        if (is_file($dumpFile)) {
            $sql = file_get_contents($dumpFile);

            if (strlen($sql) > 0) {
                $sql = str_replace(array('{TABLE_PREFIX}', '{TABLE_ENGINE}', '{TABLE_COLLATION}'), array($tablePrefix, $tableEngine, $tableCollation), $sql);

                if ($preserve) {
                    $sql = preg_replace('/(.*DROP\sTABLE\sIF\sEXISTS.*)/', '', $sql);
                }

                $result = $this->query($sql);

                return $result && !$this->getError();
            } else {

                //throw new DatabaseException('Dump file ' . $dumpFile . ' is empty');
                $this->error = 'Dump file ' . $dumpFile . ' is empty';
            }
        } else {
            //throw new DatabaseException('Cannot read dump file ' . $dumpFile);
            $this->error = 'Cannot read dump file ' . $dumpFile;
        }

        return false;
    }

    /**
     * Update row.
     *
     * @param string $table
     * @param string $primaryKey
     * @param array  $data
     *
     * @return Result
     *
     * @throws DatabaseException
     */
    public function updateRow($table, $primaryKey, array $data)
    {
        if (isset($data[$primaryKey])) {
            $parameters = array();

            $sets = array_map(function ($column, $value) use ($parameters, $primaryKey) {
                if ($column !== $primaryKey) {
                    $parameters[] = $value;

                    return $column . ' = ?';
                }
            }, $data);

            $parameters[] = $data[$primaryKey];

            $sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $sets) . ' WHERE ' . $primaryKey . ' = ?';

            $result = $this->preparedQuery($sql, $parameters);

            if ($result && !$this->getError()) {
                return $result;
            }
        } else {
            //throw new DatabaseException('Cannot find primary key ' . $primaryKey . ' in data');
            $this->error = 'Cannot find primary key ' . $primaryKey . ' in data';
        }

        return false;
    }

    /**
     * Insert row.
     *
     * @param string $table
     * @param array  $data
     *
     * @return Result
     */
    public function insertRow($table, array $data)
    {
        $parameters = array();

        $columns = array_keys($data);
        $values = array_map(function ($value) use ($parameters) {
            $parameters[] = $value;

            return '?';
        }, $data);

        $sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $values) . ')';

        return $this->preparedQuery($sql, $parameters);
    }

    /**
     * Magic getter.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        switch ($name) {
            case 'db_handle':
            case 'DbHandle':
                trigger_error('Property ' . $name . ' is deprecated, use the method getPdo() instead', E_USER_DEPRECATED);

                return $this->getPdo();
            case 'db_name':
            case 'DbName':
                trigger_error('Property ' . $name . ' is deprecated, use the method getDatabaseName() instead', E_USER_DEPRECATED);

                return $this->getDatabaseName();
        }
    }

    /**
     * Magic call.
     *
     * @param string $name
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($name, array $args)
    {
        switch ($name) {
            case 'get_one':
                trigger_error('Method ' . $name . '(\$query) is deprecated, use the method getOne(\$query) instead', E_USER_DEPRECATED);

                return call_user_func_array(array($this, 'getOne'), $args);
            case 'is_error':
                trigger_error('Method ' . $name . '() is deprecated, use the method hasError() instead', E_USER_DEPRECATED);

                return call_user_func_array(array($this, 'hasError'), $args);
            case 'get_error':
                trigger_error('Method ' . $name . '() is deprecated, use the method getError() instead', E_USER_DEPRECATED);

                return call_user_func_array(array($this, 'getError'), $args);
            case 'escapeString':
                trigger_error('Method ' . $name . '(\$string) is deprecated, use the method quote(\$string) instead', E_USER_DEPRECATED);

                return call_user_func_array(array($this, 'quote'), $args);
            case 'field_exists':
                trigger_error('Method ' . $name . '(\$table_name, \$field_name) is deprecated, use the method hasColumn(\$table, \$column) instead', E_USER_DEPRECATED);

                return call_user_func_array(array($this, 'hasColumn'), $args);
            case 'field_add':
                trigger_error('Method ' . $name . '(\$table_name, \$field_name, \$description) is deprecated, use the method addColumn(\$table, \$column, \$description) instead', E_USER_DEPRECATED);

                return call_user_func_array(array($this, 'addColumn'), $args);
            case 'field_modify':
                trigger_error('Method ' . $name . '(\$table_name, \$field_name, \$description) is deprecated, use the method modifyColumn(\$table, \$column, \$description) instead', E_USER_DEPRECATED);

                return call_user_func_array(array($this, 'modifyColumn'), $args);
            case 'field_remove':
                trigger_error('Method ' . $name . '(\$table_name, \$field_name) is deprecated, use the method dropColumn(\$table, \$column) instead', E_USER_DEPRECATED);

                return call_user_func_array(array($this, 'dropColumn'), $args);
            case 'SqlImport':
                trigger_error('Method ' . $name . '(\$sSqlDump, \$sTablePrefix, \$bPreserve, \$sTblEngine, \$sTblCollation) is deprecated, use the method import((\$dumpFile, \$tablePrefix, \$preserve, \$tableEngine, \$tableCollation)) instead', E_USER_DEPRECATED);

                return call_user_func_array(array($this, 'import'), $args);
        }
    }
}
