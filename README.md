# Simple Query Builder

Simple Query Builder (SQB) es una delgada capa sobre [PDO](https://www.php.net/manual/es/book.pdo.php) que provee mecanismos para simplificar la construcción y ejecución de consultas SQL. Se ejecuta a través de una API moderna inspirada en las mejores prácticas de otras librerías del mismo propósito. Cuenta con un manejador sencillo de conexiones basado en variables de entorno.

## Características

TODO: utiliza tal y tal driver, tal version de php y extensiones a usar, etc

## Instalación

TODO: composer install

## Uso
```php
use QueryBuilder\Builder;

$builder = Builder::table('users');
```

La instancia de `QueryBuilder` te permite encadenar métodos para realizar las consultas, por ejemplo:

```php
$builder = Builder::table('users')
    ->select('name as first_name', 'last_name', 'email')
    ->where('status = 1', 'age > 18');

# SELECT name AS first_name, last_name, email FROM users WHERE status = 1 AND age > 18
echo $builder->toSql();
```

## Guías

TODO: