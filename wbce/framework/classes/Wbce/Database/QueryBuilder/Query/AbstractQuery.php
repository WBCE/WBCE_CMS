<?php

namespace Wbce\Database\QueryBuilder\Query;

use DateTime;
use PDO;
use PDOStatement;

abstract class AbstractQuery
{
    /**
     * @var bool
     */
    protected $asObject = false;

    /**
     * @var array
     */
    protected $statements = [];

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var array
     */
    protected $clauses = [];

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * Set primary key column.
     *
     * @param string $primaryKey
     *
     * @return static
     */
    public function setPrimaryKey(string $primaryKey): self
    {
        $this->primaryKey = $primaryKey;

        return $this;
    }

    /**
     * Add statement.
     *
     * @param string $clause
     * @param string $statement
     * @param array  $parameters
     *
     * @return static
     */
    protected function addStatement(string $clause, string $statement, array $parameters = []): self
    {
        $this->statements[$clause][] = $statement;
        $this->parameters[$clause][] = $parameters;

        return $this;
    }

    /**
     * Execute query.
     *
     * @return PDOStatement|null
     */
    protected function executeQuery(): ?PDOStatement
    {
        $query = $this->buildQuery();
        $parameters = $this->buildParameters();

        $statement = $this->pdo->prepare($query);

        if ($statement) {
            if ($this->asObject) {
                if (class_exists($this->asObject)) {
                    $statement->setFetchMode(PDO::FETCH_CLASS, $this->asObject);
                } else {
                    $statement->setFetchMode(PDO::FETCH_OBJ);
                }
            } elseif (PDO::FETCH_BOTH == $this->pdo->getAttribute(PDO::ATTR_DEFAULT_FETCH_MODE)) {
                $statement->setFetchMode(PDO::FETCH_ASSOC);
            }

            $statement->execute($parameters);

            return $statement;
        }

        return null;
    }

    /**
     * Get parameters.
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->buildParameters();
    }

    /**
     * Get query string.
     *
     * @return string
     */
    public function getQuery(): string
    {
        $query = $this->buildQuery();

        return $query;
    }

    /**
     * Get formatted and readable query string.
     *
     * @return string
     */
    public function getFormattedQuery(): string
    {
        $query = $this->getQuery();

        return $this->formatQuery($query);
    }

    /**
     * Format to readable query.
     *
     * @param string $query
     *
     * @return string
     */
    protected function formatQuery(string $query): string
    {
        // Add line break
        $query = preg_replace('/WHERE|FROM|GROUP BY|HAVING|ORDER BY|LIMIT|OFFSET|UNION|ON DUPLICATE KEY UPDATE|VALUES/', '\n$0', $query);

        // Add line break and spaces
        $query = preg_replace('/INNER|LEFT|RIGHT|OUTER|CASE|WHEN|END|ELSE|AND/', '\n    $0', $query);

        // remove trailing spaces
        $query = preg_replace('/\s+\n/', '\n', $query);

        return $query;
    }

    /**
     * Build query with statements.
     *
     * @return string
     */
    protected function buildQuery(): string
    {
        $query = '';
        foreach ($this->clauses as $clause => $separator) {
            $clauseStatements = $this->statements[$clause];
            if (count($clauseStatements) > 0) {
                if (is_callable($separator)) {
                    $query .= call_user_func_array($separator, [$clauseStatements]);
                } else {
                    $query .= ' '.$clause.' '.implode($separator, $clauseStatements);
                }
            }
        }

        $query = trim($query);

        return $query;
    }

    /**
     * Quote identifier to avoid using reserved keywords as characters beyond a limited set.
     *
     * @param string $identifier Identifier to quote
     *
     * @return string
     *
     * @see https://stackoverflow.com/questions/11321491/when-to-use-single-quotes-double-quotes-and-backticks-in-mysql/11321508#11321508
     * @see http://php.net/manual/de/pdo.quote.php#112169
     */
    protected function quoteIdentifier(string $identifier): string
    {
        return '`'.str_replace(['`', '.'], ['``', '`.`'], $identifier).'`';
    }

    /**
     * Build parameters.
     *
     * @return array
     */
    protected function buildParameters(): array
    {
        $parameters = [];
        foreach ($this->parameters as $parameter) {
            if (is_array($parameter)) {
                foreach ($parameter as $value) {
                    if (is_array($value)) {
                        $parameters = array_merge($parameters, $value);
                    } else {
                        $parameters[] = $value;
                    }
                }
            } else {
                $parameters[] = $parameter;
            }
        }

        $parameters = array_map(function ($parameter) {
            // Fix boolean behavior when using PDO and prepared statements
            // @link https://evertpot.com/mysql-bool-behavior-and-php/
            if (is_bool($parameter)) {
                $parameter = (int) $parameter;
            }

            return $parameter;
        }, $parameters);

        return $parameters;
    }

    /**
     * Quote a value for use in a query.
     *
     * @param mixed $value Value to quote
     *
     * @return string
     */
    protected function quote($value): string
    {
        if (!isset($value)) {
            return 'NULL';
        }
        if (is_array($value)) { // (a, b) IN ((1, 2), (3, 4))
            return '('.implode(', ', array_map([$this, 'quote'], $value)).')';
        }
        if ($value instanceof DateTime) {
            return $value->format('Y-m-d H:i:s'); //! may be driver specific
        }
        if (is_float($value)) {
            return sprintf('%F', $value); // otherwise depends on setlocale()
        }
        if (false === $value) {
            return '0';
        }
        if (is_int($value)) {
            return (string) $value;
        }

        return $this->pdo->quote($value);
    }
}
