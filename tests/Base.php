<?php

namespace QueryBuilder\Tests;

use Dotenv\Dotenv;
use PDO;
use PHPUnit\Framework\TestCase;
use QueryBuilder\Builder;
use QueryBuilder\DefaultConnection;

/**
 * Class Base
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Tests
 */
class Base extends TestCase
{
    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var PDO
     */
    protected $conn;

    /**
     * @var int
     */
    protected $qty = 5;

    /**
     * Base constructor.
     *
     * @param PDO|null $conn
     * @param null $name
     * @param array $data
     * @param string $dataName
     *
     */
    public function __construct(PDO $conn = null, $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $this->conn = $conn ?: new DefaultConnection();
        $this->builder = new Builder($this->conn);
        Populate::reset($this->conn);
        Populate::insert($this->conn, $this->qty);
    }
}
