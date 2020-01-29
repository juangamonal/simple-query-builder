```php
# consulta la columna 'column_name' de la tabla 'table'
$builder = Builder::table('table')
    ->select('column_name');

# selecciona múltiples columnas (name, email y created_at) de la tabla 'users'
$builder = Builder::table('users')
    ->select('name', 'email', 'created_at');

# añade un alias a la columna name
$builder = Builder::table('users')
    ->select('name as nombre');
```

Llamar por segunda vez al método `select()` **sobreescribirá** el listado de columnas. Para añadir más columnas a la consulta utiliza el m´rtodo `addSelect()`:

```php
# solo consultará la columna 'three'
$builder = Builder::table('table')
    ->select('one', 'two')
    ->select('three');

# contendrá las columnas 'one', 'two' y 'three'
$builder = Builder::table('table')
    ->select('one', 'two')
    ->addSelect('third');
```

Se puede hacer uso de la declaración SQL `DISTINCT` para evitar valores repetidos, a través del método `distinct()`:

```php
$builder = Builder::table('table')
    ->select('one', 'two')
    ->distinct();
```