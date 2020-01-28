<?php

namespace QueryBuilder\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use QueryBuilder\Builder;

/**
 * Class BuilderSqlGeneratorTest
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Tests
 */
final class BuilderSqlGeneratorTest extends TestCase
{
    /**
     * Prueba el método ->getSelectSql()
     *
     * @return void
     */
    public function testGetSelectSql()
    {
        // select por defecto
        $builder = Builder::table('tablename');
        $sql = 'SELECT * FROM tablename';
        $this->assertEquals($sql, $builder->toSql());

        // select con un par de columnas
        $builder->select('one', 'two');
        $sql = 'SELECT one, two FROM tablename';
        $this->assertEquals($sql, $builder->toSql());

        // select con addSelect()
        $builder->select('one', 'two')
            ->addSelect('three');
        $sql = 'SELECT one, two, three FROM tablename';
        $this->assertEquals($sql, $builder->toSql());

        // select con distinct
        $builder->select('one', 'two')
            ->distinct();
        $sql = 'SELECT DISTINCT one, two FROM tablename';
        $this->assertEquals($sql, $builder->toSql());

        // TODO: select con where
        // TODO: select con groupBy
        // TODO: select con having
        // TODO: select con orderBy
    }

    /**
     * Prueba el método ->getCountSql()
     *
     * @return void
     */
    public function testGetCountSql()
    {
        // count por defecto
        $builder = Builder::table('tablename')
            ->count();
        $sql = 'SELECT COUNT(*) FROM tablename';
        $this->assertEquals($sql, $builder->toSql());

        // count con un par de columnas
        $builder->count('one', 'two');
        $sql = 'SELECT COUNT(one), COUNT(two) FROM tablename';
        $this->assertEquals($sql, $builder->toSql());

        // TODO: count con alias

        // count con addCount()
        /*
        $builder->select('one', 'two')
            ->addSelect('three');
        $sql = 'SELECT one, two, three FROM tablename';
        $this->assertEquals($sql, $builder->toSql());

        // count con distinct
        $builder->select('one', 'two')
            ->distinct();
        $sql = 'SELECT DISTINCT one, two FROM tablename';
        $this->assertEquals($sql, $builder->toSql());
        */

        // TODO: select con where
        // TODO: select con groupBy
        // TODO: select con having
        // TODO: select con orderBy
    }

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
