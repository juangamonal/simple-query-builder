<?php

namespace QueryBuilder\Grammars;

use InvalidArgumentException;
use QueryBuilder\Grammar;
use QueryBuilder\Types\Engine;

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
            case Engine::ORACLE:
                return new OracleGrammar();
            case Engine::MYSQL:
                return new MySqlGrammar();
            case Engine::MSSQL:
                return new SqlServerGrammar();
            case Engine::PGSQL:
                return new PostgresGrammar();
            case Engine::SQLITE:
                return new SQLiteGrammar();
            default:
                throw new InvalidArgumentException();
        }
    }
}
