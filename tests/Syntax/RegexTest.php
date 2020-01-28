<?php

namespace QueryBuilder\Syntax\Tests;

use PHPUnit\Framework\TestCase;
use QueryBuilder\Syntax\Regex;

/**
 * Class RegexTest
 *
 * @author Juan Gamonal H <jgamonal@ucsc.cl>
 * @package QueryBuilder\Syntax\Tests
 */
final class RegexTest extends TestCase
{
    /**
     * Prueba que el nombre de tabla valide correctamente
     *
     * @return void
     */
    public function testTableName()
    {
        // nombres válidos
        $names = [
            'table',
            'table_name',
            'valid_table_name',
            'table_1',
            'table_1_one'
        ];

        foreach ($names as $name) {
            $this->assertEquals(
                true,
                preg_match(Regex::TABLE_NAME, $name)
            );
        }

        // nombres inválidos
        $names = [
            'invalid.name',
            '*',
            'invalid?'
        ];

        foreach ($names as $name) {
            $this->assertEquals(
                false,
                preg_match(Regex::TABLE_NAME, $name)
            );
        }
    }

    /**
     * Prueba que la expresión de 'Select' valide correctamente
     *
     * @return void
     */
    public function testSelect()
    {
        // selects válidos
        $statements = [
            'field',
            'field_name',
            'field as alias',
            'table.field',
            'table.field_name',
            'table.field as alias',
            'table.field_name as alias',
            '*',
            'table.*'
        ];

        foreach ($statements as $statement) {
            $this->assertEquals(
                true,
                preg_match(Regex::SELECT, $statement)
            );
        }

        // selects inválidos
        $statements = [
            'invalid syntax',
            '*.column',
            '*.*',
            '* as alias',
            'table.* as alias'
        ];

        foreach ($statements as $statement) {
            $this->assertEquals(
                false,
                preg_match(Regex::SELECT, $statement)
            );
        }
    }

    /**
     * Prueba que la expresión de 'Insert' valide correctamente
     *
     * @return void
     */
    public function testInsert()
    {
        // inserts válidos
        $statements = [
            'column',
            'column_name',
            'column_1'
        ];

        foreach ($statements as $statement) {
            $this->assertEquals(
                true,
                preg_match(Regex::COLUMN_NAME, $statement)
            );
        }

        // inserts inválidos
        $statements = [
            'invalid syntax',
            '_invalid',
            'invalid_'
        ];

        foreach ($statements as $statement) {
            $this->assertEquals(
                false,
                preg_match(Regex::COLUMN_NAME, $statement)
            );
        }
    }
}
