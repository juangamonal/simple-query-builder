<?php

namespace QueryBuilder;

use PDO;
use PDOException;

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
        $driver = getenv('QB_DEFAULT_DRIVER');
        $host = getenv('QB_DEFAULT_HOST');
        $db = getenv('QB_DEFAULT_DATABASE');
        $user = getenv('QB_DEFAULT_USER');
        $pass = getenv('QB_DEFAULT_PASSWORD');

        try {
            parent::__construct("$driver:dbname=$db;host=$host", $user, $pass);
            parent::setAttribute(self::ATTR_ERRMODE, self::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // TODO
            throw $e;
        }
    }
}
