<?php

namespace Wbce\Database\QueryBuilder\Query;

use PDO;
use PDOStatement;

/**
 * @method InsertQuery where(string $condition, array $parameters) Add WHERE condition
 */
class InsertQuery extends AbstractQuery
{
    /**
     * @var array
     */
    protected $clauses = [
        'INSERT INTO' => false,
        'VALUES' => ', ',
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

        $this->addStatement('INSERT INTO', $this->quoteIdentifier($table));

        $this->clauses['VALUES'] = function ($clauseStatement) {
            $query = ' ('.implode(', ', $clauseStatement).') VALUES ('.str_repeat('?, ', count($clauseStatement) - 1).'?) ';

            return $query;
        };
    }

    /**
     * Execute insert query.
     *
     * @return PDOStatement|null
     */
    public function execute(): ?PDOStatement
    {
        return $this->executeQuery();
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
            return str_replace('INSERT INTO', 'INSERT IGNORE INTO', $query);
        }

        return $query;
    }

    /**
     * Add values to INSERT statement.
     *
     * @param array $values
     *
     * @return static
     */
    public function values(array $values = []): self
    {
        foreach ($values as $column => $value) {
            $this->statements['VALUES'][] = $this->quoteIdentifier($column);
            $this->parameters['VALUES'][] = $value;
        }

        return $this;
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
}
