<?php

namespace QueryBuilder;

/**
 * Interface ConnectionInterface
 *
 * @author
 */
interface ConnectionInterface
{
    /**
     * Devuelve el String de conexión
     *
     * @return string
     */
    public function getConnectionString(): string;
}
