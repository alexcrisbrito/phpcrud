<?php

include "./vendor/autoload.php";

use alexcrisbrito\php_crud\Crud;

define("DB_CONFIG", [
    "driver" => "mysql",
    "host" => "localhost",
    "port" => "3306",
    "dbname" => "example",
    "username" => "root",
    "passwd" => "",
    "options" => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    ]
]);


class User extends Crud{
    public function __construct()
    {
        parent::__construct("users", ["nome","idade"]);
    }
}

$user_model = new User();

//Inserting record
try {
    $save = $user_model->save(["nome" => "Joao", "idade" => 18]);
} catch (Exception $e) {
    die($e->getMessage());
}

if($save){
    echo "Inserted";
}else{
    echo "Not inserted";
 }

//Finding records

$users = $user_model->find();

if($users) {
    foreach ($users as $user) {
        echo "Nome: " . $user->nome;
    }
}else{
    echo "Not found";
}

$users = $user_model->find("*","idade > 19");

if($users) {
    foreach ($users as $user) {
        echo "Nome: " . $user->nome;
    }
}else{
    echo "Not found";
}


//Updating records
$update = $user_model->update(["nome"=>"Alex"]);

if ($update){
    echo "YES";
}else{
    echo "NO";
}

$update = $user_model->update(["nome"=>"John"],1);

if ($update){
    echo "YES";
}else{
    echo "NO";
}

//Deleting records

$delete = $user_model->delete();

if($delete){
    echo "YES";
}else{
    echo "NO";
}

$delete = $user_model->delete(1);

if($delete){
    echo "YES";
}else{
    echo "NO";
}