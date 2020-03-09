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
     * Prepara las declaraciones 'SELECT' para ser concatenadas en otra consulta
     *
     * @param string $table Nombre de la tabla
     * @param array $statements Listado de declaraciones 'SELECT'
     * @param bool $distinct Indica si se utiliza un 'DISTINCT'
     *
     * @return string
     */
    public function select(string $table, array $statements, bool $distinct = false): string
    {
        if (count($statements) === 0) {
            $statements = ['*'];
        }

        $query = 'SELECT';

        // añade distinct
        if ($distinct) {
            $query .= ' DISTINCT';
        }

        // parsea 'as' a mayúsuculas
        foreach ($statements as $i => $statement) {
            $statements[$i] = str_replace(' as ', ' AS ', $statement);
        }

        // añade columnas
        $query .= ' ' . implode(', ', $statements);

        // añade 'from'
        $query .= ' FROM ' . $table;

        return $query;
    }

    /**
     * Prepara las declaraciones 'SELECT COUNT' para ser concatenadas en otra consulta
     *
     * @param string $table Nombre de la tabla
     * @param array $statements Listado de declaraciones 'SELECT COUNT'
     * @param bool $distinct Indica si se utiliza un 'DISTINCT'
     *
     * @return string
     */
    public function count(string $table, array $statements, bool $distinct = false): string
    {
        if (count($statements) === 0) {
            $statements = ['*'];
        }

        $query = 'SELECT';

        // añade distinct
        if ($distinct) {
            $query .= ' DISTINCT';
        }

        // parsea 'as' a mayúsuculas
        foreach ($statements as $i => $statement) {
            $statements[$i] = str_replace(' as ', ' AS ', $statement);
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

    /**
     * Prepara la sentencia 'INSERT' para ser concatenada en otra consulta
     *
     * @param string $table Nombre de la tabla
     * @param array $insert Valores a insertar
     * @param bool $bind Utilizará binding para la query?
     *
     * @return string
     */
    public function insert(string $table, array $insert, bool $bind = true): string
    {
        $query = 'INSERT INTO ' . $table .
            ' (' . implode(', ', array_keys($insert)) . ') VALUES (';

        if ($bind) {
            foreach (array_keys($insert) as $index => $key) {
                $query .= ":$key";
                $query .= $index < (count($insert) - 1) ? ', ': '';
            }
        } else {
            foreach (array_values($insert) as $index => $value) {
                $query .= is_string($value) ? "'$value'" : $value;
                $query .= $index < (count($insert) - 1) ? ', ': '';
            }
        }

        $query .= ')';

        return $query;
    }

    /**
     * Prepara la sentencia 'UPDATE' para ser concatenada en otra consulta
     *
     * @param string $table Nombre de la tabla
     * @param array $update Valores a modificar
     *
     * @return string
     */
    public function update(string $table, array $update): string
    {
        $query = 'UPDATE ' . $table . ' SET';

        // itera sobre los valores y lo añade a la consulta
        foreach ($update as $index => $value) {
            $query .= key($update) !== $index ? ',' : '';
            $query .= " $index = ";
            $query .= ":$index";
        }

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
                    if (key($clauses) !== $clause) {
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

    /**
     * Prepara las declaración 'LIMIT' para ser concatenada en otra consulta
     *
     * @param int $limit Cantidad de registros a consultar
     *
     * @return string
     */
    public function limit(int $limit): string
    {
        return ' LIMIT ' . $limit;
    }
}
