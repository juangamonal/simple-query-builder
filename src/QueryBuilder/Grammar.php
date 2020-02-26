<?php

namespace QueryBuilder;

/**
 * Class Grammar
 *
 * @package QueryBuilder
 * @author Juan Gamonal H <juangamonal@gmail.com>
 */
abstract class Grammar
{
    /**
     * Prepara la sentencia 'INSERT' para ser concatenada en otra consulta
     *
     * @param string $table Nombre de la tabla
     * @param array $insert Valores a insertar
     *
     * @return string
     */
    public function insert(string $table, array $insert): string
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
