<?php

namespace QueryBuilder;

use QueryBuilder\Types\Where;

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

    /**
     * Prepara las cláusulas 'WHERE' para ser concatenadas en otra consulta
     *
     * @param array $clauses Listado de cláusulas 'WHERE'
     *
     * @return string
     */
    public function where(array $clauses): string
    {
        // TODO: manejar sub queries y todo eso
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
