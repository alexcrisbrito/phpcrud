<?php


namespace Alexcrisbrito\Php_crud\examples;


use Alexcrisbrito\Php_crud\Crud;

class Users extends Crud
{
    public function __construct()
    {
        /* 1 - Table name, 2 - Required fields = [], 3 - Primary key = "id"  */
        parent::__construct("invoice");
    }
}