<?php

namespace QueryBuilder\Syntax;

use InvalidArgumentException;

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
     * Valida un nuevo 'Select' añadido
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

            array_push($columns, $statement);
        }

        return $columns;
    }

    /**
     * Valida un nuevo 'Insert' añadido
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
     * Valida nuevas columnas añadidas al 'Insert'
     *
     * @param array $columns Columnas a insertar
     *
     * @return array
     */
    public static function columns(array $columns)
    {
        foreach ($columns as $i => $column) {
            $column = trim($column);

            if (!preg_match(Regex::COLUMN_NAME, $column)) {
                throw new InvalidArgumentException();
            }

            $columns[$i] = $column;
        }

        return $columns;
    }
}
