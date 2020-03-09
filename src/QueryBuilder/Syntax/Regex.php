<?php

namespace QueryBuilder\Syntax;

/**
 * Class Regex
 *
 * @author Juan Gamonal H <jgamonal@ucsc.cl>
 * @package QueryBuilder\Syntax
 */
final class Regex
{
    public const TABLE_NAME = '/^[A-z_0-9]+$/im';
    public const SELECT = '/^([A-z]+|\*)((?<!\*)\.([A-z]+|\*))?((?<!\*) as [A-z]+)?$/im';
    public const COLUMN_NAME = '/^[a-z0-9][a-z0-9_]+[a-z0-9]$/im';
    // TODO: where no funciona con id = 1
    /*
    public const WHERE = '/^([a-z0-9][a-z0-9_]+[a-z0-9](\.[a-z0-9][a-z0-9_]+[a-z0-9])?)'
        . '( (=|!=|\^=|<>|<|>|<=|>=) )(:?.+)$/im';*/
    public const WHERE = '//im';
}
