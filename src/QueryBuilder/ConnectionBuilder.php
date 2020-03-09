<?php


namespace QueryBuilder;

use PDO;
use PDOException;

/**
 * Class ConnectionBuilder
 *
 * @package QueryBuilder
 * @author Juan Gamonal H <juangamonal@gmail.com>
 */
class ConnectionBuilder
{
    /**
     * Devuelve una instancia de PDO
     *
     * @param string $driver
     * @param string $host
     * @param string $db
     * @param string $user
     * @param string $pass
     *
     * @return PDO
     */
    public static function create(string $driver, string $host, string $db, string $user, string $pass): PDO
    {
        $pdo = null;

        try {
            $dsn = "$driver:dbname=$db;host=$host";
            $pdo = new PDO($dsn, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        } catch (PDOException $e) {
            // TODO
            throw $e;
        }
    }
}
