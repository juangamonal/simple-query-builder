<?php

namespace QueryBuilder\Syntax;

use InvalidArgumentException;

/**
 * Representa la sentencia INSERT en el lenguaje SQL, almacena la columna de la tabla y el valor que se insertará en
 * ella.
 *
 * @author Juan Gamonal H <jgamonal@ucsc.cl>
 * @package QueryBuilder\Syntax
 */
class Insert
{
    /**
     * Columna a la cual insertar
     *
     * @var string
     */
    private $column;

    /**
     * Valor a almacenar
     *
     * @var string
     */
    private $value;

    /**
     * Select constructor.
     *
     * @param string $column Columna a la cual insertar el valor
     * @param $value Valor a almacenar
     */
    public function __construct(string $column, $value)
    {
        $column = trim($column);

        if (!preg_match(Regex::COLUMN_NAME, $column)) {
            // TODO mejorar excepción
            throw new InvalidArgumentException();
        }

        $this->column = $column;
        $this->value = $value;
    }

    /**
     * Obtiene la columna a la cual insertar el valor
     *
     * @return string
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * Obtiene el valor que se almacenará
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
