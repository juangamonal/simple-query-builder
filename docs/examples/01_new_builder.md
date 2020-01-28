```php
<?php

use QueryBuilder\Builder;

// builder totalmente vacío
$builder = new Builder();

// builder con una tabla por defecto
$builder = new Builder('nombretabla');

// alternativa...
$builder = Builder::create('nombretabla');
```

La última opción es recomendada cuando se desea encadenar métodos, por ejemplo:

```php
<?php

use QueryBuilder\Builder;

// permite encadenar métodos
$builder = Builder::create('nombretabla')
    ->select('columna')
    ->where(...);
```

Por defecto cuando no se especifica una conexión, Query Builder utilizará la conexión <b>*default*</b> del proyecto. Una conexión válida debe implementar la interfaz `QueryBuilder\ConnectionInterface`:

```php
<?php

use QueryBuilder\ConnectionInterface;

class MiConexion implements ConnectionInterface
{
    public function getConnectionString(): string
    {
        return 'tns:connection';
    }
}
```

```php
<?php

use MiConexion;
use QueryBuilder\Builder;

...

$conn = new MiConexion();
$builder = new Builder('nombretabla', $conn);
```