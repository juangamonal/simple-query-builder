<?php

namespace QueryBuilder\Handlers;

use QueryBuilder\Types\Where;

/**
 * Class WhereHandler
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Handlers
 */
final class WhereHandler
{
    /**
     * Prepara las cláusulas 'WHERE' para ser concatenadas en otra consulta
     *
     * @param array $clauses Listado de cláusulas 'WHERE'
     * @return string
     */
    public static function prepare(array $clauses): string
    {
        $query = 'WHERE';

        foreach ($clauses as $clause => $type) {
            // maneja 'WHERE' según $type
            switch ($type) {
                case Where::AND:
                    // si es el primer elemento, no añade 'AND'
                    if (array_key_first($clauses) !== $clause) {
                        $query .= ' AND';
                    }

                    break;
                case Where::OR:
                    $query .= ' OR';
                    break;
            }

            $query .= " $clause";
        }

        return $query;
    }
}
