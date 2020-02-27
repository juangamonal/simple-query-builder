<?php

namespace QueryBuilder\Tests\Unit\Grammars;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use QueryBuilder\Grammars\GrammarHandler;

/**
 * Class GrammarHandlerTest
 *
 * @author Juan Gamonal H <jgamonal@ucsc.cl>
 * @package QueryBuilder\Grammars\Tests
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
