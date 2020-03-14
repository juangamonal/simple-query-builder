<?php

namespace QueryBuilder\Types;

/**
 * Class Query
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Types
 */
class Query
{
    public const SELECT = 0;
    public const INSERT = 1;
    public const UPDATE = 2;
    public const DELETE = 3;
}
