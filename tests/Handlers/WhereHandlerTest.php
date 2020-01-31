<?php

namespace QueryBuilder\Handlers\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use QueryBuilder\Builder;
use QueryBuilder\Handlers\WhereHandler;

/**
 * Class WhereHandlerTest
 *
 * @author Juan Gamonal H <jgamonal@ucsc.cl>
 * @package QueryBuilder\Handlers\Tests
 */
final class WhereHandlerTest extends TestCase
{
    /**
     * Prueba el método ->prepare()
     *
     * @throws Exception
     * @return void
     */
    public function testPrepare()
    {
        // where básico
        $builder = Builder::table('users')
            ->where('status = 1');
        $where = 'WHERE status = 1';
        $this->assertEquals($where, WhereHandler::prepare($builder->getWheres()));

        // where con múltiples 'AND'
        $builder->where('status = 1', 'age < 18')
            ->addWhere('age > 0');
        $where = 'WHERE status = 1 AND age < 18 AND age > 0';
        $this->assertEquals($where, WhereHandler::prepare($builder->getWheres()));

        // where y 'OR' where
        $builder->where('status = 1')
            ->orWhere('removed = 0');
        $where = 'WHERE status = 1 OR removed = 0';
        $this->assertEquals($where, WhereHandler::prepare($builder->getWheres()));
    }
}
