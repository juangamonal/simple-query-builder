<?php

namespace QueryBuilder\Tests;

use Exception;
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
     * @throws Exception
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

        // select con where
        /* TODO: falla por que el alias no se maneja bien, falta un selectHandler
        $builder  = Builder::table('users')
            ->select('name as full_name')
            ->where('status = 1')
            ->orWhere('removed = 0');
        $sql = 'SELECT name AS full_name FROM users WHERE status = 1 OR removed = 0';
        $this->assertEquals($sql, $builder->toSql());
        */

        // TODO: select distinct con where

        // TODO: select con groupBy
        // TODO: select con having
        // TODO: select con orderBy
        // TODO: query compleja que tenga todo lo anterior
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

        // count con alias
        $builder->count('one as total_one', 'two as total_two');
        $sql = 'SELECT COUNT(one) AS total_one, COUNT(two) AS total_two FROM tablename';
        $this->assertEquals($sql, $builder->toSql());

        // count con addCount()
        $builder = Builder::table('tablename')
            ->count('one')
            ->addCount('two as total_two');
        $sql = 'SELECT COUNT(one), COUNT(two) AS total_two FROM tablename';
        $this->assertEquals($sql, $builder->toSql());

        // count con distinct
        $builder->count('one as total_one', 'two as total_two')
            ->distinct();
        $sql = 'SELECT DISTINCT COUNT(one) AS total_one, COUNT(two) AS total_two FROM tablename';
        $this->assertEquals($sql, $builder->toSql());

        // TODO: count con where
        // TODO: count con groupBy
        // TODO: count con having
        // TODO: count con orderBy
        // TODO: query compleja que tenga todo lo anterior
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
