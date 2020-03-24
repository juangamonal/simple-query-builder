# Conexiones

Para poder ejecutar consultas, SQB requiere de una conexión PDO. Puedes crear una instancia de `QueryBuilder\Builder` sin conexión y te servirá para obtener el string de la consulta con el método `toSql()` ya que no exige una conexión para ello. Si intentas llamar al método `execute()` SQB utilizará la conexión <b>*default*</b>.
 
```php
<?php

use QueryBuilder\Builder;

$builder = Builder::table('users');

// no requiere conexión, imprime 'SELECT * FROM users'
echo $builder->toSql();

// si requiere conexión, ejecuta la consulta 'SELECT * FROM users'
$results = $builder->execute();
``` 
 
SQB acepta como conexión válida cualquier objeto PDO o herencia de éste último.

- Objeto PDO:

```php
<?php

use QueryBuilder\Builder;

$pdo = new PDO('driver:dbname=example;host=localhost', 'user', 'pass');
$builder = new Builder($pdo);
```

- Herencia PDO:

```php
<?php

use QueryBuilder\Builder;

class MiConexion extends PDO
{
    ...
}

$builder = new Builder(new MiConexion());
```

## La clase `QueryBuilder\DefaultConnection`

Como se menciona arriba, SQB utilizará una conexión por defecto, esta conexión es la clase `QueryBuilder\DefaultConnection`. Esta clase te permite crear una conexión PDO sencilla en base a variables de entorno.

El constructor de dicha clase se ve así:

```php
...
$driver = getenv('SQB_DEFAULT_DRIVER');
$host = getenv('SQB_DEFAULT_HOST');
$db = getenv('SQB_DEFAULT_DATABASE');
$user = getenv('SQB_DEFAULT_USER');
$pass = getenv('SQB_DEFAULT_PASSWORD');
...
```

Si tienes configurados estos parámetros SQB creará la conexión por ti, de lo contrario lanzará la excepción `UndefinedConnectionException`.

Hay varias formas de configurar estas variables de entorno en PHP. Un ejemplo puede ser la librería [dotenv](https://github.com/vlucas/phpdotenv) si lo haces a nivel de un proyecto.