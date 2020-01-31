<?php

namespace QueryBuilder\Handlers;

/**
 * Class UpdateHandler
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Handlers
 */
final class UpdateHandler
{
    /**
     * Prepara la sentencia 'UPDATE' para ser concatenada en otra consulta
     *
     * @param string $table Nombre de la tabla
     * @param array $update Valores a modificar
     * @return string
     */
    public static function prepare(string $table, array $update): string
    {
        $query = 'UPDATE ' . $table;

        // TODO maneja los 'SET'

        return $query;
    }
}
