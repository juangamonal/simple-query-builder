# Simple Query Builder ![build](https://travis-ci.org/juangamonal/simple-query-builder.svg?branch=master)

Simple Query Builder (SQB) es una delgada capa sobre [PDO](https://www.php.net/manual/es/book.pdo.php) que provee mecanismos para simplificar la construcción y ejecución de consultas SQL. Se ejecuta a través de una API moderna inspirada en las mejores prácticas de otras librerías del mismo propósito.

## Características

TODO: utiliza tal y tal driver, tal version de php y extensiones a usar, etc

## Instalación

```sh
composer require juangamonal/sqb
```

**Nota**: para esta versión `alpha` de SQB vas a necesitar indicar en tu archivo `composer.json` la propiedad `minimum-stability` en `dev`.

## Uso
```php
use QueryBuilder\Builder;

$pdo = new PDO('...');
$builder = new Builder($pdo);
```

La instancia de `QueryBuilder` te permite encadenar métodos para realizar las consultas, por ejemplo:

```php
$builder->select('name as first_name', 'last_name', 'email')
    ->where('status = 1', 'age > 18')
    ->get();

# SELECT name AS first_name, last_name, email FROM users WHERE status = 1 AND age > 18
echo $builder->toSql();

// obteniendo resultados
$users = $builder->select('id')->from('users')->get();

// insertando datos
$builder->insert([
    'id' => 1,
    'name' => 'Foo'
])->into('users');

// modificando datos
$id = 1;
$builder->update([
    'name' => 'Foo',
    'email' => 'foo@bar.com'
])->where("id = {$id}")->execute();

// eliminado datos
$builder->delete()
    ->from('users')
    ->where("name like %Foo")
    ->execute();
    
// transacciones (callback)
$builder->transaction(function($b) {
    
    // operaciones...
    
    $b->insert(['id' => 1])->into('users');
    $b->setTable('posts')->delete()->where('user.id = 1')->execute();
    
    // más operaciones ...
    
});

// transacciones (manual)
try {
    $builder->beginTransaction();

    // operaciones...
    
    $builder->insert(['id' => 1])->into('users');
    $builder->setTable('posts')->delete()->where('user.id = 1')->execute();
    
    // más operaciones ...

    $builder->commit();
} catch (\Exception $e) {
    $builder->rollback();
}
```

## Guías

TODO: