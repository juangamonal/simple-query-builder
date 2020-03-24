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
     * TODO: en algún punto debe lanzar UndefinedConnectionException
     */
    public function __construct()
    {
        $driver = getenv('SQB_DEFAULT_DRIVER');
        $host = getenv('SQB_DEFAULT_HOST');
        $db = getenv('SQB_DEFAULT_DATABASE');
        $user = getenv('SQB_DEFAULT_USER');
        $pass = getenv('SQB_DEFAULT_PASSWORD');

        try {
            parent::__construct("$driver:dbname=$db;host=$host", $user, $pass);
            parent::setAttribute(self::ATTR_ERRMODE, self::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // TODO
            throw $e;
        }
    }
}
