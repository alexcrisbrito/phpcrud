<?php


namespace Alexcrisbrito\Php_crud;


final class Operations
{

    /* SQL query to be executed on database */
    private string $query;

    /* Where clause */
    private array $clause = [
        "distinct" => "",
        "where" => "",
        "in" => "",
        "like" => "",
        "limit" => "",
        "order" => "",
        "group_by" => ""
    ];

    /* The primary key of the table */
    private string $primary;

    /* The distinct clause */
    private bool $distinct = false;

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
        $this->clause['where'] = "{$terms}";

        return $this;
    }

    /**
     * Limit the number
     * of rows to fetch
     *
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): Operations
    {
        $this->clause['limit'] = " LIMIT {$limit}";

        return $this;
    }


    /**
     * Set ordering
     * of results
     *
     * @param string|null $column
     * @param string $order
     * @return $this
     */
    public function order( string $column = "PRIMARY_KEY", string $order = "DESC"): Operations
    {
        $this->clause['order'] = " ORDER BY `" . ($column == "PRIMARY_KEY" ? $this->primary : $column) . "` $order";

        return $this;
    }

    public function group_by(string $column = "PRIMARY_KEY") :Operations
    {
        $this->clause['group_by'] = " GROUP BY `". ($column == "PRIMARY_KEY" ? $this->primary : $column) . "`";
        return $this;
    }

    /**
     * Select only distinct
     * values on the table
     *
     * @param string $column
     * @return Operations
     */
    public function distinct(string $column): Operations
    {
        $this->clause['distinct'] = "DISTINCT " . $column;
        $this->distinct = true;

        return $this;
    }

    /**
     * Sets the like clause
     * on the query string
     *
     * @param string $column
     * @param string $term
     * @param string $position
     * @return Operations
     */
    public function like(string $column, string $term, string $position = 'any'): Operations
    {
        switch ($position) {
            case 'any':
                $term = "%{$term}%";
                break;

            case 'start':
                $term = "{$term}%";
                break;

            case 'end':
                $term = "%{$term}";
                break;

            default:
                break;
        }

        $this->clause['like'] = "{$column} LIKE '{$term}'";

        return $this;
    }

    /**
     * Set the in operator
     * on query string
     *
     * @param string $column
     * @param array $values
     * @return Operations
     */
    public function in(array $values, string $column = "PRIMARY_KEY"): Operations
    {
        if ($column == "PRIMARY_KEY") $column = $this->primary;

        $values = implode("','", $values);
        $this->clause['in'] = "`{$column}` IN('{$values}')";

        return $this;
    }

    /**
     *
     * Execute the query
     * on the database
     * @param int|null $fetch_mode
     * @param bool $fetch_all
     * @return array|bool|mixed|string
     */
    public function execute(int $fetch_mode = null, bool $fetch_all = false)
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
                    if ($fetch_all) {
                        return $stmt->fetchAll($fetch_mode);
                    }
                    return $stmt->rowCount() > 1 ? $stmt->fetchAll($fetch_mode) : $stmt->fetch($fetch_mode);
                    break;


                case 'insert':
                    return $stmt->rowCount() >= 1 ? $conn->lastInsertId() : false;
                    break;

                default:
                    return true;
            }
        }

        $stmt->closeCursor();
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
     * Build the query
     *
     * @return string
     */
    private function buildQuery(): string
    {
        $query = $this->query;

        switch ($this->detectOperation()) {

            case 'select':

                $query .= " WHERE 1";
                $query = explode(" ", $query);

                $keyOfDefault = array_search("1", $query);

                if (!$this->distinct) {

                    if (!empty($this->clause['where']))
                        $query[] = $this->clause['where'];

                    if (!empty($this->clause['in']))
                        $query[] = $this->clause['in'];

                    if (!empty($this->clause['group_by']))
                        $query[] = $this->clause['group_by'];

                    if (!empty($this->clause['order']))
                        $query[] = $this->clause['order'];

                    if (!empty($this->clause['limit']))
                        $query[] = $this->clause['limit'];

                } else {

                    $query[1] = $this->clause['distinct'];
                }

                if (array_key_exists($keyOfDefault + 1, $query)) {

                    if (strpos($query[$keyOfDefault + 1], "LIMIT") || strpos($query[$keyOfDefault + 1], "ORDER") || strpos($query[$keyOfDefault + 1], "GROUP BY")) {
                        unset($query[$keyOfDefault - 1]);
                    }

                    unset($query[$keyOfDefault]);
                }

                $query = implode(" ", $query);

                break;

            case 'update':
            case 'delete':
                $query .= " WHERE 1";
                $query = explode(" ", $query);

                $keyOfDefault = array_search("1", $query);

                if (!empty($this->clause['where']))
                    $query[] = $this->clause['where'];

                if (!empty($this->clause['in']))
                    $query[] = $this->clause['in'];

                if (!empty($this->clause['limit']))
                    $query[] = $this->clause['limit'];

                if (array_key_exists($keyOfDefault + 1, $query)) {
                    unset($query[$keyOfDefault]);
                }
                $query = implode(" ", $query);

                break;

            default:
                break;
        }


        return $query;
    }
}