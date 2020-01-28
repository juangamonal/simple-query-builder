```php
<?php

use QueryBuilder\Builder;

// builder totalmente vac�o
$builder = new Builder();

// builder con una tabla por defecto
$builder = new Builder('nombretabla');

// alternativa...
$builder = Builder::create('nombretabla');
```

La �ltima opci�n es recomendada cuando se desea encadenar m�todos, por ejemplo:

```php
<?php

use QueryBuilder\Builder;

// permite encadenar m�todos
$builder = Builder::create('nombretabla')
    ->select('columna')
    ->where(...);
```

Por defecto cuando no se especifica una conexi�n, Query Builder utilizar� la conexi�n <b>*default*</b> del proyecto. Una conexi�n v�lida debe implementar la interfaz `QueryBuilder\ConnectionInterface`:

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