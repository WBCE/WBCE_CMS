<?php

namespace Wbce\Database\QueryBuilder\Query;

use Wbce\Database\QueryBuilder\Statement;
use PDO;
use PDOStatement;

/**
 * @method SelectQuery where(string $condition, string $operator, mixed $parameter) Add WHERE condition
 * @method SelectQuery whereRaw(string $condition, array $parameters)               Add raw WHERE condition
 * @method SelectQuery leftJoin(string $table, array $condition)                    Add LEFT JOIN statement
 * @method SelectQuery innerJoin(string $table, array $condition)                   Add INNER JOIN statement
 * @method SelectQuery rightJoin (string $table, array $condition)                  Add INNER JOIN statement
 * @method SelectQuery outerJoin(string $table, array $condition)                   Add INNER JOIN statement
 */
class SelectQuery extends AbstractQuery
{
    /**
     * Query traits.
     */
    use WhereTrait;
    use JoinTrait;

    /**
     * @var array
     */
    protected $clauses = [
        'SELECT' => ', ',
        'FROM' => false,
        'LEFT JOIN' => false,
        'INNER JOIN' => false,
        'RIGHT JOIN' => false,
        'OUTER JOIN' => false,
        'WHERE' => ' AND ',
        'GROUP BY' => ', ',
        'HAVING' => ' AND ',
        'ORDER BY' => ', ',
        'LIMIT' => false,
        'OFFSET' => false,
    ];

    /**
     * Constructor.
     *
     * @param string $table Table name
     * @param PDO    $pdo   PDO instance
     */
    public function __construct(string $table, PDO $pdo)
    {
        $this->pdo = $pdo;

        foreach (array_keys($this->clauses) as $clause) {
            $this->statements[$clause] = [];
            $this->parameters[$clause] = [];
        }

        $this->addStatement('FROM', $this->quoteIdentifier($table));
    }

    /**
     * Fetch only one column.
     *
     * @param string $column
     *
     * @return mixed
     */
    public function fetchColumn(string $column = '')
    {
        $statement = $this->asObject(false)->execute();
        if ($statement) {
            $result = $statement->fetch();
            if ($result) {
                return $result[$column];
            }
        }

        return null;
    }

    /**
     * Fetch only first column.
     *
     * @return mixed
     */
    public function fetchFirstColumn()
    {
        return $this->fetchColumnByIndex(0);
    }

    /**
     * Fetch only one column by index.
     *
     * @param int $columnIndex
     *
     * @return mixed
     */
    public function fetchColumnByIndex(int $columnIndex)
    {
        $statement = $this->asObject(false)->execute();
        if ($statement) {
            return $statement->fetchColumn($columnIndex);
        }

        return null;
    }

    /**
     * Execute select query.
     *
     * @return PDOStatement|null
     */
    public function execute(): ?PDOStatement
    {
        if (0 === count($this->statements['SELECT'])) {
            $this->statements['SELECT'][] = '*';
        }

        return $this->executeQuery();
    }

    /**
     * Add SELECT for column(s).
     *
     * @param string $column
     *
     * @return static
     */
    public function select(string $column): self
    {
        return $this->addStatement('SELECT', $this->quoteIdentifier($column));
    }

    /**
     * Add raw SELECT statement.
     *
     * @param string $statement
     *
     * @return static
     */
    public function selectRaw(string $statement): self
    {
        return $this->addStatement('SELECT', $statement);
    }

    /**
     * Add GROUP BY for column.
     *
     * @param string $column
     *
     * @return static
     */
    public function groupBy(string $column): self
    {
        return $this->addStatement('GROUP BY', $this->quoteIdentifier($column));
    }

    /**
     * Add HAVING for column.
     *
     * @param string $column
     *
     * @return static
     */
    public function having(string $column): self
    {
        return $this->addStatement('HAVING', $this->quoteIdentifier($column));
    }

    /**
     * Add ORDER BY ASC for column.
     *
     * @param string $column
     *
     * @return static
     */
    public function orderByAsc(string $column): self
    {
        return $this->orderByRaw($this->quoteIdentifier($column).' ASC');
    }

    /**
     * Add ORDER BY ASC for column.
     *
     * @param string $column
     *
     * @return static
     */
    public function orderByDesc(string $column): self
    {
        return $this->orderByRaw($this->quoteIdentifier($column).' DESC');
    }

    /**
     * Add raw ORDER BY statement.
     *
     * @param string $statement
     *
     * @return static
     */
    public function orderByRaw(string $statement): self
    {
        return $this->addStatement('ORDER BY', $statement);
    }

    /**
     * Add LIMIT.
     *
     * @param int $limit
     *
     * @return static
     */
    public function limit(int $limit): self
    {
        return $this->addStatement('LIMIT', $limit);
    }

    /**
     * Add OFFSET.
     *
     * @param int $offset
     *
     * @return static
     */
    public function offset(int $offset): self
    {
        return $this->addStatement('OFFSET', $offset);
    }

    /**
     * Fetch first row.
     *
     * @param mixed $id Identifier
     *
     * @return mixed
     */
    public function fetch($id = null)
    {
        if ($id) {
            $this->where($this->primaryKey, '=', $id);
        }

        $this->limit(1);

        $statement = $this->execute();
        if ($statement) {
            return $statement->fetch();
        }

        return null;
    }

    /**
     * Fetch all rows.
     *
     * @return array
     */
    public function fetchAll(): array
    {
        $statement = $this->execute();
        if ($statement) {
            $result = $statement->fetchAll();

            if (is_array($result)) {
                return $result;
            }
        }

        return [];
    }

    /**
     * Count rows.
     *
     * @return int
     */
    public function count(): int
    {
        return (int) $this->selectRaw('COUNT(*)')->fetchFirstColumn();
    }

    /**
     * Set fetch mode as object.
     *
     * @param bool|string $enable Set FALSE to disable fetch mode as object or set class name
     *
     * @return static
     */
    public function asObject($enable = true): self
    {
        $this->asObject = $enable;

        return $this;
    }
}
