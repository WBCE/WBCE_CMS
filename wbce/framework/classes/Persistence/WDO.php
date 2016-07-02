<?php

namespace Persistence;

class WDO extends \PDO
{
    /**
     * Table prefix.
     *
     * @var string
     */
    protected $tablePrefix;

    /**
     * Number of executed queries.
     *
     * @var int
     */
    protected $numberOfQueries = 0;

    /**
     * Constructor.
     *
     * @param string $dsn
     * @param string $user
     * @param string $password
     * @param array  $driverOptions
     * @param string $tablePrefix
     */
    public function __construct($dsn, $user = null, $password = null, array $driverOptions = array(), $tablePrefix = null)
    {
        $this->tablePrefix = $tablePrefix;
        parent::__construct($dsn, $user, $password, $driverOptions);
    }

    /**
     * Execute an SQL statement and return the number of affected rows.
     *
     * @param string $statement
     *
     * @return int
     */
    public function exec($statement)
    {
        $this->numberOfQueries += 1;
        $statement = $this->replaceTablePrefix($statement);

        return parent::exec($statement);
    }

    /**
     * Prepares a statement for execution and returns a statement object.
     *
     * @param string $statement
     * @param array  $driverOptions
     *
     * @return \PDOStatement
     */
    public function prepare($statement, array $driverOptions = array())
    {
        $this->numberOfQueries += 1;
        $statement = $this->replaceTablePrefix($statement);

        return parent::prepare($statement, $driverOptions);
    }

    /**
     * Executes an SQL statement, returning a result set as a PDO statement object.
     *
     * @param string $statement
     *
     * @return \PDOStatement
     */
    public function query($statement)
    {
        $this->numberOfQueries += 1;
        $statement = $this->replaceTablePrefix($statement);

        return parent::query($statement);
    }

    /**
     * Replace placeholder with table prefix.
     *
     * @param string $statement
     *
     * @return string
     */
    protected function replaceTablePrefix($statement)
    {
        return str_replace(array('{TABLE_PREFIX}', '{TP}'), $this->tablePrefix, $statement);
    }

    /**
     * Get number of executed queries.
     *
     * @return int
     */
    public function getNumberOfQueries()
    {
        return $this->numberOfQueries;
    }
}
