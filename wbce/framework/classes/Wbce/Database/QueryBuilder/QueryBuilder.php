<?php

namespace Wbce\Database\QueryBuilder;

use Wbce\Database\QueryBuilder\Query\DeleteQuery;
use Wbce\Database\QueryBuilder\Query\InsertQuery;
use Wbce\Database\QueryBuilder\Query\SelectQuery;
use Wbce\Database\QueryBuilder\Query\UpdateQuery;
use PDO;

class QueryBuilder
{
    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * Constructor.
     *
     * @param PDO $pdo PDO instance
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
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
        $query = new SelectQuery($table, $this->pdo);

        foreach ($columns as $column) {
            $query->select($column);
        }

        return $query;
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
        return new DeleteQuery($table, $this->pdo);
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
        return (new InsertQuery($table, $this->pdo))->values($values);
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
        return (new UpdateQuery($table, $this->pdo))->set($set);
    }
}
