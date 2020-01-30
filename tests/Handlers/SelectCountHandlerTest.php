<?php

namespace QueryBuilder\Handlers\Tests;

use PHPUnit\Framework\TestCase;
use QueryBuilder\Builder;
use QueryBuilder\Handlers\SelectCountHandler;

/**
 * Class SelectCountHandlerTest
 *
 * @author Juan Gamonal H <jgamonal@ucsc.cl>
 * @package QueryBuilder\Handlers\Tests
 */
class SelectCountHandlerTest extends TestCase
{
    /**
     * Prueba el método ->prepare()
     *
     * @return void
     */
    public function testPrepare()
    {
        // prepara un 'COUNT' sencillo
        $builder = Builder::table('users')
            ->count('email', 'name as full_name');
        $select = 'SELECT COUNT(email), COUNT(name) AS full_name FROM users';
        $this->assertEquals($select, SelectCountHandler::prepare(
            $builder->getTable(),
            $builder->getCounts()
        ));

        // prepara un 'SELECT' con 'DISTINCT'
        $builder = Builder::table('users')
            ->count('email as user_email', 'name', 'status')
            ->distinct();
        $select = 'SELECT DISTINCT COUNT(email) AS user_email, COUNT(name), COUNT(status) FROM users';
        $this->assertEquals($select, SelectCountHandler::prepare(
            $builder->getTable(),
            $builder->getCounts(),
            $builder->getDistinct()
        ));
    }
}
