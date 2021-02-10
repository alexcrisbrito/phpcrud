<?php

use Alexcrisbrito\Php_crud\examples\Users;


require 'config.php';
require '../vendor/autoload.php';


$users = new Users();

/**
 *
 * Inserting records
 *
 * Returns false or
 * insertion id
 *
 */

try {
    $users->save(["name" => "Alexandre", "age" => 18])->execute();
} catch (Exception $e) {
    echo $e->getMessage();
}


/**
 * Finding records
 *
 * Returns false or set
 * of results
 *
 */

//If no parameters in find will fetch all columns
$users->find("name,age")->execute();

//With where clause
$users->find()->where("name = 'Alexandre'")->execute();

//With limit
$users->find()->limit(2)->execute();

//With custom order, if no parameters with do by primary key in DESC order
$users->find()->order("id", "ASC")->execute();

//You can freely mix all
$result = $users->find("id,age,name")->where("name = 'Alexandre'")->order("id")->limit(5)->execute();

if($result) {
    if(is_array($result)) {
        foreach ($result as $user) {
            echo "Name: " . $user->name;
        }
    }else {
        echo "Name: " . $result->name;
    }
}

/**
 *
 * Updating records
 *
 * Returns false or true
 *
 */

//All records
$users->update(["name" => "Alexandre"])->execute();

//With conditions
$users->update(["name"=>"2021"])->where("name = 'Alex'")->execute();

//Limit update
$users->update(["name"=>"Alexa"])->where("name = '2021'")->limit(2)->execute();

/**
 *
 * Deleting records
 *
 * Returns false or true
 *
 */

//All records
$users->delete()->execute();

//With conditions
$users->delete()->where("name = 'Alexandre'")->execute();

//With limitation
$users->delete()->where("name = 'Alexandre'")->limit(5)->execute();