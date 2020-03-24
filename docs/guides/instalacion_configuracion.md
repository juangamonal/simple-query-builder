# Instalación y configuración

```php
<?php

use QueryBuilder\Builder;

// instancia de la clase 'Builder'
$builder = new Builder();
$builder->setTable('users');

// alternativa...
$builder = Builder::table('users');
```

La segunda opción es recomendada cuando se desea encadenar métodos, por ejemplo:

```php
<?php

use QueryBuilder\Builder;

$builder = Builder::table('users')
    ->select('first_name as name')
    ->where(...);
```