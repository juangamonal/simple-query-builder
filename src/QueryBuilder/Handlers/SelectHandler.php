<?php

namespace QueryBuilder\Handlers;

/**
 * Class SelectHandler
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Handlers
 */
class SelectHandler
{
    /**
     * Prepara las declaraciones 'SELECT' para ser concatenadas en otra consulta
     *
     * @param string $table Nombre de la tabla
     * @param array $statements Listado de declaraciones 'SELECT'
     * @param bool $distinct Indica si se utiliza un 'DISTINCT'
     *
     * @return string
     */
    public static function prepare(string $table, array $statements, bool $distinct = false): string
    {
        // TODO: debe validar que tenga al menos una columna?

        $query = 'SELECT';

        // añade distinct
        if ($distinct) {
            $query .= ' DISTINCT';
        }

        // añade columnas
        $query .= ' ' . implode(', ', $statements);

        // añade 'from'
        $query .= ' FROM ' . $table;

        return $query;
    }
}
