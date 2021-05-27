<?php

namespace Alexcrisbrito\Php_crud;

use Exception;

abstract class Crud
{
    /* The database table to operate */
    protected string $entity;

    /* The SQL query */
    private string $query;

    /* The primary key of the table */
    protected string $primary;

    /* The required fields on table */
    protected array $required;

    public function __construct(string $entity, array $required = [], string $primary = "id")
    {
        $this->entity = $entity;
        $this->primary = $primary;
        $this->required = $required;
    }

    /**
     * Insert records into table
     * @param array $data
     * @return Operations
     * @throws Exception
     */
    public function save(array $data): Operations
    {
        //Check if all required values are given
        if (count($this->required) >= 1) {
            for ($i = 0; $i < count($this->required); $i++) {
                if (!in_array($this->required[$i], array_keys($data)) or !isset($data[$this->required[$i]])) {
                    throw new Exception("(!) Missing value for required field '{$this->required[$i]}'");
                }
            }
        }

        $this->query = "INSERT INTO `" . $this->entity . "` (`" . implode("`,`", array_keys($data)) . "`) VALUES ('" . implode("','", $data) . "')";

        return new Operations($this->query, $this->primary);
    }

    /**
     *
     * Fetch records from table
     * @param string $columns
     *
     * @return Operations
     */
    public function find(string $columns = "*"): Operations
    {
        $this->query = "SELECT " . preg_replace("/\s+/", "", $columns) . " FROM `{$this->entity}`";

        return new Operations($this->query, $this->primary);
    }

    /**
     * Updates records on table,
     * optionally you can use id parameter to
     * update using entity's primary key
     * @param array $data
     * @return Operations
     */
    public function update(array $data): Operations
    {
        foreach ($data as $key => $value) {
            $data[$key] = "{$key} = '{$value}'";
        }

        $this->query = "UPDATE `" . $this->entity . "` SET " . implode(",", $data);

        return new Operations($this->query, $this->primary);
    }

    /**
     * Deletes records on table
     *
     * @return Operations
     */
    public function delete(): Operations
    {
        $this->query = "DELETE FROM `$this->entity`";

        return new Operations($this->query, $this->primary);
    }
}