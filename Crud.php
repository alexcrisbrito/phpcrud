<?php

namespace alexcrisbrito\php_crud;

use Exception;

abstract class Crud{

    /* The database table to operate */
    protected string $entity;

    /* The primary key of the table */
    protected string $primary;

    /* The required fields on table */
    protected array $required;

    public function __construct(string $entity, array $required = [] ,string $primary = "id")
    {
        $this->entity = $entity;
        $this->primary = $primary;
        $this->required = $required;
    }


    /**
     * Insert records into table
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function save(array $data)
    {
        //Check if all required values are given
        if(count($this->required) >= 1) {
            for ($i = 0; $i < count($this->required); $i++) {
                if (!in_array($this->required[$i], array_keys($data)) or !isset($data[$this->required[$i]])) {
                    throw new Exception("(!) Missing value for required field '{$this->required[$i]}'");
                }
            }
        }

        $conn = Connection::connect();
        $query = "INSERT INTO ".$this->entity." (`".implode("`,`",array_keys($data))."`) VALUES ('".implode("','",$data)."')";
        $stmt = $conn->prepare($query);

        if($stmt->execute()){
            return $stmt->rowCount() >= 1;
        }

        return false;
    }


    /**
     * Fetch records from table
     * @param string $columns
     * @param string $terms
     * @return array|bool|mixed
     */
    public function find(string $columns = "*", string $terms = "1")
    {
        $conn = Connection::connect();
        $query = "SELECT ".$columns." FROM ".$this->entity." WHERE ". $terms ;
        $stmt = $conn->prepare($query);

        if($stmt->execute()){
            return $stmt->rowCount() ? ($stmt->rowCount() == 1 ? $stmt->fetch() : $stmt->fetchAll()) : false;
        }

        return false;
    }

    /**
     * Fetch records from table using primary key
     * @param string $columns
     * @param $id
     * @return array|bool|mixed
     */
    public function findById(string $columns, $id)
    {
        $conn = Connection::connect();
        $query = "SELECT ".$columns." FROM ".$this->entity." WHERE ".$this->primary." = '{$id}'";
        $stmt = $conn->prepare($query);

        if($stmt->execute()){
            return $stmt->rowCount() ? ($stmt->rowCount() == 1 ? $stmt->fetch() : $stmt->fetchAll()) : false;
        }

        return false;
    }

    /**
     * Updates records on table,
     * optionally you can use id parameter to
     * update using entity's primary key
     * @param array $terms
     * @param null $id
     * @return bool
     */
    public function update(array $terms , $id = null)
    {
        foreach ($terms as $key => $value){
            $terms[$key] = "`{$key}` = '{$value}'";
        }

        $conn = Connection::connect();
        $query = "UPDATE ".$this->entity." SET ".implode(",",$terms). " WHERE ". ($id ? $this->primary." = '{$id}'" : "1");
        $stmt = $conn->prepare($query);

        if($stmt->execute()){

            return $stmt->rowCount() >= 1;
        }

        return false;
    }

    /**
     * Deletes records on table
     * optionally you can use id parameter to
     * delete using entity's primary key
     * @param null $id
     * @return bool
     */
    public function delete($id = null)
    {
        $conn = Connection::connect();
        $query = "DELETE FROM ".$this->entity." WHERE ". ($id ? $this->primary." = ".$id : "1");
        $stmt = $conn->prepare($query);

        if($stmt->execute()){

            return $stmt->rowCount() >= 1;
        }

        return false;
    }
}