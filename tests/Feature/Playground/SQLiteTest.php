<?php

namespace QueryBuilder\Tests\Feature\Playground;

use PDO;
use PDOException;
use PHPUnit\Framework\TestCase;

/**
 * Class SQLiteTest
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Tests
 */
final class SQLiteTest extends TestCase
{
    private $pdo;
    /**
     * SQLiteTest constructor.
     *
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->pdo = new PDO('sqlite:db.sqlite');
    }

    /**
     * Prueba
     *
     * @return void
     */
    public function test()
    {
        // $this->resetDatabase();

        $this->assertEquals(true, !false);
    }

    // TODO
    private function resetDatabase()
    {

    }
}
