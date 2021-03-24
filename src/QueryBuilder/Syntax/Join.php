<?php

namespace QueryBuilder\Syntax;

/**
 * Class Join
 * TODO docs
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Syntax
 */
class Join
{
    public const INNER = 'INNER';
    public const LEFT = 'LEFT';
    public const RIGHT = 'RIGHT';
    public const OUTER = 'OUTER';

    private $condition;
    private $table;
    private $type;

    public function __construct(string $table, string $condition, string $type)
    {
        $this->condition = $condition;
        $this->table = $table;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getCondition(): string
    {
        return $this->condition;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
