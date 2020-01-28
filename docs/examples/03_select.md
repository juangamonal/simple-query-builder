```php
# consulta la columna 'column_name' de la tabla 'table'
$builder = Builder::create('table')
    ->select('column_name');

# selecciona m�ltiples columnas (name, email y created_at) de la tabla 'users'
$builder = Builder::create('users')
    ->select('name', 'email', 'created_at');

# a�ade un alias a la columna name
$builder = Builder::create('users')
    ->select('name as nombre');
```

Llamar por segunda vez al m�todo `select()` **sobreescribir�** el listado de columnas. Para a�adir m�s columnas a la consulta utiliza el m�todo `addSelect()`:

```php
# solo consultar� la columna 'three'
$builder = Builder::create('table')
    ->select('one', 'two')
    ->select('three');

# contendr� las columnas 'one', 'two' y 'three'
$builder = Builder::create('table')
    ->select('one', 'two')
    ->addSelect('third');
```