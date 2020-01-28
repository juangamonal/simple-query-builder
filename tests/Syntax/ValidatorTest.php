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
    /*
    public function testInsert()
    {

    }
    */
}
