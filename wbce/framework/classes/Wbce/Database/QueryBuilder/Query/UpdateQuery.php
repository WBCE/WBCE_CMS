<?php

namespace Wbce\Database\QueryBuilder\Query;

use Everest\Framework\Exception\RuntimeException;
use PDO;
use PDOStatement;

/**
 * @method UpdateQuery where(string $condition, string $operator, mixed $parameter) Add WHERE condition
 * @method UpdateQuery whereRaw(string $condition, array $parameters)               Add raw WHERE condition
 */
class UpdateQuery extends AbstractQuery
{
    /**
     * Where query trait.
     */
    use WhereTrait;

    /**
     * @var array
     */
    protected $clauses = [
        'UPDATE' => false,
        'SET' => ', ',
        'WHERE' => ' AND ',
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

        $this->addStatement('UPDATE', $this->quoteIdentifier($table));
    }

    /**
     * Add values to UPDATE statement.
     *
     * @param array $set List of update sets
     *
     * @return UpdateQuery
     */
    public function set(array $set = []): self
    {
        foreach ($set as $column => $value) {
            $this->statements['SET'][] = $this->quoteIdentifier($column).' = ?';
            $this->parameters['SET'][] = $value;
        }

        return $this;
    }

    /**
     * Execute update query.
     *
     * @param int $id Primary key
     *
     * @return PDOStatement|null
     */
    public function execute(int $id = 0): ?PDOStatement
    {
        if (count($this->statements['SET']) > 0) {
            if ($id) {
                $this->where($this->primaryKey, '=', $id);
            }

            return $this->executeQuery();
        }

        throw new RuntimeException('There is no SET for query defined');
    }
}
