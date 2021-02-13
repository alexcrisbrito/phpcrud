#PHP-CRUD

[![Maintainer](http://img.shields.io/badge/maintainer-@alexcrisbrito-red.svg?style=flat-square)](https://github.com/alexcrisbrito)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/alexcrisbrito/php-crud.svg?style=flat-square)](https://packagist.org/packages/alexcrisbrito/php-crud)
[![Latest Version](https://img.shields.io/github/release/alexcrisbrito/phpcrud.svg?style=flat-square)](https://github.com/alexcrisbrito/phpcrud/releases)

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

Refer to index file on the examples folder of package as reference and testing
###### Veja o ficheiro index na pasta exemplos para os exemplos abaixo de uso e teste

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
use Alexcrisbrito\Php_crud\examples\Users;

$users = new Users();

/*
 * Inserting records 
 * Inserir dados na tabela
 */

try{
    $id = $users->save(["Name"=>"Alex","Age"=>18])->execute();
}catch(Exception $e){
    die($e->getMessage());
}

if($id){
    echo "Inserted successfully";
}else{
    echo "Not Inserted";
}

```

#### Retrieving records
###### Selecionar registos
This method returns a set of results or false when empty

###### Este método retorna um array de resultados ou falso
```php
/**
 * Finding records
 *
 * Returns false or set
 * of results
 *
 */

//If no parameters in find method will fetch all columns
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
```

#### Updating records
###### Atualizar registos
This method returns true or false respectively, may return false when there's no need to update

###### Este método retorna falso ou verdadeiro respetivamente, pode retornar falso quando não há necessidade de atualização.
```php
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
```

#### Deleting records
###### Apagar registos
 
This method returns true or false respectively
```php
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

```

Thank you !

###### Obrigado!

## Contributing

You can contribute emailing me via abrito@nextgenit-mz.com or via pull request

## Credits

- [Alexandre Brito](https://github.com/alexcrisbrito) (Developer)

## License

The MIT License (MIT). Please see [License File](https://github.com/alexcrisbrito/php-crud/blob/master/LICENSE) for more information.