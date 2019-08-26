<?php

namespace Wbce\Database\QueryBuilder\Query;

trait JoinTrait
{
    /**
     * Add LEFT JOIN statement.
     *
     * @param string $table
     * @param string $condition
     *
     * @return AbstractQuery
     */
    public function leftJoin(string $table, string $condition): AbstractQuery
    {
        return $this->addStatement('LEFT JOIN', $this->quoteIdentifier($table).' ON '.$condition);
    }

    /**
     * Add INNER JOIN statement.
     *
     * @param string $table
     * @param string $condition
     *
     * @return AbstractQuery
     */
    public function innerJoin(string $table, string $condition): AbstractQuery
    {
        return $this->addStatement('INNER JOIN', $this->quoteIdentifier($table).' ON '.$condition);
    }

    /**
     * Add RIGHT JOIN statement.
     *
     * @param string $table
     * @param string $condition
     *
     * @return AbstractQuery
     */
    public function rightJoin(string $table, string $condition): AbstractQuery
    {
        return $this->addStatement('RIGHT JOIN', $this->quoteIdentifier($table).' ON '.$condition);
    }

    /**
     * Add OUTER JOIN statement.
     *
     * @param string $table
     * @param string $condition
     *
     * @return AbstractQuery
     */
    public function outerJoin(string $table, string $condition): AbstractQuery
    {
        return $this->addStatement('OUTER JOIN', $this->quoteIdentifier($table).' ON '.$condition);
    }
}
