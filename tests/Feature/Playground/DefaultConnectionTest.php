<?php

namespace QueryBuilder\Tests\Feature\Playground;

use PHPUnit\Framework\TestCase;
use QueryBuilder\Builder;
use QueryBuilder\DefaultConnection;

/**
 * Class DefaultConnectionTest
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Tests\Feature\Playground
 */
class DefaultConnectionTest extends TestCase
{
    /**
     * @var Builder Query Builder instance
     */
    private $builder;

    /**
     * MySqlTest constructor.
     *
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->builder = new Builder(
            new DefaultConnection()
        );

        parent::__construct($name, $data, $dataName);
    }

    /**
     * Intenta realizar una consulta de tipo 'SELECT'
     *
     * @return void
     */
    public function testSelect()
    {
        $sql = $this->builder->setTable('users')
            ->where('status = 1')
            ->select('id', 'name', 'email')
            ->execute();

        $this->assertCount(1, $sql);
    }
}
