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
