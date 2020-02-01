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
        // prepara un 'UPDATE'
        $builder = Builder::table('users')
            ->update([
                'age' => 20,
                'email' => 'juangamonalh@gmail.com'
            ]);
        $sql = "UPDATE users SET age = 20, email = 'juangamonalh@gmail.com'";
        $this->assertEquals($sql, UpdateHandler::prepare(
            $builder->getTable(),
            $builder->getUpdate()
        ));

        // TODO: probar con demás tipos de datos
    }
}
