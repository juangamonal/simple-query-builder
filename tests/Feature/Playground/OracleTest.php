<?php

namespace QueryBuilder\Tests\Feature\Playground;

use PDO;
use PHPUnit\Framework\TestCase;
use QueryBuilder\Builder;
use QueryBuilder\Grammars\GrammarHandler;
use QueryBuilder\Tests\DefaultOracleConnection;
use QueryBuilder\Types\Engine;

/**
 * Class OracleTest
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Tests\Feature\Playground
 */
class OracleTest extends TestCase
{
    /**
     * @var Builder Query Builder instance
     */
    private $builder;

    /**
     * @var PDO PDO connection
     */
    private $pdo;

    /**
     * OracleTest constructor.
     *
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->pdo = new DefaultOracleConnection();
        $this->builder = new Builder(
            $this->pdo,
            GrammarHandler::create(Engine::ORACLE)
        );

        parent::__construct($name, $data, $dataName);
    }

    /**
     * Prueba
     *
     * @return void
     */
    public function test()
    {
        // $this->resetDatabase();
        var_dump($this->builder);

        $this->assertEquals(true, !false);
    }
}
