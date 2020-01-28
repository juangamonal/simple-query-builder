<?php

namespace QueryBuilder;

use InvalidArgumentException;
use QueryBuilder\Syntax\Regex;
use QueryBuilder\Syntax\Validator;

/**
 * Class Builder
 *
 * @package QueryBuilder
 * @author Juan Gamonal H <juangamonal@gmail.com>
 */
final class Builder
{
    public const SELECT = 0;
    public const INSERT = 1;
    public const UPDATE = 2;
    public const DELETE = 3;

    private $type = self::SELECT;
    private $connection;
    private $table;
    private $columns = ['*'];
    private $distinct = false;
    private $joins = [];
    private $inserts = [[]];
    private $wheres = [];
    private $groupBy = [];
    private $orderBy = [];

    /**
     * Builder constructor.
     *
     * @param string|null         $table      Nombre de la tabla
     * @param ConnectionInterface $connection Instancia de conexión a la BDD
     */
    public function __construct(string $table = null, ConnectionInterface $connection = null)
    {
        if (!preg_match(Regex::TABLE_NAME, $table)) {
            throw new InvalidArgumentException();
        }

        $this->table = $table;
        $this->connection = $connection;
    }

    public function execute(): void
    {

    }

    /**
     * Convierte el Query Builder en una consulta SQL
     *
     * @return string
     */
    public function toSql(): string
    {
        switch ($this->type) {
            case self::INSERT:
                return $this->getInsertSql();
            case self::UPDATE:
                return $this->getUpdateSql();
            case self::DELETE:
                return $this->getDeleteSql();
            case self::SELECT:
            default:
                return $this->getSelectSql();
        }
    }

    /**
     * Asigna columnas para realizar 'SELECT'
     *
     * @param mixed $statements,... Declaraciones para realizar 'SELECT'
     *
     * @return $this
     */
    public function select(string ...$statements): self
    {
        $this->columns = [];
        $this->type = self::SELECT;

        if (count($statements) === 0) {
            $statements = ['*'];
        }

        $this->columns = Validator::select($statements);

        return $this;
    }

    /**
     * Añade columnas para realizar 'SELECT'
     *
     * @param mixed $statements,... Declaraciones para realizar 'SELECT'
     *
     * @return $this
     */
    public function addSelect(string ...$statements): self
    {
        $this->type = self::SELECT;
        $this->columns = array_merge($this->columns, Validator::select($statements));

        return $this;
    }

    // TODO: hacer distinct
    public function distinct(): self
    {
        $this->distinct = true;

        return $this;
    }

    /**
     * Añade una inserción de una fila
     *
     * @param array $values Llave-valor para columna y valor
     *
     * @return $this
     */
    public function insert(array $values): self
    {
        $this->type = self::INSERT;
        array_push($this->inserts, Validator::insert($values));

        return $this;
    }

    public function update(): self
    {
        return $this;
    }

    public function delete(): self
    {
        return $this;
    }

    public function join(): self
    {
        return $this;
    }

    public function leftJoin(): self
    {
        return $this;
    }

    public function rightJoin(): self
    {
        return $this;
    }

    public function where(): self
    {
        return $this;
    }

    public function andWhere(): self
    {
        return $this;
    }

    public function orWhere(): self
    {
        return $this;
    }

    public function groupBy(): self
    {
        return $this;
    }

    public function addGroupBy(): self
    {
        return $this;
    }

    public function orderBy(): self
    {
        return $this;
    }

    public function addOrderBy(): self
    {
        return $this;
    }

    /**
     * Obtiene listado de columnas para 'SELECT'
     *
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * Obtiene listado de filas para 'INSERT'
     *
     * @return array
     */
    public function getInserts(): array
    {
        return $this->inserts;
    }

    /**
     * Crea una nueva instancia de Query Builder
     *
     * @param string|null $table Nombre de la tabla
     *
     * @return self
     */
    public static function table(string $table = null): self
    {
        return new self(
            $table,
            new Connection()
        );
    }

    /**
     * Genera consulta SQL para un SELECT
     *
     * @return string
     */
    private function getSelectSql(): string
    {
        // TODO: debe validar que tenga al menos una columna?

        $query = 'SELECT';

        // añade distinct
        if ($this->distinct) {
            $query .= ' DISTINCT';
        }

        // añade columnas
        $query .= ' ' . implode(', ', $this->columns);

        // añade 'from'
        $query .= ' FROM ' . $this->table;

        // TODO: añade 'where'
        // TODO: añade 'groupBy'
        // TODO: añade 'having'
        // TODO: añade 'orderBy'

        return $query;
    }

    /**
     * Genera consulta SQL para un INSERT
     *
     * @return string
     */
    private function getInsertSql(): string
    {
        return '';
    }

    /**
     * Genera consulta SQL para un UPDATE
     *
     * @return string
     */
    private function getUpdateSql(): string
    {
        return '';
    }

    /**
     * Genera consulta SQL para un DELETE
     *
     * @return string
     */
    private function getDeleteSql(): string
    {
        return '';
    }
}
