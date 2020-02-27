<?php

namespace QueryBuilder\Grammars;

use PHPUnit\Framework\TestCase;

/**
 * Class PostgresGrammarTest
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Grammars
 */
class PostgresGrammarTest extends TestCase
{
    public function testDummy()
    {
        $this->assertEquals(true, !false);
    }
}
