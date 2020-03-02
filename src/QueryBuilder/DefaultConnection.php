<?php

namespace QueryBuilder;

use PDO;

/**
 * Class DefaultConnection
 *
 * @package QueryBuilder
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 */
class DefaultConnection extends PDO
{
    /**
     * DefaultConnection constructor.
     */
    public function __construct()
    {
        $engine = getenv('QB_DEFAULT_ENGINE');
        $host = getenv('QB_DEFAULT_HOST');
        $db = getenv('QB_DEFAULT_DATABASE');
        $user = getenv('QB_DEFAULT_USER');
        $pass = getenv('QB_DEFAULT_PASSWORD');

        parent::__construct("$engine:dbname=$db;host=$host", $user, $pass);
    }
}
