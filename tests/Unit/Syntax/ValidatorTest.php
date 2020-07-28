<?php

namespace QueryBuilder\Tests\Unit\Syntax;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use QueryBuilder\Syntax\Validator;
use QueryBuilder\Types\OrderBy;
use QueryBuilder\Types\Where;

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
            'with_alias AS alias'
        ];
        $this->assertEquals($valid, Validator::select($valid));

        // en caso de recibir un alias, 'as' pasa a ser mayúsculas
        $original = ['name as full_name', 'email as as'];
        $valid = ['name AS full_name', 'email AS as'];
        $this->assertEquals($valid, Validator::select($original));

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
     * Prueba la validación de los 'WHERE'
     *
     * @return void
     */
    public function testWhere()
    {
        // where correcto
        $valid = ['status = 1', 'age > 18'];
        $result = ['status = 1' => Where::AND, 'age > 18' => Where::AND];
        $this->assertEquals($result, Validator::where($valid));

        // error al tener cláusula inválida
        // TODO: arreglar junto con el Regex de Where
        // $this->expectException(InvalidArgumentException::class);
        // Validator::where(['invalid syntax']);

        // orWhere
        $result = ['status = 1' => Where::OR, 'age > 18' => Where::OR];
        $this->assertEquals($result, Validator::where($valid, Where::OR));
    }

    /**
     * Prueba la validación de los 'ORDER BY'
     *
     * @return void
     */
    public function testOrderBy()
    {
        // order by correcto
        $valid = ['age asc', 'status desc'];
        $result = ['age' => OrderBy::ASC, 'status' => OrderBy::DESC];
        $this->assertEquals($result, Validator::orderBy($valid));

        // order by con tipo 'ASC' por defecto
        $valid = ['age'];
        $result = ['age' => OrderBy::ASC];
        $this->assertEquals($result, Validator::orderBy($valid));

        // error al tener un comando inválido
        // TODO: arreglar junto con el Regex de OrderBy
        // $this->expectException(InvalidArgumentException::class);
        // Validator::orderBy(['age invalid']
    }
}
