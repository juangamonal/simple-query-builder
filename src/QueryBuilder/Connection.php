<?php

namespace QueryBuilder;

use QueryBuilder\Types\Engine;

/**
 * Class Connection
 *
 * @package QueryBuilder
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 */
class Connection implements ConnectionInterface
{
    /**
     * Define la gramática que debe usar para crear las consultas
     *
     * @var string
     */
    private $engine;

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
        return Engine::ORACLE;
    }
}
