<?php

namespace QueryBuilder\Syntax;

/**
 * Representa la cláusula JOIN del lenguaje SQL.
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Syntax
 */
class Join
{
    /** @var string */
    public const INNER = 'INNER';

    /** @var string */
    public const LEFT = 'LEFT';

    /** @var string */
    public const RIGHT = 'RIGHT';

    /** @var string */
    public const OUTER = 'OUTER';

    /**
     * Condición del JOIN
     *
     * @var string
     */
    private $condition;

    /**
     * Tabla que se busca unir
     *
     * @var string
     */
    private $table;

    /**
     * Tipo de JOIN (inner, left, etc) según las constantes de esta clase
     *
     * @var string
     */
    private $type;

    /**
     * Join constructor.
     *
     * @param string $table
     * @param string $condition
     * @param string $type
     */
    public function __construct(string $table, string $condition, string $type)
    {
        $this->condition = $condition;
        $this->table = $table;
        $this->type = $type;
    }

    /**
     * Obtiene la condición del 'JOIN'
     *
     * @return string
     */
    public function getCondition(): string
    {
        return $this->condition;
    }

    /**
     * Obtiene la tabla que se busca unir
     *
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Obtiene el tipo de JOIN (inner, left, etc)
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
