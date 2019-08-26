<?php

namespace Wbce\Database\QueryBuilder\Query;

use Everest\Framework\Exception\InvalidArgumentException;

trait WhereTrait
{
    /**
     * Add raw WHERE condition.
     *
     * @param string $condition  Condition
     * @param array  $parameters Parameters
     *
     * @return static
     */
    public function whereRaw(string $condition, array $parameters = []): self
    {
        return $this->addStatement('WHERE', $condition, $parameters);
    }

    /**
     * Add where condition.
     *
     * @param string $column    Column name
     * @param string $operator  Where condition operator
     * @param mixed  $parameter Condition parameter
     *
     * @return static
     */
    public function where(string $column, string $operator, $parameter): self
    {
        if (in_array($operator, ['<', '>', '=', '!=', 'BETWEEN', 'LIKE', 'IS', 'IS NOT', 'IN'])) {
            if (is_null($parameter)) {
                if ('!=' === $operator) {
                    $operator = 'IS NOT';
                } elseif ('=' === $operator) {
                    $operator = 'IS';
                }

                return $this->addStatement('WHERE', $this->quoteIdentifier($column).' '.$operator.' NULL');
            } elseif (is_array($parameter)) {
                if (count($parameter) > 1) {
                    return $this->addStatement('WHERE',
                        $this->quoteIdentifier($column).' IN ('.implode(',', array_fill(0, count($parameter), '?')).')', $parameter);
                } elseif (1 === count($parameter)) {
                    $parameter = array_values($parameter)[0];
                } else {
                    $parameter = '';
                }
            }

            return $this->addStatement('WHERE', $this->quoteIdentifier($column).' '.$operator.' ?', [$parameter]);
        }
        throw new InvalidArgumentException('WHERE condition operator "'.$operator.'" is invalid');
    }
}
