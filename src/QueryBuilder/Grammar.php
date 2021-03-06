<?php

namespace QueryBuilder;

use PDO;
use QueryBuilder\Types\OrderBy;
use QueryBuilder\Types\Where;

/**
 * Class Grammar
 *
 * @package QueryBuilder
 * @author Juan Gamonal H <juangamonal@gmail.com>
 */
class Grammar
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

        // añade columnas
        $query .= ' ' . implode(', ', array_map(function ($s) {
            return $s->getStatement();
        }, $statements));

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

        // añade columnas
        foreach (array_values($statements) as $index => $statement) {
            $statement = $statement->getStatement();

            if (strpos($statement, ' AS ')) {
                $pieces = explode(' ', $statement);
                $query .= " COUNT($pieces[0]) AS $pieces[2]";
            } else {
                $query .= " COUNT($statement)";
            }

            $query .= $index < (count($statements) - 1) ? ',' : '';
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
        $data = [];

        foreach ($insert as $i) {
            $data[$i->getColumn()] = $i->getValue();
        }

        $query = 'INSERT INTO ' . $table .
            ' (' . implode(', ', array_keys($data)) . ') VALUES (';

        if ($bind) {
            foreach (array_keys($data) as $index => $key) {
                $query .= ":$key";
                $query .= $index < (count($data) - 1) ? ', ' : '';
            }
        } else {
            foreach (array_values($data) as $index => $value) {
                $query .= is_null($value) ? 'NULL' : (is_string($value) ? "'$value'" : $value);
                $query .= $index < (count($data) - 1) ? ', ' : '';
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
     * @param bool $bind Utilizará binding para la query
     *
     * @return string
     */
    public function update(string $table, array $update, bool $bind = true): string
    {
        $query = 'UPDATE ' . $table . ' SET';

        foreach ($update as $index => $value) {
            $query .= key($update) !== $index ? ',' : '';
            $query .= " $index = ";

            if ($bind) {
                $query .= ":$index";
            } else {
                $query .= is_null($value) ? 'NULL' : (is_string($value) ? "'$value'" : $value);
            }
        }

        return $query;
    }

    /**
     * Prepara las cláusulas 'JOIN' para ser concatenada en otra consulta
     * TODO: bind
     *
     * @param array $joins Listado de uniones a realizar
     * @param bool $bind Utilizará binding para la query
     *
     * @return string
     */
    public function join(array $joins, bool $bind = true): string
    {
        $query = '';
        $total = count($joins);

        foreach ($joins as $i => $join) {
            $query .= "{$join->getType()} JOIN {$join->getTable()} ON {$join->getCondition()}";

            if (($i + 1) < $total) {
                $query .= " ";
            }
        }

        return $query;
    }

    /**
     * Prepara las cláusulas 'WHERE' para ser concatenadas en otra consulta
     *
     * @param array $clauses Listado de cláusulas 'WHERE'
     * @param bool $bind Utilizará binding para la query
     *
     * @return string
     */
    public function where(array $clauses, $bind = true): string
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

            if ($bind) {
                $c = explode(' ', $clause);
                $query .= sprintf(' %s %s :%s', $c[0], $c[1], $c[0]);
            } else {
                $query .= " $clause";
            }
        }

        return $query;
    }

    /**
     * Prepara la declaración 'LIMIT' para ser concatenada en otra consulta
     *
     * @param int $limit Cantidad de registros a consultar
     *
     * @return string
     */
    public function limit(int $limit): string
    {
        return 'LIMIT ' . $limit;
    }

    /**
     * Prepara los comandos 'ORDER BY' para ser concatenados en otra consulta
     *
     * @param array $commands Listado de comandos 'ORDER BY'
     *
     * @return string
     */
    public function orderBy(array $commands): string
    {
        $query = 'ORDER BY ';

        foreach ($commands as $column => $type) {
            // si es el primer elemento, no añade 'AND'
            if (key($commands) !== $column) {
                $query .= ', ';
            }

            $query .= "$column $type";
        }

        return $query;
    }
}
