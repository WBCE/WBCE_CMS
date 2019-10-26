<?php

namespace Wbce\Database;

use PDO;
use PDOStatement;
use Wbce\Database\QueryBuilder\Query\DeleteQuery;
use Wbce\Database\QueryBuilder\Query\InsertQuery;
use Wbce\Database\QueryBuilder\Query\SelectQuery;
use Wbce\Database\QueryBuilder\Query\UpdateQuery;
use Wbce\Database\QueryBuilder\QueryBuilder;

class Database
{
    /**
     * Error message.
     *
     * @var string
     */
    protected $error = '';

    /**
     * PDO driver.
     *
     * @var string
     */
    protected $driver = 'mysql';

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
     * Table prefixes.
     *
     * @var string
     */
    protected $tablePrefixes = [
        '{tp}' => '',
        '{TP}' => '',
        '{TABLE_PREFIX}' => '',
    ];

    /**
     * PDO connection.
     *
     * @var PDO
     */
    protected $pdo;

    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * @var array
     */
    protected $options = [
        'throwExceptions' => false,
        'triggerDeprecatedError' => false,
        'autoConnect' => true,
    ];

    /**
     * Constructor.
     *
     * @param array $options Database options
     *
     * @throws DatabaseException
     */
    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->options, $options);

        if (defined('DB_DSN') && strlen(DB_DSN) > 0) {
            $this->dsn = DB_DSN;
        } else {
            if (defined('DB_DRIVER')) {
                $this->driver = DB_DRIVER;
            }

            if (defined('DB_HOST')) {
                $this->host = DB_HOST;

                if (defined('DB_PORT')) {
                    $this->host .= ':' . DB_PORT;
                }
            }

            if (defined('DB_NAME')) {
                $this->dbname = DB_NAME;
            } elseif ($this->options['throwExceptions']) {
                throw new DatabaseException('DB_NAME as named constant not defined');
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
                $this->tablePrefixes = [
                    '{tp}' => TABLE_PREFIX,
                    '{TP}' => TABLE_PREFIX,
                    '{TABLE_PREFIX}' => TABLE_PREFIX,
                ];
            }
        }

        if ($this->options['autoConnect']) {
            $this->connect();
        }
    }

    /**
     * Connect to database.
     */
    public function connect(): void
    {
        $options = [
            PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL,
        ];

        if ($this->dsn) {
            $this->pdo = new PDO($this->dsn);
        } else {
            $this->pdo = new PDO($this->driver . ':host=' . $this->host . ';dbname=' . $this->dbname, $this->username, $this->password, $options);
            $this->pdo->exec('SET NAMES ' . $this->charset);
        }

        if ('mysql' === $this->driver) {
            $this->pdo->exec('SET @@sql_mode=""');
        }

        $this->queryBuilder = new QueryBuilder($this->pdo);
    }

    /**
     * Create SELECT query.
     *
     * @param string $table Name of table
     * @param array $columns Name of columns to select
     *
     * @return SelectQuery
     */
    public function selectFrom(string $table, array $columns = []): SelectQuery
    {
        $table = $this->replaceTablePrefixes($table);

        return $this->queryBuilder->selectFrom($table, $columns);
    }

    /**
     * Create DELETE query.
     *
     * @param string $table Name of table
     *
     * @return DeleteQuery
     */
    public function deleteFrom(string $table): DeleteQuery
    {
        $table = $this->replaceTablePrefixes($table);

        return $this->queryBuilder->deleteFrom($table);
    }

    /**
     * Create INSERT query.
     *
     * @param string $table Name of table
     * @param array $values Values as associative array (column => value)
     *
     * @return InsertQuery
     */
    public function insertInto(string $table, array $values = []): InsertQuery
    {
        $table = $this->replaceTablePrefixes($table);

        return $this->queryBuilder->insertInto($table, $values);
    }

    /**
     * Create UPDATE query.
     *
     * @param string $table Name of table
     * @param array $set Values as associative array (column => value)
     *
     * @return UpdateQuery
     */
    public function update(string $table, array $set = []): UpdateQuery
    {
        $table = $this->replaceTablePrefixes($table);

        return $this->queryBuilder->update($table, $set);
    }

    /**
     * Replace table prefixes.
     *
     * @param string $query SQL query
     *
     * @return string
     */
    protected function replaceTablePrefixes(string $query): string
    {
        foreach ($this->tablePrefixes as $placeholder => $value) {
            $query = str_replace($placeholder, $value, $query);
        }

        return $query;
    }

    /**
     * Add prefix.
     *
     * @example $database->addPrefix('{DROPLETS_TABLE}', TABLE_PREFIX.'mod_droplets');
     *
     * @param string $placeholder Placeholder of the prefix
     * @param string $value Value of the prefix
     */
    public function addPrefix(string $placeholder, string $value): void
    {
        $this->tablePrefixes[$placeholder] = $value;
    }

    /**
     * Disconnect from database.
     *
     * @return bool
     */
    public function disconnect(): bool
    {
        $this->pdo = null;

        return true;
    }

    /**
     * Execute query.
     *
     * @param string $query SQL query
     *
     * @return Result|null
     */
    public function query(string $query): ?Result
    {
        $query = $this->replaceTablePrefixes($query);
        $statement = $this->pdo->query($query);
        $this->setError($statement);

        if ($statement) {
            return new Result($statement);
        }

        return null;
    }

    /**
     * Execute prepared query.
     *
     * @param string $query SQL query
     * @param array $parameters Query parameters
     *
     * @return Result|null
     */
    public function preparedQuery($query, array $parameters = []): ?Result
    {
        $query = $this->replaceTablePrefixes($query);
        $statement = $this->pdo->prepare($query);
        $statement->execute($parameters);
        $this->setError($statement);

        if ($statement) {
            return new Result($statement);
        }

        return null;
    }

    /**
     * Get the first column value of first row.
     *
     * @param string $query SQL query
     *
     * @return mixed
     */
    public function getOne($query)
    {
        $query = $this->replaceTablePrefixes($query);
        $statement = $this->pdo->query($query);
        $this->setError($statement);

        if ($statement) {
            return $statement->fetchColumn();
        }

        return null;
    }
        
    /**
     * Get result of the query as associative array.
     *
     * @param string $query SQL query
     *
     * @return array
     */
    public function getArray($query)
    {
        $aData = array();
        if($resData = $this->query($query)){
            while($rec = $resData->fetchRow(MYSQLI_ASSOC)){
                $aData[] = $rec;
            }        
        }
        return $aData;
    }

    /**
     * Check whether error message exists.
     *
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->getError();
    }

    /**
     * Get error message.
     *
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * Set error.
     *
     * @param mixed $message Error message
     *
     * @throws DatabaseException
     */
    protected function setError($message = null): void
    {
        if (!is_string($message)) {
            $errorInfo = $this->pdo->errorInfo();
            if ($message instanceof PDOStatement) {
                $errorInfo = $message->errorInfo();
            }

            if (isset($errorInfo[2])) {
                $message = $errorInfo[2];
            }
        }

        if (is_string($message)) {
            if ($this->options['throwExceptions']) {
                throw new DatabaseException($message);
            } else {
                $this->error = $message;
            }
        }
    }

    /**
     * Get PDO connection.
     *
     * @return PDO
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
        return $this->dbname ?: $this->dsn;
    }

    /**
     * Quote a string for use in a query.
     *
     * @param string $string
     *
     * @return string
     */
    public function quote($string): string
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
    public function getTableEngine(string $table): string
    {
        if ('mysql' === $this->driver) {
            $result = $this->preparedQuery('SHOW TABLE STATUS LIKE :table', [
                'table' => $table,
            ]);

            if ($result && !$this->getError()) {
                $row = $result->fetchRow();

                return $row['Engine'];
            }
        }

        return $this->driver;
    }

    /**
     * Check whether column in table exists.
     *
     * @param string $table Table name
     * @param string $column Column name
     *
     * @return bool
     */
    public function hasColumn(string $table, string $column)
    {
        $table = $this->replaceTablePrefixes($table);
        $result = $this->preparedQuery('SHOW COLUMNS FROM ' . $table . ' LIKE :column', [
            'column' => $column,
        ]);

        if ($result && !$this->getError()) {
            return $result->numRows() > 0;
        }

        return false;
    }

    /**
     * Add column to table.
     *
     * @param string $table Table name
     * @param string $column Column name
     * @param string $description Column description
     *
     * @return bool
     */
    public function addColumn(string $table, string $column, string $description)
    {
        $table = $this->replaceTablePrefixes($table);
        if (!$this->hasColumn($table, $column)) {
            $result = $this->query('ALTER TABLE ' . $table . ' ADD ' . $column . ' ' . $description);

            if ($result && !$this->getError()) {
                return $this->hasColumn($table, $column);
            }
        } else {
            $this->setError('Cannot add column ' . $column . ' in table ' . $table . ', because a column with that name already exists');
        }

        return false;
    }

    /**
     * Modify column from table.
     *
     * @param string $table Table name
     * @param string $column Column name
     * @param string $description Column description
     *
     * @return bool
     */
    public function modifyColumn(string $table, string $column, string $description)
    {
        $table = $this->replaceTablePrefixes($table);
        if ($this->hasColumn($table, $column)) {
            $result = $this->query('ALTER TABLE ' . $table . ' MODIFY ' . $column . ' ' . $description);

            return $result && !$this->getError();
        } else {
            $this->setError('Cannot modify column ' . $column . ' in table ' . $table . ', because a column with that name doesn\'t exists');
        }

        return false;
    }

    /**
     * Drop column from table.
     *
     * @param string $table Table name
     * @param string $column Column name
     *
     * @return bool
     */
    public function dropColumn(string $table, string $column)
    {
        $table = $this->replaceTablePrefixes($table);
        if ($this->hasColumn($table, $column)) {
            $result = $this->query('ALTER TABLE ' . $table . ' DROP ' . $column);

            return $result && !$this->getError();
        } else {
            $this->setError('Cannot drop column ' . $column . ' in table ' . $table . ', because a column with that name doesn\'t exists');
        }

        return false;
    }

    /**
     * Import SQL dump file.
     *
     * @param string $dumpFile File path of dump file
     * @param string $tablePrefix Table prefix
     * @param bool $preserve Set FALSE to disable "DROP TABLE IF EXISTS"
     * @param string $tableEngine Table engine
     * @param string $tableCollation Table collation
     *
     * @return mixed
     */
    public function import(string $dumpFile, string $tablePrefix = '', bool $preserve = true, string $tableEngine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci', string $tableCollation = ' collate utf8_unicode_ci')
    {
        if (is_file($dumpFile)) {
            $sql = file_get_contents($dumpFile);

            if (strlen($sql) > 0) {
                $sql = str_replace(['{tp}', '{TABLE_PREFIX}', '{TABLE_ENGINE}', '{TABLE_COLLATION}'], [
                    $tablePrefix, $tablePrefix, $tableEngine, $tableCollation,
                ], $sql);

                $sql = $this->replaceTablePrefixes($sql);

                if ($preserve) {
                    $sql = preg_replace('/(.*DROP\sTABLE\sIF\sEXISTS.*)/', '', $sql);
                }

                $result = $this->query($sql);

                return $result && !$this->getError();
            } else {
                $this->setError('Dump file ' . $dumpFile . ' is empty');
            }
        } else {
            $this->setError('Cannot read dump file ' . $dumpFile);
        }

        return false;
    }

    /**
     * Update row.
     *
     * @param string $table
     * @param string $primaryKey
     * @param array $data
     *
     * @return Result|bool
     */
    public function updateRow($table, $primaryKey, array $data)
    {
        if (isset($data[$primaryKey])) {
            $parameters = [];
            $sets = [];

            foreach ($data as $column => $value) {
                if ($column !== $primaryKey) {
                    $parameters[] = $value;
                    $sets[] = $column . ' = ?';
                }
            }

            $parameters[] = $data[$primaryKey];

            $sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $sets) . ' WHERE ' . $primaryKey . ' = ?';

            $result = $this->preparedQuery($sql, $parameters);

            if ($result && !$this->getError()) {
                return $result;
            }
        }
        $this->setError('Cannot find primary key ' . $primaryKey . ' in data');

        return false;
    }

    /**
     * Insert row.
     *
     * @param string $table
     * @param array $data
     *
     * @return Result
     */
    public function insertRow(string $table, array $data)
    {
        $parameters = [];
        $values = [];

        $columns = array_keys($data);
        foreach ($data as $value) {
            $parameters[] = $value;
            $values[] = '?';
        }

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
    public function __get(string $name)
    {
        switch ($name) {
            case 'db_handle':
            case 'DbHandle':
                $this->triggerDeprecatedError('Property ' . $name . ' is deprecated, use the method getPdo() instead');

                return $this->getPdo();
            case 'db_name':
            case 'DbName':
                $this->triggerDeprecatedError('Property ' . $name . ' is deprecated, use the method getDatabaseName() instead');

                return $this->getDatabaseName();
        }
    }

    /**
     * Magic call.
     *
     * @param string $name
     * @param array $args
     *
     * @return mixed
     */
    public function __call(string $name, array $args)
    {
        switch ($name) {
            case 'get_one':
                $this->triggerDeprecatedError('The method name ' . $name . '(\$query) is deprecated, use the method getOne(\$query) instead');

                return call_user_func_array([$this, 'getOne'], $args);
            case 'get_array':
                $this->triggerDeprecatedError('The method name ' . $name . '(\$query) is deprecated, use the method getOne(\$query) instead');

                return call_user_func_array([$this, 'getArray'], $args);
            case 'is_error':
                $this->triggerDeprecatedError('The method name ' . $name . '() is deprecated, use the method hasError() instead');

                return call_user_func_array([$this, 'hasError'], $args);
            case 'get_error':
                $this->triggerDeprecatedError('The method name ' . $name . '() is deprecated, use the method getError() instead');

                return call_user_func_array([$this, 'getError'], $args);
            case 'escapeString':
                $this->triggerDeprecatedError('The method name ' . $name . '(\$string) is deprecated, use the method quote(\$string) instead');

                return call_user_func_array([$this, 'quote'], $args);
            case 'field_exists':
                $this->triggerDeprecatedError('The method name' . $name . '(\$table_name, \$field_name) is deprecated, use the method hasColumn(\$table, \$column) instead');

                return call_user_func_array([$this, 'hasColumn'], $args);
            case 'field_add':
                $this->triggerDeprecatedError('The method name ' . $name . '(\$table_name, \$field_name, \$description) is deprecated, use the method addColumn(\$table, \$column, \$description) instead');

                return call_user_func_array([$this, 'addColumn'], $args);
            case 'field_modify':
                $this->triggerDeprecatedError('The method name ' . $name . '(\$table_name, \$field_name, \$description) is deprecated, use the method modifyColumn(\$table, \$column, \$description) instead');

                return call_user_func_array([$this, 'modifyColumn'], $args);
            case 'field_remove':
                $this->triggerDeprecatedError('The method name ' . $name . '(\$table_name, \$field_name) is deprecated, use the method dropColumn(\$table, \$column) instead');

                return call_user_func_array([$this, 'dropColumn'], $args);
            case 'SqlImport':
                $this->triggerDeprecatedError('The method name ' . $name . '(\$sSqlDump, \$sTablePrefix, \$bPreserve, \$sTblEngine, \$sTblCollation) is deprecated, use the method import((\$dumpFile, \$tablePrefix, \$preserve, \$tableEngine, \$tableCollation)) instead');

                return call_user_func_array([$this, 'import'], $args);
        }
    }

    /**
     * Trigger deprecated error.
     *
     * @param string $message
     */
    protected function triggerDeprecatedError(string $message)
    {
        if ($this->options['triggerDeprecatedError']) {
            trigger_error($message, E_USER_DEPRECATED);
        }
    }
}
