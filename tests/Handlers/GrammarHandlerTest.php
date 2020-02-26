<?php

namespace QueryBuilder\Handlers\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use QueryBuilder\Handlers\GrammarHandler;

/**
 * Class GrammarHandlerTest
 *
 * @author Juan Gamonal H <jgamonal@ucsc.cl>
 * @package QueryBuilder\Handlers\Tests
 */
final class GrammarHandlerTest extends TestCase
{
    /**
     * Prueba el método ::create()
     *
     * @return void
     */
    public function testCreate()
    {
        $this->expectException(InvalidArgumentException::class);
        GrammarHandler::create('invalid argument');
    }
}
