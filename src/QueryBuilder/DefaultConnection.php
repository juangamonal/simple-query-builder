<?php

namespace QueryBuilder;

use QueryBuilder\Types\Engine;

/**
 * Class DefaultConnection
 *
 * @package QueryBuilder
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 */
class DefaultConnection implements ConnectionInterface
{
    /**
     * Define la gramática que debe usar para crear las consultas
     *
     * @var string
     */
    private $engine;

    /**
     * DefaultConnection constructor.
     */
    public function __construct()
    {
        $default = getenv('QB_DEFAULT_ENGINE');
        $this->engine = $default ?: Engine::MYSQL;
    }

    /**
     * Devuelve el String de conexión
     *
     * @return string
     */
    public function getConnectionString(): string
    {
        // TODO: Implement getConnectionString() method.
        return 'asd';
    }

    /**
     * Devuelve el nombre del motor asociado a la conexión
     *
     * @return string
     */
    public function getEngine(): string
    {
        return $this->engine;
    }
}
