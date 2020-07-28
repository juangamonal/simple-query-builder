<?php

namespace QueryBuilder\Syntax;

use InvalidArgumentException;
use QueryBuilder\Types\OrderBy;
use QueryBuilder\Types\Where;

/**
 * Class Validator
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Syntax
 */
final class Validator
{
    // TODO: hacer?
    // public static function create(){}

    /**
     * Valida un nuevo 'SELECT' añadido
     *
     * @param array $statements Declaraciones a validar
     *
     * @return array
     */
    public static function select(array $statements)
    {
        $columns = [];

        foreach ($statements as $statement) {
            $statement = trim($statement);

            if (!preg_match(Regex::SELECT, $statement)) {
                throw new InvalidArgumentException();
            }

            // en caso de tener alias, se transforma a mayúsculas
            if (strpos($statement, ' as ')) {
                $statement = str_replace(' as ', ' AS ', $statement);
            }

            array_push($columns, $statement);
        }

        return $columns;
    }

    /**
     * Valida un nuevo 'INSERT' añadido
     *
     * @param array $values Valores a insertar
     *
     * @return array
     */
    public static function insert(array $values)
    {
        // verifica si es array asociativo
        if (array_keys($values) !== range(0, count($values) - 1)) {
            $columns = [];

            foreach ($values as $column => $value) {
                $column = trim($column);

                if (!preg_match(Regex::COLUMN_NAME, $column)) {
                    throw new InvalidArgumentException();
                }

                $columns[$column] = $value;
            }

            return $columns;
        } else {
            // TODO: indicar que no es array asociativo?
            throw new InvalidArgumentException();
        }
    }

    /**
     * Valida un nuevo 'WHERE' añadido
     *
     * @param array $clauses Cláusulas 'WHERE' a añadir
     * @param int $type
     *
     * @return array
     */
    public static function where(array $clauses, int $type = null)
    {
        $wheres = [];

        foreach ($clauses as $clause) {
            $clause = trim($clause);

            if (!preg_match(Regex::WHERE, $clause)) {
                throw new InvalidArgumentException();
            }

            $wheres[$clause] = is_null($type) ? Where::AND : $type;
        }

        return $wheres;
    }

    /**
     * Valida los comandos 'ORDER BY' añadidos
     *
     * @param array $commands Comandos 'ORDER BY' a añadir
     *
     * @return array
     */
    public static function orderBy(array $commands)
    {
        $orderBy = [];

        foreach ($commands as $command) {
            $command = trim($command);

            if (!preg_match(Regex::ORDER_BY, $command)) {
                throw new InvalidArgumentException();
            }

            $pieces = explode(' ', $command);
            $orderBy[$pieces[0]] = array_key_exists(1, $pieces) ? strtoupper($pieces[1]) : OrderBy::ASC;
        }

        return $orderBy;
    }
}
