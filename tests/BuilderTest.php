<?php

namespace QueryBuilder\Tests;

use Exception;
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
        $this->assertCount(1, $builder->getSelects());

        // todavía debería haber uno
        $builder->select('column');
        $this->assertCount(1, $builder->getSelects());

        // añade múltiples select
        $builder->select('column_one', 'column_two', 'column_three');
        $this->assertCount(3, $builder->getSelects());

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

        $this->assertCount(2, $builder->getSelects());

        $builder->addSelect('three', 'four');

        $this->assertCount(4, $builder->getSelects());
    }

    // TODO: hacer
    // public function addDistinct()

    /**
     * Prueba el método ->count()
     *
     * @return void
     */
    public function testCount()
    {
        // prueba con la columna por defecto '*'
        $builder = Builder::table('table');
        $builder->count();
        $this->assertCount(1, $builder->getCounts());

        // todavía debería haber uno
        $builder->count('column');
        $this->assertCount(1, $builder->getCounts());

        // añade múltiples count
        $builder->count('column_one', 'column_two', 'column_three');
        $this->assertCount(3, $builder->getCounts());

        // error: nombre inválido
        $this->expectException(InvalidArgumentException::class);
        $builder->count('invalid name');
    }

    /**
     * Prueba el método ->addCount()
     *
     * @return void
     */
    public function testAddCount()
    {
        $builder = Builder::table('table');
        $builder->count('one', 'two');

        $this->assertCount(2, $builder->getCounts());

        $builder->addCount('three', 'four');

        $this->assertCount(4, $builder->getCounts());
    }

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

        $this->assertCount(1, $builder->getInsert());

        // intenta añadir nuevos inserts a la consulta, queda en uno ya que cada llamada sobreescribe a la anterior
        $builder->insert(['column_two' => 'value_two']);
        $builder->insert(['column_three' => 'value_three']);
        $this->assertCount(1, $builder->getInsert());
    }

    /**
     * Prueba el método ->where()
     *
     * @return void
     */
    public function testWhere()
    {
        // where básico
        $builder = Builder::table('users')
            ->where(
                'status = 1',
                'age > 18'
            );

        $this->assertCount(2, $builder->getWheres());

        // sobreescribe el where anterior
        $builder->where('status = 0');
        $this->assertCount(1, $builder->getWheres());
    }

    /**
     * Prueba el método ->addWhere()
     *
     * @return void
     */
    public function testAddWhere()
    {
        $builder = Builder::table('users');
        $builder->where(
            'status = 1'
        );

        $this->assertCount(1, $builder->getWheres());

        $builder->addWhere(
            'age > 18',
            "name = 'Juan Gamonal H'"
        );

        $this->assertCount(3, $builder->getWheres());
    }

    /**
     * Prueba el método ->orWhere()
     *
     * @throws Exception
     * @return void
     */
    public function testOrWhere()
    {
        // error, sin wheres
        $this->expectException(Exception::class);
        $builder = Builder::table('users')
            ->orWhere('error');

        // orWhere básico
        $builder->where('status = 1')
            ->orWhere('age > 0');
        $this->assertCount(2, $builder->getWheres());

        // sobreescribe el/los orWhere anterior/es
        $builder->orWhere('status = 0');
        $this->assertCount(2, $builder->getWheres());
    }

    /**
     * Prueba el método ->addOrWhere()
     *
     * @throws Exception
     * @return void
     */
    public function testAddOrWhere()
    {
        // error, sin wheres
        $this->expectException(Exception::class);
        $builder = Builder::table('users')
            ->addOrWhere('error');

        // addOrWhere básico
        $builder->where('status = 1')
            ->addOrWhere('age > 0');
        $this->assertCount(2, $builder->getWheres());

        $builder->addOrWhere('age < 18');
        $this->assertCount(3, $builder->getWheres());
    }

    /**
     * Prueba el método ->validateExistingWhere()
     *
     * @throws Exception
     * @return void
     */
    public function testValidateExistingWhere()
    {
        // prueba con orWhere
        $this->expectException(Exception::class);
        $builder = Builder::table('users')
            ->orWhere('error');

        // prueba con addOrWhere
        $this->expectException(Exception::class);
        $builder->addOrWhere('another error');
    }

    /**
     * Prueba el método ->prepareWhere()
     *
     * @return void
    public function testPrepareWhere()
    {
        // un solo where
        $builder = Builder::table('users')
            ->where('age > 18');

        // múltiples where
        // utilizando addWhere
        // or where
        // utilizando addOrWhere
        // mezclado where y addWhere
        // situación compleja
    }*/
}
