<?php

namespace QueryBuilder\Tests;

use PHPUnit\Framework\TestCase;
use QueryBuilder\Grammar;
use QueryBuilder\Grammars\MySqlGrammar;
use QueryBuilder\Types\Where;

/**
 * Class GrammarTest
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Tests
 */
final class GrammarTest extends TestCase
{
    /**
     * Instancia de grammar para pruebas
     * @var Grammar
     */
    private $grammar;

    /**
     * GrammarTest constructor.
     *
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->grammar = new MySqlGrammar();
    }

    /**
     * Prueba el método ->insert()
     *
     * @return void
     */
    public function testInsert()
    {
        // insert básico
        $sql = "INSERT INTO users (name, age) VALUES ('foo bar', 25)";
        $data = [
            'name' => 'foo bar',
            'age' => 25
        ];

        $this->assertEquals($sql, $this->grammar->insert('users', $data));

        // TODO: probar con demás tipos de datos
    }

    /**
     * Prueba el método ->where()
     *
     * @return void
     */
    public function testWhere()
    {
        // where básico
        $data = ['status = 1' => Where::AND];
        $where = 'WHERE status = 1';
        $this->assertEquals($where, $this->grammar->where($data));

        // where con múltiples 'AND'
        $data = ['status = 1' => Where::AND, 'age < 18' => Where::AND, 'age > 0' => Where::AND];
        $where = 'WHERE status = 1 AND age < 18 AND age > 0';
        $this->assertEquals($where, $this->grammar->where($data));

        // where y 'OR' where
        $data = ['status = 1' => Where::AND, 'deleted = 0' => Where::OR];
        $where = 'WHERE status = 1 OR deleted = 0';
        $this->assertEquals($where, $this->grammar->where($data));
    }
}
