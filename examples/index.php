<?php

use Alexcrisbrito\Php_crud\examples\Users;


require 'config.php';
require '../vendor/autoload.php';


$users = new Users();

/**
 * Inserting records
 *
 * @return int|bool
 */

try {
    $users->save(["name" => "Alexandre", "age" => 17])->execute();
} catch (Exception $e) {
    echo $e->getMessage();
}


/**
 * Finding records
 *
 * Default fetch mode is OBJ,
 * but you can change it on
 * config
 *
 * Possible values for
 * position argument on
 * like method are any(default),
 * start, end
 *
 * @return bool|array|object
 */


//If no parameters in find will fetch all columns
$users->find("name,age")->execute();

//With where clause
$users->find()->where("name = 'Alexandre'")->execute();

//With limit
$users->find()->limit(2)->execute();

//With custom order, if no parameters will do by table's primary key in DESC order
$users->find()->order("ASC", "id")->execute();

//You can call the methods in the order you want
$result = $users->find("name, age")->limit(5)
    ->order("DESC", "age")->where("age > 12")->execute();

if($result) {
    if(is_array($result)) {
        foreach ($result as $user) {
            echo "Name: " . $user->name . " - ". $user->age ."<br>";
        }
    }else {
        echo "Name: " . $result->name;
    }
}

die;

/**
 * Updating records
 *
 * @return bool
 */


$users->update(["name" => "Alexandre"])->execute();

$users->update(["name"=>"2021"])->where("name = 'Alex'")->execute();

$users->update(["name"=>"Alexa"])->where("name = '2021'")->limit(2)->execute();

/**
 * Deleting records
 *
 * @return bool
 */

$users->delete()->execute();

$users->delete()->where("name = 'Alexandre'")->execute();

$users->delete()->where("name = 'Alexandre'")->limit(5)->execute();