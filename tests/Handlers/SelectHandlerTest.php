<?php

namespace QueryBuilder\Handlers\Tests;

use PHPUnit\Framework\TestCase;
use QueryBuilder\Builder;
use QueryBuilder\Handlers\SelectHandler;

/**
 * Class SelectHandlerTest
 *
 * @author Juan Gamonal H <jgamonal@ucsc.cl>
 * @package QueryBuilder\Handlers\Tests
 */
class SelectHandlerTest extends TestCase
{
    /**
     * Prueba el método ->prepare()
     *
     * @return void
     */
    public function testPrepare()
    {
        // prepara un 'SELECT' sencillo
        $builder = Builder::table('users')
            ->select('email', 'name as full_name');
        $select = 'SELECT email, name AS full_name FROM users';
        $this->assertEquals($select, SelectHandler::prepare(
            $builder->getTable(),
            $builder->getSelects()
        ));

        // prepara un 'SELECT' con 'DISTINCT'
        $builder = Builder::table('users')
            ->select('email AS user_email', 'name', 'status')
            ->distinct();
        $select = 'SELECT DISTINCT email AS user_email, name, status FROM users';
        $this->assertEquals($select, SelectHandler::prepare(
            $builder->getTable(),
            $builder->getSelects(),
            $builder->getDistinct()
        ));
    }
}
