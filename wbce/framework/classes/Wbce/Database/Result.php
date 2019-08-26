<?php

namespace Wbce\Database;

use PDO;
use PDOStatement;

class Result
{

    /**
     * PDO statement.
     *
     * @var PDOStatement
     */
    private $statement;

    /**
     * PDO fetch style.
     *
     * @var int
     */
    private $fetchStyle = PDO::FETCH_BOTH;

    /**
     * Constructor.
     *
     * @param PDOStatement $statement
     */
    public function __construct(PDOStatement $statement)
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

        return $this->statement->fetch($this->fetchStyle, PDO::FETCH_ORI_ABS, $offset);
    }

    /**
     * Fetch row.
     *
     * @param int $fetchStyle PDO fetch style
     *
     * @return mixed
     */
    public function fetchRow($fetchStyle = PDO::FETCH_BOTH)
    {
        // Replace of MYSQLI-fetch types
        if ($fetchStyle === MYSQLI_ASSOC) {
            $this->fetchStyle = PDO::FETCH_ASSOC;
        } else if ($fetchStyle === MYSQLI_BOTH) {
            $this->fetchStyle = PDO::FETCH_BOTH;
        } else {
            $this->fetchStyle = $fetchStyle;
        }

        return $this->statement->fetch($this->fetchStyle);
    }

    /**
     * Get PDO statement.
     *
     * @return PDOStatement
     */
    public function getPdoStatement()
    {
        return $this->statement;
    }
}
