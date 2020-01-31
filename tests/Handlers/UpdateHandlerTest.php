<?php

namespace QueryBuilder\Handlers\Tests;

use PHPUnit\Framework\TestCase;
use QueryBuilder\Builder;
use QueryBuilder\Handlers\UpdateHandler;

/**
 * Class UpdateHandlerTest
 *
 * @author Juan Gamonal H <jgamonal@ucsc.cl>
 * @package QueryBuilder\Handlers\Tests
 */
class UpdateHandlerTest extends TestCase
{
    /**
     * Prueba el método ->prepare()
     *
     * @return void
     */
    public function testPrepare()
    {
        // prepara un 'UPDATE' sencillo
        $builder = Builder::table('users')
            ->update([
                'status' => 1
            ]);
        $sql = 'UPDATE users SET status = 1';
        $this->assertEquals($sql, UpdateHandler::prepare(
            $builder->getTable(),
            $builder->getUpdate()
        ));
    }
}
