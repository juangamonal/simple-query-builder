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