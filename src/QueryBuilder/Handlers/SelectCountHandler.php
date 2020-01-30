<?php

namespace QueryBuilder\Handlers;

/**
 * Class SelectCountHandler
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Handlers
 */
class SelectCountHandler
{
    /**
     * Prepara las declaraciones 'SELECT COUNT' para ser concatenadas en otra consulta
     *
     * @param string $table Nombre de la tabla
     * @param array $statements Listado de declaraciones 'SELECT COUNT'
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
        foreach (array_values($statements) as $index => $statement) {
            if (strpos($statement, ' AS ')) {
                $pieces = explode(' ', $statement);
                $query .= " COUNT($pieces[0]) AS $pieces[2]";
            } else {
                $query .= " COUNT($statement)";
            }

            $query .= $index < (count($statements) - 1) ? ',': '';
        }

        // añade 'from'
        $query .= ' FROM ' . $table;

        return $query;
    }
}
