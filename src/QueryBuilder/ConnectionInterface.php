<?php

namespace QueryBuilder;

/**
 * Interface ConnectionInterface
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder
 */
interface ConnectionInterface
{
    /**
     * Devuelve el String de conexión
     *
     * @return string
     */
    public function getConnectionString(): string;

    /**
     * Devuelve el nombre del motor asociado a la conexión
     *
     * @return string
     */
    public function getEngine(): string;
}
