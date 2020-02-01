<?php

namespace QueryBuilder\Handlers\Tests;

use PHPUnit\Framework\TestCase;
use QueryBuilder\Builder;

/**
 * Class InsertHandlerTest
 *
 * @author Juan Gamonal H <jgamonal@ucsc.cl>
 * @package QueryBuilder\Handlers\Tests
 */
class InsertHandlerTest extends TestCase
{
    /**
     * Prueba el método ->getInsertSql()
     *
     * @return void
     */
    public function testGetInsertSql()
    {
        // insert básico
        $builder = Builder::table('users')
            ->insert([
                'name' => 'Juan Gamonal H',
                'age' => 26
            ]);
        $sql = "INSERT INTO users (name, age) VALUES ('Juan Gamonal H', 26)";
        $this->assertEquals($sql, $builder->toSql());

        // TODO: probar con demás tipos de datos
    }
}
