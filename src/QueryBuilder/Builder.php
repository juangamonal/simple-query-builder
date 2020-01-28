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

    /**
     * Tipo de Query según las constantes de arriba
     *
     * @var int
     */
    private $type = self::SELECT;

    /**
     * Instancia de conexión a la base de datos
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * Nombre de la tabla
     *
     * @var string|null
     */
    private $table;

    /**
     * Listado de columnas para hacer 'SELECT'
     *
     * @var array
     */
    private $selects = ['*'];

    /**
     * Indica si 'SELECT' utilizará 'DISTINCT'
     * @var bool
     */
    private $distinct = false;

    /**
     * Listado de 'JOIN' para hacer 'SELECT'
     *
     * @var array
     */
    private $joins = [];

    /**
     * Matriz con datos para la inserción masiva
     *
     * @var array
     */
    private $inserts = [];

    /**
     * Columnas a insertar a través de la declaración 'INSERT'
     *
     * @var array
     */
    private $columns = [];

    /**
     * Valores a mapear en las columnas para la declaración 'INSERT'
     *
     * @var array
     */
    private $values = [];

    /**
     * Listado de condiciones para ejecutar 'SELECT', 'UPDATE' y 'DELETE'
     *
     * @var array
     */
    private $wheres = [];

    // TODO: documentar
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
        $this->selects = [];
        $this->type = self::SELECT;

        if (count($statements) === 0) {
            $statements = ['*'];
        }

        $this->selects = Validator::select($statements);

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
        $this->selects = array_merge($this->selects, Validator::select($statements));

        return $this;
    }

    /**
     * Añade la marca de 'DISTINCT' para añadirla a consulta
     *
     * @return $this
     */
    public function distinct(): self
    {
        $this->type = self::SELECT;
        $this->distinct = true;

        return $this;
    }

    /**
     * Añade una fila para nueva inserción
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

    /**
     * Añade columnas para nueva inserción
     *
     * @param mixed $columns,... Columnas a añadir
     * @return $this
     */
    public function columns(string ...$columns): self
    {
        $this->type = self::INSERT;
        $selects = Validator::columns($columns);

        foreach ($selects as $select) {
            if (!array_key_exists($select, $this->columns)) {
                $this->columns[$select] = $select;
            }
        }

        return $this;
    }

    // TODO: docs
    public function values(string ...$values): self
    {
        $this->type = self::INSERT;

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
     * Obtiene listado de columnas para 'INSERT'
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
     * Obtiene listado de columnas para 'SELECT'
     *
     * @return array
     */
    public function getSelects(): array
    {
        return $this->selects;
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
