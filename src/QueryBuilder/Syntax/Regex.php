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
    public const INSERT = '/^[a-z]+$/im';
}
