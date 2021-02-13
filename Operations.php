<?php


namespace Alexcrisbrito\Php_crud;


final class Operations
{

    /* SQL query to be executed on database */
    private string $query;

    /* Where clause */
    private string $clause = " WHERE 1";

    /* The fetching limit */
    private string $limit;

    /* The primary key of the table */
    private string $primary;

    /* The ordering of result selection */
    private string $order;

    public function __construct(string $query, string $primary)
    {
        $this->primary = $primary;
        $this->query = $query;
    }

    /**
     * Set the terms of the
     * query to execute
     *
     * @param string $terms
     * @return $this
     */
    public function where(string $terms): Operations
    {
        $this->clause = " WHERE {$terms}";

        return $this;
    }

    /**
     *
     * Limit records fetching
     *
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): Operations
    {
        $this->limit = " LIMIT {$limit}";

        return $this;
    }


    /**
     * Set the terms of the
     * query to execute
     *
     * @param string|null $column
     * @param string $order
     * @return $this
     */
    public function order(string $column = null, string $order = "DESC"): Operations
    {

        $this->order = " ORDER BY '" . (is_null($column) ? $this->primary : $column) . "' {$order}";

        return $this;
    }

    /**
     *
     * Execute the query
     * on the database
     *
     */
    public function execute()
    {
        $query = $this->buildQuery();

        $conn = Connection::connect();
        $stmt = $conn->prepare($query);

        if ($stmt->execute()) {

            switch ($this->detectOperation()) {
                case 'delete':
                case 'update':
                    return $stmt->rowCount() >= 1;
                    break;

                case 'select':
                    return $stmt->rowCount() >= 1 ? $stmt->fetchAll() : $stmt->fetch();
                    break;


                case 'insert':
                    return $stmt->rowCount() >= 1 ? $conn->lastInsertId() : false;
                    break;

                default:
                    return true;
            }

        }

        return false;
    }

    /**
     * Detect the current
     * operation type
     * to set return
     * type on execute
     * method
     *
     * @return string
     */
    private function detectOperation(): string
    {
        $trim = mb_split(" ", $this->query);

        return mb_strtolower($trim[0]);
    }


    /**
     *
     * Build the query
     * prior to execution
     *
     * @return string
     *
     */
    private function buildQuery(): string
    {
        switch ($this->detectOperation()) {

            case 'select':
                $query = $this->query . $this->clause;
                if (!empty($this->order)) $query .= $this->order;
                if (!empty($this->limit)) $query .= $this->limit;
                break;

            case 'update':
            case 'delete':
                $query = $this->query . $this->clause;
                if (!empty($this->limit)) $query .= $this->limit;
                break;

            default:
                $query = $this->query;
                break;
        }

        return $query;
    }
}