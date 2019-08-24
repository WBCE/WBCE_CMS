<?php

namespace Wbce\Database\QueryBuilder\Query;

use PDO;

/**
 * @method DeleteQuery where(string $condition, string $operator, mixed $parameter) Add WHERE condition
 * @method DeleteQuery whereRaw(string $condition, array $parameters)               Add raw WHERE condition
 */
class DeleteQuery extends AbstractQuery
{
    /**
     * WHERE query trait.
     */
    use WhereTrait;

    /**
     * @var array
     */
    protected $clauses = [
        'DELETE FROM' => false,
        'FROM' => null,
        'WHERE' => ' AND ',
    ];

    /**
     * @var bool
     */
    protected $ignore = false;

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

        $this->addStatement('DELETE FROM', $this->quoteIdentifier($table));
    }

    /**
     * Enable/disable ignoring and query fails silently.
     *
     * @param bool $enable Set FALSE to prevent ignoring query fails silently
     *
     * @return static
     */
    public function ignore(bool $enable = true): self
    {
        $this->ignore = $enable;

        return $this;
    }

    /**
     * Build query with statements.
     *
     * @return string
     */
    protected function buildQuery(): string
    {
        $query = parent::buildQuery();
        if ($this->ignore) {
            return str_replace('DELETE', 'DELETE IGNORE', $query);
        }

        return $query;
    }

    /**
     * Execute delete query.
     *
     * @param int $id
     *
     * @return bool
     */
    public function execute(int $id = 0): bool
    {
        if (0 !== $id) {
            $this->where($this->primaryKey, '=', $id);
        }

        return $this->executeQuery();
    }
}
