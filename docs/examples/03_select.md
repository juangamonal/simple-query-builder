```php
# consulta la columna 'column_name' de la tabla 'table'
$builder = Builder::create('table')
    ->select('column_name');

# selecciona múltiples columnas (name, email y created_at) de la tabla 'users'
$builder = Builder::create('users')
    ->select('name', 'email', 'created_at');

# añade un alias a la columna name
$builder = Builder::create('users')
    ->select('name as nombre');
```

Llamar por segunda vez al método `select()` **sobreescribirá** el listado de columnas. Para añadir más columnas a la consulta utiliza el método `addSelect()`:

```php
# solo consultará la columna 'three'
$builder = Builder::create('table')
    ->select('one', 'two')
    ->select('three');

# contendrá las columnas 'one', 'two' y 'three'
$builder = Builder::create('table')
    ->select('one', 'two')
    ->addSelect('third');
```