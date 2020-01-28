<?php

namespace QueryBuilder\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use QueryBuilder\Builder;

/**
 * Class BuilderTest
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Tests
 */
final class BuilderTest extends TestCase
{
    /**
     * Prueba que se cree correctamente un Query Builder
     *
     * @return void
     */
    public function testCreate()
    {
        // a través de constructor
        new Builder('table');

        // a través de create
        Builder::table('table');

        // nombre inválido
        $this->expectException(InvalidArgumentException::class);
        Builder::table('invalid.table');
    }

    /**
     * Prueba el método ->execute()
     *
     * @return void
     */
    // public function testExecute(){}

    /**
     * Prueba el método ->toSql()
     *
     * @return void
     */
    // public function testToSql(){}

    /**
     * Prueba el método ->select()
     *
     * @return void
     */
    public function testSelect()
    {
        // prueba con la columna por defecto '*'
        $builder = Builder::table('table');
        $builder->select();
        $this->assertCount(1, $builder->getColumns());

        // todavía debería haber uno
        $builder->select('column');
        $this->assertCount(1, $builder->getColumns());

        // añade múltiples select
        $builder->select('column_one', 'column_two', 'column_three');
        $this->assertCount(3, $builder->getColumns());

        // error: nombre inválido
        $this->expectException(InvalidArgumentException::class);
        $builder->select('invalid name');
    }

    /**
     * Prueba el método ->addSelect()
     *
     * @return void
     */
    public function testAddSelect()
    {
        $builder = Builder::table('table');
        $builder->select('one', 'two');

        $this->assertCount(2, $builder->getColumns());

        $builder->addSelect('three', 'four');

        $this->assertCount(4, $builder->getColumns());
    }

    // TODO: hacer
    // public function addDistinct()

    /**
     * Prueba el método ->insert()
     *
     * @return void
     */
    public function testInsert()
    {
        // insert básico
        $builder = Builder::table('tablename')
            ->insert(
                ['column_one' => 'value_one']
            );

        $this->assertCount(1, $builder->getInserts());

        // intenta añadir nuevos inserts a la consulta
        $builder->insert(['column_two' => 'value_two']);
        $builder->insert(['column_three' => 'value_three']);
        $this->assertCount(3, $builder->getInserts());
    }

    /**
     * Prueba el método ->columns()
     *
     * @return void
     */
    public function testColumns()
    {
        // añade nuevas columnas
        $builder = Builder::table('tablename')
            ->columns('name', 'email', 'password');

        $this->assertCount(3, $builder->getColumns());

        // añade aún más columnas
        $builder->columns('status', 'created_at');
        $this->assertCount(5, $builder->getColumns());

        // si se pasa por parámetro una columna ya añadida, ésta se omite, por ende solo debería haber una columna nueva
        $builder->columns('status', 'updated_at');
        $this->assertCount(6, $builder->getColumns());
    }

    // public function testValues(){}

    // TODO: pruebas arriba de esto
    // TODO: abajo los privados

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
}
