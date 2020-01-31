<?php

namespace QueryBuilder\Handlers;

/**
 * Class UpdateHandler
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Handlers
 */
final class UpdateHandler
{
    /**
     * Prepara la sentencia 'UPDATE' para ser concatenada en otra consulta
     *
     * @param string $table Nombre de la tabla
     * @param array $update Valores a modificar
     * @return string
     */
    public static function prepare(string $table, array $update): string
    {
        $query = 'UPDATE ' . $table . ' SET';

        // itera sobre los valores y lo añade a la consulta
        foreach ($update as $index => $value) {
            $query .= " $index = " . is_string($value) ? "'$value'" : $value;
            $query .= array_key_first($update) !== $index ? ', ': '';
        }

        return $query;
    }
}
