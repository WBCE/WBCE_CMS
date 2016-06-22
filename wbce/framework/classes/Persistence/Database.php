<?php

namespace Persistence;

class Database
{

    /**
     * PDO driver.
     *
     * @var string
     */
    private $driver = 'mysql';

    /**
     * Database host.
     *
     * @var string
     */
    private $host = 'localhost';

    /**
     * Database name.
     *
     * @var string
     */
    private $dbname = '';

    /**
     * Database username.
     *
     * @var string
     */
    private $username = '';

    /**
     * Database password.
     *
     * @var string
     */
    private $password = '';

    /**
     * Database charset.
     *
     * @var string
     */
    private $charset = 'utf8';

    /**
     * Database DSN.
     *
     * @var string
     */
    private $dsn;

    /**
     * PDO connection.
     *
     * @var \PDO
     */
    private $pdo;

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
            $this->pdo = new \PDO($this->dsn);
        } else {
            $this->pdo = new \PDO($this->driver . ':host=' . $this->host . ';dbname=' . $this->dbname, $this->username, $this->password, $options);
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
     * @param array  $parameters
     *
     * @return Result
     */
    public function query($query)
    {
        $statement = $this->pdo->query($query);
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
    public function preparedQuery($query, $parameters = array())
    {
        $statement = $this->pdo->prepare($query);
        $statement->execute($parameters);

        if ($statement) {
            return new Result($statement);
        }

        return;
    }

    /**
     * Get the first column of the first row.
     *
     * @param string $query
     * @param array  $parameters
     *
     * @return mixed
     */
    public function getOne($query, $parameters = array())
    {
        $statement = $this->pdo->prepare($query);
        $statement->execute($parameters);

        if ($statement) {
            $result = $statement->fetchColumn();

            return $result;
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
        return $this->getError() !== '';
    }

    /**
     * Get error message.
     *
     * @return string
     */
    public function getError()
    {
        $errorInfo = $this->statement->errorInfo();

        return $errorInfo[2];
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
        return $this->pdo->quote($string);
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
        $result = $this->preparedQuery('SHOW TABLE STATUS LIKE :table', array(
            'table' => $table,
        ));

        if ($result) {
            $row = $result->fetchRow();

            return $row['Engine'];
        }

        return 'Unknown';
    }

    /**
     * Check wether column in table exists.
     *
     * @param string $table
     * @param string $column
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function hasColumn($table, $column)
    {
        $result = $this->preparedQuery('SHOW COLUMNS FROM ' . $table . ' LIKE :column', array(
            'column' => $column,
        ));
        if ($result->error()) {
            throw new DatabaseException($result->error());
        }

        return $result->numRows() > 0;
    }

    /**
     * Add column to table.
     *
     * @param string $table
     * @param string $column
     * @param string $description
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function addColumn($table, $column, $description)
    {
        if (!$this->hasColumn($table, $column)) {
            $result = $this->query('ALTER TABLE ' . $table . ' ADD ' . $column . ' ' . $description);
            if ($result->error()) {
                throw new DatabaseException($result->error());
            }

            return $this->hasColumn($table, $column);
        }
        throw new DatabaseException('Cannot add column ' . $column . ' in table ' . $table . '. A column with that name already exists.');
    }

    /**
     * Modify column from table.
     *
     * @param string $table
     * @param string $column
     * @param string $description
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function modifyColumn($table, $column, $description)
    {
        if ($this->hasColumn($table, $column)) {
            $result = $this->query('ALTER TABLE ' . $table . ' MODIFY ' . $column . ' ' . $description);
            if ($result->error()) {
                throw new DatabaseException($result->error());
            }

            return true;
        }
        throw new DatabaseException('Cannot modify column ' . $column . ' in table ' . $table . '. A column with that name doesn\'t exists.');
    }

    /**
     * Drop column from table.
     *
     * @param string $table
     * @param string $column
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function dropColumn($table, $column)
    {
        if ($this->hasColumn($table, $column)) {
            $result = $this->query('ALTER TABLE ' . $table . ' DROP ' . $column);
            if ($result->error()) {
                throw new DatabaseException($result->error());
            }

            return true;
        }
        throw new DatabaseException('Cannot drop column ' . $column . ' in table ' . $table . '. A column with that name doesn\'t exists.');
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
     *
     * @throws DatabaseException
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

                return $this->pdo->exec($sql);
            }
            throw new DatabaseException('Dump file ' . $dumpFile . ' is empty');
        }
        throw new DatabaseException('Cannot read dump file ' . $dumpFile);
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
    public function __call($name, $args)
    {
        switch ($name) {
            case 'get_one':
                trigger_error('Method ' . $name . '(\$query) is deprecated, use the method getOne(\$query) instead', E_USER_DEPRECATED);

                return call_user_method('getOne', $this, $args);
            case 'is_error':
                trigger_error('Method ' . $name . '() is deprecated, use the method hasError() instead', E_USER_DEPRECATED);

                return call_user_method('hasError', $this, $args);
            case 'get_error':
                trigger_error('Method ' . $name . '() is deprecated, use the method getError() instead', E_USER_DEPRECATED);

                return call_user_method('getError', $this, $args);
            case 'escapeString':
                trigger_error('Method ' . $name . '(\$string) is deprecated, use the method quote(\$string) instead', E_USER_DEPRECATED);

                return call_user_method('quote', $this, $args);
            case 'field_exists':
                trigger_error('Method ' . $name . '(\$table_name, \$field_name) is deprecated, use the method hasColumn(\$table, \$column) instead', E_USER_DEPRECATED);

                return call_user_method('hasColumn', $this, $args);
            case 'field_add':
                trigger_error('Method ' . $name . '(\$table_name, \$field_name, \$description) is deprecated, use the method addColumn(\$table, \$column, \$description) instead', E_USER_DEPRECATED);

                return call_user_method('addColumn', $this, $args);
            case 'field_modify':
                trigger_error('Method ' . $name . '(\$table_name, \$field_name, \$description) is deprecated, use the method modifyColumn(\$table, \$column, \$description) instead', E_USER_DEPRECATED);

                return call_user_method('modifyColumn', $this, $args);
            case 'field_remove':
                trigger_error('Method ' . $name . '(\$table_name, \$field_name) is deprecated, use the method dropColumn(\$table, \$column) instead', E_USER_DEPRECATED);

                return call_user_method('dropColumn', $this, $args);
            case 'SqlImport':
                trigger_error('Method ' . $name . '(\$sSqlDump, \$sTablePrefix, \$bPreserve, \$sTblEngine, \$sTblCollation) is deprecated, use the method import((\$dumpFile, \$tablePrefix, \$preserve, \$tableEngine, \$tableCollation)) instead', E_USER_DEPRECATED);

                return call_user_method('import', $this, $args);
        }
    }
}
