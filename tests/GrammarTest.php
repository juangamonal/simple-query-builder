<?php

namespace QueryBuilder\Tests;

use PHPUnit\Framework\TestCase;
use QueryBuilder\Builder;

/**
 * Class GrammarTest
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Tests
 */
final class GrammarTest extends TestCase
{
    /**
     * Prueba el método ->insert()
     *
     * @return void
     */
    public function testInsert()
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
