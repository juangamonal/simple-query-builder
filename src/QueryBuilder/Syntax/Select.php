<?php

namespace QueryBuilder\Syntax;

use InvalidArgumentException;

/**
 * Representa la sentencia SELECT en el lenguaje SQL
 *
 * @author Juan Gamonal H <jgamonal@ucsc.cl>
 * @package QueryBuilder\Syntax
 */
class Select
{
    /**
     * Declaración/sentencia del SELECT
     *
     * @var string
     */
    private $statement;

    /**
     * Select constructor.
     *
     * @param string $statement
     */
    public function __construct(string $statement)
    {
        $statement = trim($statement);

        if (!preg_match(Regex::SELECT, $statement)) {
            // TODO: QBException
            throw new InvalidArgumentException();
        }

        // en caso de tener alias, se transforma a mayúsculas
        if (strpos($statement, ' as ')) {
            $statement = str_replace(' as ', ' AS ', $statement);
        }

        $this->statement = $statement;
    }

    /**
     * Obtiene la declaración/sentencia SELECT
     *
     * @return string
     */
    public function getStatement(): string
    {
        return $this->statement;
    }
}
