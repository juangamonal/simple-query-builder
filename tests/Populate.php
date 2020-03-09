<?php

namespace QueryBuilder\Tests;

use Faker\Factory;
use PDO;
use PDOException;

/**
 * Class Populate
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Tests
 */
class Populate
{
    /**
     * Crea la estructura de tablas para las pruebas
     *
     * @param PDO $conn Conexión a la base de datos
     *
     * @return void
     */
    public static function create(PDO $conn): void
    {
        $users = 'CREATE TABLE users (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(30) NOT NULL,
            last_name VARCHAR(30) NOT NULL,
            email VARCHAR(50) NOT NULL,
            status BOOLEAN NOT NULL DEFAULT TRUE ,
            created_at TIMESTAMP NOT NULL
        )';

        $posts = 'CREATE TABLE posts (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            content LONGTEXT,
            user_id INT(6) UNSIGNED NOT NULL,
            created_at TIMESTAMP NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )';

        try {
            // TODO: transaccion !!!
            $conn->exec($users);
            $conn->exec($posts);
        } catch (PDOException $e) {
            // TODO: catch!!!
        }
    }

    /**
     * Inserta datos para poder realizar algunas pruebas
     *
     * @param PDO $conn Conexión a la base de datos
     * @param int $qty Cantidad de datos
     *
     * @return void
     */
    public static function insert(PDO $conn, int $qty = 5): void
    {
        $faker = Factory::create();
        $date = date('Y-m-d H:i:s');

        try {
            // TODO: transaccion !!!
            foreach (range(1, $qty) as $user) {
                $sql = 'INSERT INTO users (
                    id, first_name, last_name, email, status, created_at
                ) VALUES (?, ?, ?, ?, ?, ?)';

                $conn->prepare($sql)
                    ->execute([
                        $user,
                        $faker->firstName(),
                        $faker->lastName(),
                        $faker->email(),
                        true,
                        $date
                    ]);
            }
        } catch (PDOException $e) {
            // TODO: catch!!!
            throw $e;
        }
    }

    /**
     * Reinicia base de datos de pruebas
     *
     * @param PDO $conn Conexión a la base de datos
     *
     * @return void
     */
    public static function reset(PDO $conn): void
    {
        $sql = $conn->prepare('DROP TABLE posts');

        try {
            $sql->execute();
        } catch (PDOException $e) {
            var_dump($e);
        }

        $sql = $conn->prepare('DROP TABLE users');

        try {
            $sql->execute();
        } catch (PDOException $e) {
            var_dump($e);
        }

        self::create($conn);
    }
}
