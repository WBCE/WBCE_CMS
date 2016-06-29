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
        $this->setAttribute(self::ATTR_STATEMENT_CLASS, array('WDOStatement', array($this)));
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
        $statement = $this->replaceTablePrefix($statement);

        return parent::exec($statement);
    }

    /**
     * Prepares a statement for execution and returns a statement object.
     *
     * @param string $statement
     * @param array  $driverOptions
     *
     * @return \WDOStatement
     */
    public function prepare($statement, array $driverOptions = array())
    {
        $statement = $this->replaceTablePrefix($statement);

        return parent::prepare($statement, $driverOptions);
    }

    /**
     * Executes an SQL statement, returning a result set as a WDOStatement object.
     *
     * @param string $statement
     *
     * @return \WDOStatement
     */
    public function query($statement)
    {
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
        return str_replace(array('[TP]', '{TP}'), $this->tablePrefix, $statement);
    }
}
