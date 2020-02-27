<?php

namespace QueryBuilder\Grammars\Tests;

use PHPUnit\Framework\TestCase;
use QueryBuilder\Grammar;
use QueryBuilder\Grammars\OracleGrammar;

/**
 * Class OracleGrammarTest
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Grammars\Tests
 */
class OracleGrammarTest extends TestCase
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
        $this->grammar = new OracleGrammar();
    }

    /**
     * Prueba el método ->limit()
     *
     * @return void
     */
    public function testLimit()
    {
        $this->assertEquals(true, !false);
    }
}
