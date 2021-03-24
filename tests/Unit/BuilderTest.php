<?php

namespace QueryBuilder\Tests\Unit;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use QueryBuilder\Builder;
use Tests\Utils;

/**
 * Class BuilderTest
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Tests\Unit
 */
final class BuilderTest extends TestCase
{
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
        // añade un select vacío
        $builder = $this->getInstance()->select();
        $this->assertCount(1, $builder->getSelects());

        $builder = $builder->setTable('users')
            ->select('column');
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
        $builder = $this->getInstance();
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
        // añade un count vacío
        $builder = $this->getInstance()->count();
        $this->assertCount(1, $builder->getCounts());

        // sobreescribe las declaraciones anteriores
        $builder = $this->getInstance()->count('column');
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
        $builder = $this->getInstance();
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
        $builder = $this->getInstance()->insert(
            ['column_one' => 'value_one']
        );

        $this->assertCount(1, $builder->getInsert());

        // intenta añadir nuevos inserts a la consulta, queda en uno ya que cada llamada sobreescribe a la anterior
        $builder->insert(['column_two' => 'value_two']);
        $builder->insert(['column_three' => 'value_three']);
        $this->assertCount(1, $builder->getInsert());
    }

    /**
     * Prueba el método ->update()
     *
     * @return void
     */
    public function testUpdate()
    {
        // update básico
        $builder = $this->getInstance()->update(
            ['column_one' => 'value_one']
        );

        $this->assertCount(1, $builder->getUpdate());

        // intenta añadir nuevos update a la consulta, queda en uno ya que cada llamada sobreescribe a la anterior
        $builder->update(['column_two' => 'value_two']);
        $builder->update(['column_three' => 'value_three']);
        $this->assertCount(1, $builder->getUpdate());
    }

    /**
     * Prueba el método ->where()
     *
     * @return void
     */
    public function testWhere()
    {
        // where básico
        $builder = $this->getInstance()->where('status = 1', 'age > 18');

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
        $builder = $this->getInstance();
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
        $builder = $this->getInstance()->orWhere('error');

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
        $builder = $this->getInstance()->addOrWhere('error');

        // addOrWhere básico
        $builder->where('status = 1')
            ->addOrWhere('age > 0');
        $this->assertCount(2, $builder->getWheres());

        $builder->addOrWhere('age < 18');
        $this->assertCount(3, $builder->getWheres());
    }

    /**
     * Prueba el método ->orderBy()
     *
     * @return void
     */
    public function testOrderBy()
    {
        // order by básico
        $builder = $this->getInstance()
            ->where('age > 18')
            ->orderBy('age asc', 'status desc');

        $this->assertCount(2, $builder->getOrderBy());

        // sobreescribe el order by anterior
        $builder->orderBy('status asc');
        $this->assertCount(1, $builder->getOrderBy());
    }

    /**
     * Prueba el método ->addWhere()
     *
     * @throws Exception
     * @return void
     */
    public function testAddOrderBy()
    {
        $builder = $this->getInstance();
        $builder->where('status = 1')
            ->orderBy('age asc');

        $this->assertCount(1, $builder->getOrderBy());

        $builder->addOrderBy('status desc');

        $this->assertCount(2, $builder->getOrderBy());
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
        $builder = $this->getInstance()->orWhere('error');

        // prueba con addOrWhere
        $this->expectException(Exception::class);
        $builder->addOrWhere('another error');
    }

    /**
     * Prueba el método ->validateExistingOrderBy()
     *
     * @throws Exception
     * @return void
     */
    public function testValidateExistingOrderBy()
    {
        $this->expectException(Exception::class);
        $this->getInstance()->addOrderBy('order asc');
    }

    /**
     * Obtiene una instancia de Query Builder ideal para pruebas
     *
     * @return Builder
     */
    private function getInstance(): Builder
    {
        return new Builder(Utils::getTestingConnection());
    }
}
