```php
# lo siguiente insertará una fila en la tabla 'users'
$result = Builder::table('users')
    ->insert([
        'name' => 'Juan Gamonal',
        'email' => 'juangamonalh@gmail.com'
    ])
    ->execute();

# puedes añadir tantos inserts como quieras, al ejecutarlos se insertarán todos
$builder = Builder::table('users')
    ->insert(['name' => 'Name 1', 'email' => 'juangamonalh@gmail.com'])
    ->insert(['name' => 'Name 2', 'age' => 50])
    ->insert(['name' => 'Name 3', 'status' => true])
    ->execute();
```
Puedes usar la combinación de los métodos `columns()` y `values()` para insertar una fila.
La diferencia entre el método mencionado y el método `insert()` es que este último traducirá la inserción en una consulta `INSERT` normal, en cambio, con `columns()` y `values()`, puedes hacer un **bind** de parámetros.

```php
$builder = Builder::table('users')
    ->columns('name', 'email')
    ->values('Juan Gamonal', 'juangamonalh@gmail.com')
    ->execute();
```

Considera previamente que el orden en el que definas las columnas van a ser mapeadas con sus respectivos valores.

```php
# preparando la inserción
$builder = Builder::table('users')
    ->columns('name', 'email');

// $builder->getColumns() => ['name', 'email']

$builder->columns('created_at')
    ->columns('status');

// $builder->getColumns() => ['name', 'email', 'created_at', 'status']

# asignar valores
$builder->values('Juan Gamonal', 'juangamonalh@gmail.com', '2020/01/01', true)
    ->execute();
```

No importa cuantas veces llames al método `columns()`, lo importante es saber que la cantidad de columnas escritas debe coincidir con la cantidad de valores. Lo anterior también aplica al método `values()`:

```php
# se intercalan llamadas a 'columns()' y 'values()'
$builder = Builder::table('users')
    ->columns('name', 'email')
    // hasta este punto se han añadido 4 columnas
    ->columns('status', 'created_at')
    // valores insuficiente, ya que se han añadido solo 3 parámetros
    ->values('Juan Gamonal', 'juangamonalh@gmail.com', true)
    // se añade el último parámetro coincidente con 'created_at', ahora si el 'INSERT' es válido
    ->values('2020/01/01');

# añadir otro valor causa un error, ya que no tiene columna a la cual asociarse
// $builder->values('esto causa error');

# siempre puedes añadir más columnas y valores según tu gusto
$builder->columns('updated_at', 'password')
    ->values('2020/01/01')
    ->values('password')
    ->execute();
```