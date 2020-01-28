```php
# lo siguiente insertará una fila en la tabla 'users'
$result = Builder::table('users')
    ->insert([
        'name' => 'Juan Gamonal',
        'email' => 'juangamonalh@gmail.com'
    ])
    ->execute();

# puedes adjuntar tantos inserts como quieras
$result = Builder::table('users')
    ->insert(['name' => 'Name 1', 'email' => 'juangamonalh@gmail.com'])
    ->insert(['name' => 'Name 2', 'age' => 50])
    ->insert(['name' => 'Name 3', 'created_at' => '2020/01/01'])
    ->insert(['name' => 'Name 4', 'status' => true])
    ->execute();

# prueba también el método batchInsert()
# TODO: Builder::table('users')->batchInsert()
```