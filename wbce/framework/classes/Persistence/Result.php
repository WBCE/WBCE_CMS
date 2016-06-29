<?php

namespace Persistence;

class Result
{
    /**
     * WDO statement.
     *
     * @var WDOStatement
     */
    private $statement;

    /**
     * WDO fetch style.
     *
     * @var int
     */
    private $fetchStyle = WDO::FETCH_BOTH;

    /**
     * Constructor.
     *
     * @param WDOStatement $statement
     */
    public function __construct(WDOStatement $statement)
    {
        $this->statement = $statement;
    }

    /**
     * Fetch number of rows.
     *
     * @return int
     */
    public function numRows()
    {
        return $this->statement->rowCount();
    }

    /**
     * Rewind fetch cursor.
     *
     * @return mixed
     */
    public function rewind()
    {
        return $this->seekRow();
    }

    /**
     * Seek for row.
     *
     * @param int $offset
     *
     * @return mixed
     */
    public function seekRow($offset = 0)
    {
        $numberOfRows = $this->numRows();
        if ($offset >= $numberOfRows) {
            $offset = $numberOfRows - 1;
        }

        return $this->statement->fetch($this->fetchStyle, WDO::FETCH_ORI_ABS, $offset);
    }

    /**
     * Fetch row.
     *
     * @param int $fetchStyle
     *
     * @return mixed
     */
    public function fetchRow($fetchStyle = WDO::FETCH_BOTH)
    {
        $this->fetchStyle = $fetchStyle;

        return $this->statement->fetch($fetchStyle);
    }

    /**
     * Get WDO statement.
     *
     * @return WDOStatement
     */
    public function getPdoStatement()
    {
        return $this->statement;
    }
}
