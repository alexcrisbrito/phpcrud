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

//try {
//    $users->save(["name" => "Alexandre", "age" => 17])->execute();
//} catch (Exception $e) {
//    echo $e->getMessage();
//}


/**
 * Finding records
 *
 * Default fetch mode is OBJ,
 * but you can change it on
 * config or pass other when executing
 *
 * Method SIGNATURE
 * ->execute(fetch_mode: null, fetch_all: false)
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
$users->find()->order("id", "ASC")->execute();

//You can call the methods in the order you want
$result = $users->find("name, age")->order("age")->limit(20)
    ->execute(null, true);

if ($result) {
    foreach ($result as $user) {
        echo "Name: " . $user->name . " - " . $user->age . "<br>";
    }

}

die;

/**
 * Updating records
 *
 * @return bool
 */


$users->update(["name" => "Alexandre"])->execute();

$users->update(["name" => "2021"])->where("name = 'Alex'")->execute();

$users->update(["name" => "Alexa"])->where("name = '2021'")->limit(2)->execute();

/**
 * Deleting records
 *
 * @return bool
 */

$users->delete()->execute();

$users->delete()->where("name = 'Alexandre'")->execute();

$users->delete()->where("name = 'Alexandre'")->limit(5)->execute();