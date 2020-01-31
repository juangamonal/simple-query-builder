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
     * TODO: pasar la lógica acá desde el Builder
     *
     * @param string $table Nombre de la tabla
     * @param array $insert Valores a insertar
     * @return string
     */
    public static function prepare(string $table, array $insert): string
    {
        $query = '';

        return $query;
    }
}
