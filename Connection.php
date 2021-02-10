<?php

namespace Alexcrisbrito\Php_crud;

use PDO;
use PDOException;

class Connection
{

    private static PDO $connection;

    /**
     * Method to return the connection to database
     * You can use in case you want to create own method
     *
     * @return PDO|null
     */

    public static function connect(): ?PDO
    {
        try {
            self::$connection = new PDO(
                DB_CONFIG["driver"] . ":dbname=" . DB_CONFIG["dbname"] . ";host=" . DB_CONFIG["host"] . ";port=" . DB_CONFIG["port"],
                DB_CONFIG["username"],
                DB_CONFIG["passwd"],
                DB_CONFIG["options"]
            );

            return self::$connection;

        } catch (PDOException $exception) {
            die($exception->getMessage());
            return null;
        }
    }
}