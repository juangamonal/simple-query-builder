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
