<?php

namespace QueryBuilder\Syntax;

use QueryBuilder\Types\Join as JoinTypes;

/**
 * Class Join
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Syntax
 */
class Join
{
    /**
     * Condición del Join
     *
     * @var string
     */
    private $condition;

    /**
     * Nombre de la tabla a la que hace join
     *
     * @var string
     */
    private $table;

    /**
     * Tipo de Join
     *
     * @var string
     */
    private $type;

    /**
     * Join constructor.
     *
     * @param string $table Nombre de la tabla
     * @param string $condition Condición del Join
     * @param string $type Tipo de Join
     */
    public function __construct(string $table, string $condition, string $type = JoinTypes::INNER)
    {
        // TODO: validar table
        // TODO: validar condition
        $this->condition = $condition;
        $this->table = $table;
        $this->type = $type;
    }

    /**
     * Crea una nueva instancia de Join
     *
     * @param string $table Nombre de la tabla
     * @param string $condition Condición del Join
     * @param string $type Tipo de Join
     *
     * @return self
     */
    public static function create(string $table, string $condition, string $type = JoinTypes::INNER): self
    {
        return new self($table, $condition, $type);
    }
}
