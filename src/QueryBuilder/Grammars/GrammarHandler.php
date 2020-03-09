<?php

namespace QueryBuilder\Grammars;

use InvalidArgumentException;
use QueryBuilder\Grammar;
use QueryBuilder\Types\Driver;

/**
 * Class GrammarHandler
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Grammars
 */
final class GrammarHandler
{
    /**
     * Genera una instancia de Grammar según el valor entregado
     *
     * @param string $name Nombre del motor de base de datos
     * @return Grammar
     */
    public static function create(string $name): Grammar
    {
        switch ($name) {
            case Driver::ORACLE:
                return new OracleGrammar();
            case Driver::MYSQL:
                return new MySqlGrammar();
            case Driver::MSSQL:
                return new SqlServerGrammar();
            case Driver::PGSQL:
                return new PostgresGrammar();
            case Driver::SQLITE:
                return new SQLiteGrammar();
            default:
                throw new InvalidArgumentException();
        }
    }
}
