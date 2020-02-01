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

        // select con alias
        $builder->select('one as total_one', 'two as total_two');
        $sql = 'SELECT one AS total_one, two AS total_two FROM tablename';
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

        // select con where y orWhere
        $builder = Builder::table('users')
            ->select('name as full_name')
            ->where('status = 1')
            ->orWhere('removed = 0');
        $sql = 'SELECT name AS full_name FROM users WHERE status = 1 OR removed = 0';
        $this->assertEquals($sql, $builder->toSql());

        // select distinct con where
        $builder = Builder::table('users')
            ->select('email', 'status')
            ->where('age < 18', 'age > 0')
            ->distinct();
        $sql = 'SELECT DISTINCT email, status FROM users WHERE age < 18 AND age > 0';
        $this->assertEquals($sql, $builder->toSql());

        // select distinct con orWhere
        $builder = Builder::table('users')
            ->select('email', 'status')
            ->where('age < 18')
            ->orWhere('age > 0')
            ->distinct();
        $sql = 'SELECT DISTINCT email, status FROM users WHERE age < 18 OR age > 0';
        $this->assertEquals($sql, $builder->toSql());

        // TODO: select con groupBy
        // TODO: select con having
        // TODO: select con orderBy
        // TODO: query compleja que tenga todo lo anterior
    }

    /**
     * Prueba el método ->getCountSql()
     *
     * @throws Exception
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

        // count con where y orWhere
        $builder = Builder::table('users')
            ->count('name as full_name')
            ->where('status = 1')
            ->orWhere('removed = 0');
        $sql = 'SELECT COUNT(name) AS full_name FROM users WHERE status = 1 OR removed = 0';
        $this->assertEquals($sql, $builder->toSql());

        // count distinct con where
        $builder = Builder::table('users')
            ->count('email', 'status')
            ->where('age < 18', 'age > 0')
            ->distinct();
        $sql = 'SELECT DISTINCT COUNT(email), COUNT(status) FROM users WHERE age < 18 AND age > 0';
        $this->assertEquals($sql, $builder->toSql());

        // count distinct con orWhere
        $builder = Builder::table('users')
            ->count('email', 'status')
            ->where('age < 18')
            ->orWhere('age > 0')
            ->distinct();
        $sql = 'SELECT DISTINCT COUNT(email), COUNT(status) FROM users WHERE age < 18 OR age > 0';
        $this->assertEquals($sql, $builder->toSql());

        // TODO: count con groupBy
        // TODO: count con having
        // TODO: count con orderBy
        // TODO: query compleja que tenga todo lo anterior
    }

    /**
     * Prueba el método ->getUpdateSql()
     *
     * @throws Exception
     * @return void
     */
    public function testGetUpdateSql()
    {
        // update básico
        $builder = Builder::table('users')
            ->update([
                'age' => 20,
                'email' => 'juangamonalh@gmail.com'
            ]);
        $sql = "UPDATE users SET age = 20, email = 'juangamonalh@gmail.com'";
        $this->assertEquals($sql, $builder->toSql());

        // update con where y orWhere
        $builder->where('status = 1', "name = 'Juan Gamonal'")
            ->orWhere("name = 'Juan Gamonal H'");
        $sql .= " WHERE status = 1 AND name = 'Juan Gamonal' OR name = 'Juan Gamonal H'";
        $this->assertEquals($sql, $builder->toSql());
    }
}
