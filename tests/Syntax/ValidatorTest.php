<?php

namespace QueryBuilder\Syntax\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use QueryBuilder\Syntax\Validator;

/**
 * Class ValidatorTest
 *
 * @author Juan Gamonal H <jgamonal@ucsc.cl>
 * @package QueryBuilder\Syntax\Tests
 */
final class ValidatorTest extends TestCase
{
    /**
     * Prueba la validación de los 'SELECT'
     *
     * @return void
     */
    public function testSelect()
    {
        // prueba que un set de selects sean validados
        $valid = [
            'table.column',
            '*',
            'with_alias as alias'
        ];
        $this->assertEquals($valid, Validator::select($valid));

        // excepción al recibir al menos un valor inválido
        $invalid = ['invalid syntax'];

        $this->expectException(InvalidArgumentException::class);
        Validator::select($invalid);
    }

    /**
     * Prueba la validación de los 'INSERT'
     *
     * @return void
     */
    public function testInsert()
    {
        // insert correcto
        $valid = ['name' => 'Juan Gamonal', 'email' => 'juangamonalh@gmail.com'];
        $this->assertEquals($valid, Validator::insert($valid));

        // error al no ser array asociativo
        $this->expectException(InvalidArgumentException::class);
        Validator::insert(['value_one', 'value_two']);

        // error al tener nombre de columna inválida
        $this->expectException(InvalidArgumentException::class);
        Validator::insert(['_invalid' => 'value_one']);
    }

    /**
     * Prueba la validación de las columnas en 'INSERT'
     *
     * @return void
     */
    public function testColumns()
    {
        // insert correcto
        $valid = ['value_one', 'value_two'];
        $this->assertEquals($valid, Validator::columns($valid));

        // error al tener nombre de columna inválida
        $invalid = ['_invalid'];
        $this->expectException(InvalidArgumentException::class);
        Validator::columns($invalid);
    }
}
