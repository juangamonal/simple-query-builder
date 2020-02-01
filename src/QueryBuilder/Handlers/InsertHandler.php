<?php

namespace QueryBuilder\Handlers;

/**
 * Class InsertHandler
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Handlers
 */
final class InsertHandler
{
    /**
     * Prepara la sentencia 'INSERT' para ser concatenada en otra consulta
     *
     * @param string $table Nombre de la tabla
     * @param array $insert Valores a insertar
     *
     * @return string
     */
    public static function prepare(string $table, array $insert): string
    {
        $query = 'INSERT INTO ' . $table .
            ' (' . implode(', ', array_keys($insert)) . ') VALUES (';

        // itera sobre los valores y lo añade a la consulta
        foreach (array_values($insert) as $index => $value) {
            $query .= is_string($value) ? "'$value'" : $value;
            $query .= $index < (count($insert) - 1) ? ', ': '';
        }

        $query .= ')';

        return $query;
    }
}
