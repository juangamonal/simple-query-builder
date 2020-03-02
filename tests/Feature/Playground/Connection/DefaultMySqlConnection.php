<?php

namespace QueryBuilder\Tests;

use PDO;

/**
 * Class DefaultMySqlConnection
 *
 * @package QueryBuilder\Tests
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 */
class DefaultMySqlConnection extends PDO
{
    /**
     * DefaultMySqlConnection constructor.
     */
    public function __construct()
    {
        // TODO: parametrizar
        $dsn = 'mysql:host=localhost;dbname=sqb_test';
        $user = 'pma';
        $pass = '123456';

        parent::__construct($dsn, $user, $pass);
    }
}
