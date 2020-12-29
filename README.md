#PHP-CRUD

[![Maintainer](http://img.shields.io/badge/maintainer-@alexcrisbrito-red.svg?style=flat-square)](https://github.com/alexcrisbrito)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/alexcrisbrito/php-crud.svg?style=flat-square)](https://packagist.org/packages/alexcrisbrito/php-crud)
[![Latest Version](https://img.shields.io/github/release/alexcrisbrito/php-crud.svg?style=flat-square)](https://github.com/alexcrisbrito/php-crud/releases)

The php-crud is an abstraction component for your database that uses PDO and prepared statements for performing operations such as saving, retrieving, updating and deleting data.

### Highlights

- Only 3 parameters set up
- All necessary CRUD operations
- Safe and reliable models abstracted models
- Composer ready 

##### 
- Apenas 3 parâmetros necessários
- Todas as operações básicas
- Modelos seguros e confiáveis
- Pronto para o composer

## Installation
###### Instalação

Via composer:

```bash
composer require alexcrisbrito/php-crud
```

## Documentation
###### Documentação

You can see index file on the root folder of package as reference and testing
###### Veja o ficheiro index na pasta raiz para exemplos de uso e teste

To start using PHPCrud we need a connection to a database. To see possible connections see [PDO Drivers on PHP.net](https://www.php.net/manual/pt_BR/pdo.drivers.php)

###### Para usar PHPCrud precisamos de uma conexão a base de dados, para ver as conexões possíveis visite [Drivers PDO no PHP.net](https://www.php.net/manual/pt_BR/pdo.drivers.php)

```php
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
```

#### Creating a model
###### Criar um modelo

This package was created based on an MVC structure with the Layer Super Type and Active Record design patterns. So to consume it is necessary to create the model for your table and inherit the Crud class.

###### Este pacote foi criado com base numa estrutura MVC com os padrões de projeto Layer Super Type e Active Record. Então para consumir é necessário criar o modelo para a sua tabela e herdar a classe Crud. 

```php
use alexcrisbrito\php_crud\Crud as Crud;

class Users extends Crud
{
    /**
     * Model constructor
     */
    public function __construct()
    {
        //string "TABLE NAME", array "REQUIRED FIELDS" = [],string "PRIMARY_KEY" = "id"
        parent::__construct("users",["Name","Age"],"Id");
    }
}
```

#### Inserting records
###### Inserir registos

This method returns true or false respectively and throws an exception when you don't provide a value for a required field

###### Este método retorna verdadeiro ou falso respetivamente e, lança uma exceção quando você nao fornece valor para um campo obrigatório
```php
<?php
use Users;

$users = new Users();

/*
 * Inserting records 
 * Inserir dados na tabela
 */

try{
    $id = $users->save(["Name"=>"Alex","Age"=>18]);
}catch(Exception $e){
    die($e->getMessage());
}

if($id){
    echo "Inserted successfully";
}else{
    echo "Not Inserted";
}

?>
```

#### Retrieving records
###### Selecionar registos
This method returns a set of results or false when empty

###### Este método retorna um array de resultados ou falso
```php
<?php
use Users;

$users = new Users();

/*
 * Retrieve all records
 * Selecionar todos registos
*/

$records = $users->find();
if($records){
    foreach ($records as $row){
        echo "Name: ".$row->Name;
    }
}

/*
 * Retrieve specific columns of all records
 * Selecionar algumas colunas de todos os registros
*/

$records = $users->find("Name,Age");
if($records){
    foreach ($records as $row){
        echo "Name: ".$row->Name;
        echo "Age: ".$row->Age;
    }
}

/*
 * Retrieve records with 'where' conditions
 * Selecionar registos impondo condições
*/

$records = $users->find("Name,Age","Name LIKE A% OR Age > 18");
if($records){
    foreach ($records as $row){
        echo "Name: ".$row->Name;
        echo "Age: ".$row->Age;
    }
}

/*
 * Retrieve by primary key
 * Selecionar através da chave primaria  
 */

$row = $users->findById("Name,Age",3);
if($row){
    echo "Name: ".$row->Name;
    echo "Age: ".$row->Age;
}

?>
```

#### Updating records
###### Atualizar registos
This method returns true or false respectively, may return false when there's no need to update

###### Este método retorna falso ou verdadeiro respetivamente, pode retornar falso quando não há necessidade de atualização.
```php
<?php
use Users;

$users = new Users();

/*
 * Updating all records of the table
 * This method works the same way as 
 * save method
 *
 * Atualizar os registos na tabela funciona 
 * é como inserir os registos
 */
$update = $users->update(["Name"=>"Alex","Age"=>18]);
if($update){
    echo "Updated successfully";
}else{
    echo "Error updating";
}

/*
 * Updating records using primary 
 * key of the table
 *
 *Atualizar através da chave primária
 */
$update = $users->update(["Name"=>"Alex","Age"=>18],3);
if($update){
    echo "Updated successfully";
}else{
    echo "Error updating";
}

?>
```

#### Deleting records
###### Apagar registos
 
This method returns true or false respectively
```php
<?php

use Users;

$users = new Users();

/*
* Delete all records of table
* 
* Apagar todos os registos da
* tabela
*/
$delete = $users->delete();
if($delete){
    echo "Deleted successfully";
}else{
    echo "Error deleting";
}

/*
* Delete records of table using 
* primary key
* 
* Apagar registos da tabela
* usando a chave primaria
*/

$delete = $users->delete(3);
if($delete){
    echo "Deleted successfully";
}else{
    echo "Error deleting";
}

?>
```

Thank you !

###### Obrigado!

## Contributing

You can contribute emailing me via abrito@nextgenit-mz.com or via pull request

## Credits

- [Alexandre Brito](https://github.com/alexcrisbrito) (Developer)

## License

The MIT License (MIT). Please see [License File](https://github.com/alexcrisbrito/php-crud/blob/master/LICENSE) for more information.