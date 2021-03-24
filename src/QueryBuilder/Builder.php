<?php

namespace QueryBuilder;

use Exception;
use PDO;
use QueryBuilder\Exceptions\UndefinedTableNameException;
use QueryBuilder\Grammars\GrammarHandler;
use QueryBuilder\Syntax\Join;
use QueryBuilder\Syntax\Validator;
use QueryBuilder\Types\Query;
use QueryBuilder\Types\Where;

/**
 * Class Builder
 *
 * @author Juan Gamonal H <juangamonal@gmail.com>
 * @package QueryBuilder
 */
class Builder extends ConnectionHandler
{
    /**
     * Tipo de Query según las constantes de la clase Types\Query
     *
     * @var int
     */
    private $type = Query::SELECT;

    /**
     * Define la gramática que debe usar para crear las consultas
     *
     * @var Grammar
     */
    private $grammar;

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
    private $selects = [];

    /**
     * Indica si 'SELECT' utilizará 'DISTINCT'
     *
     * @var bool
     */
    private $distinct = false;

    /**
     * Indica si 'SELECT' utilizará 'LIMIT'
     *
     * @var int
     */
    private $limit;

    /**
     * Listado de columnas para hacer 'SELECT COUNT'
     *
     * @var array
     */
    private $counts = [];

    /**
     * Listado de 'JOIN' para hacer 'SELECT'
     *
     * @var array
     */
    private $joins = [];

    /**
     * Array de datos para la consulta 'INSERT'
     *
     * @var array
     */
    private $insert = [];

    /**
     * Array de datos para la consulta 'UPDATE'
     *
     * @var array
     */
    private $update = [];

    /**
     * Listado de condiciones para ejecutar 'SELECT', 'UPDATE' y 'DELETE'
     *
     * @var array
     */
    private $wheres = [];

    // TODO: documentar
    /*
    private $groupBy = [];
    */
    /**
     * Listado de comandos 'ORDER BY'
     *
     * @var array
     */
    private $orderBy = [];

    /**
     * Builder constructor.
     *
     * @param PDO $pdo Instancia de conexión a la BDD
     * @param Grammar|null $grammar Instancia de gramática de motor de BDD
     */
    public function __construct(PDO $pdo, Grammar $grammar = null)
    {
        parent::__construct($pdo);

        // TODO: cambiar por algo más robusto
        $this->grammar = $grammar ?: GrammarHandler::create(
            getenv('QB_DEFAULT_DRIVER') ?: 'sqlite'
        );
    }

    /**
     * Ejecuta la consulta SQL según el tipo de dicha consulta
     *
     * @param string|null $fetchMode Modo de obtención de datos según PDO
     *
     * @return object|array|null
     */
    public function execute(string $fetchMode = null)
    {
        // $this->checkTableName();

        $returnData = null;

        // TODO: binding para todas las operaciones

        switch ($this->type) {
            case Query::INSERT:
                $returnData = $this->exec($this->getInsertSql(), $this->insert);
                break;
            case Query::UPDATE:
                $returnData = $this->exec($this->getUpdateSql(false), $this->update);
                break;
            case Query::DELETE:
                $returnData = $this->exec($this->getDeleteSql(false));
                break;
            case Query::SELECT:
            default:
                $returnData = $this->query($this->getSelectSql(count($this->counts) > 0, false), $fetchMode);
                break;
        }

        $this->cleanBuilder();

        return $returnData;
    }

    /**
     * Prepara una consulta para obtener el primer resultado
     * TODO: debe limpiar
     * TODO testear mejor!!
     *
     * @param string|null $fetchMode Modo de obtención de datos según PDO
     *
     * @return object|array|null
     */
    public function first(string $fetchMode = null)
    {
        $data = $this->query($this->getSelectSql(count($this->counts) > 0, false), $fetchMode);
        $this->cleanBuilder();

        return $data;
    }

    /**
     * Funciona como alias para 'execute', básicamente devuelve resultados en una consulta de tipo SELECT
     *
     * @param string|null $fetchMode Modo de obtención de datos según PDO
     *
     * @return array
     */
    public function get(string $fetchMode = null): array
    {
        $data = $this->query($this->getSelectSql(count($this->counts) > 0, false), $fetchMode);
        $this->cleanBuilder();

        return $data;
    }

    /**
     * Convierte el Query Builder en una consulta SQL
     *
     * @param bool $bind Utilizará binding para la query?
     *
     * @return string
     */
    public function toSql(bool $bind = false): string
    {
        // $this->checkTableName();

        switch ($this->type) {
            case Query::INSERT:
                return $this->getInsertSql($bind);
            case Query::UPDATE:
                return $this->getUpdateSql($bind);
            case Query::DELETE:
                return $this->getDeleteSql($bind);
            case Query::SELECT:
            default:
                return $this->getSelectSql(count($this->counts) > 0, $bind);
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

        if (count($statements) === 0) {
            $statements = ['*'];
        }

        $this->type = Query::SELECT;
        $this->selects = Validator::select($statements);

        return $this;
    }

    /**
     * Asigna el nombre de la tabla base para el builder, funciona para hacer una query más semántica en una consulta
     * SELECT y DELETE.
     *
     * @param string $table Nombre de la tabla
     *
     * @return $this
     */
    public function from(string $table): self
    {
        $this->table = $table;

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
        $this->type = Query::SELECT;
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
        $this->type = Query::SELECT;
        $this->distinct = true;

        return $this;
    }

    /**
     * Indica el límite de resultados que se desean obtener con 'SELECT'
     *
     * @param int $limit Límite de resultados
     *
     * @return $this
     */
    public function limit(int $limit): self
    {
        $this->type = Query::SELECT;
        $this->limit = $limit;

        return $this;
    }

    /**
     * Asigna columnas para realizar 'SELECT COUNT'
     *
     * @param mixed $statements,... Declaraciones para realizar 'SELECT COUNT'
     *
     * @return $this
     */
    public function count(string ...$statements): self
    {
        $this->counts = [];

        if (count($statements) === 0) {
            $statements = ['*'];
        }

        $this->type = Query::SELECT;
        $this->counts = Validator::select($statements);

        return $this;
    }

    /**
     * Añade columnas para realizar 'SELECT COUNT'
     *
     * @param mixed $statements,... Declaraciones para realizar 'SELECT COUNT'
     *
     * @return $this
     */
    public function addCount(string ...$statements): self
    {
        $this->type = Query::SELECT;
        $this->counts = array_merge($this->counts, Validator::select($statements));

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
        $this->type = Query::INSERT;
        $this->insert = Validator::insert($values);

        return $this;
    }

    /**
     * Funciona como alias para ejecutar una consulta de tipo insert directamente en una tabla
     *
     * @param string $table
     *
     * @return bool
     */
    public function into(string $table): bool
    {
        $this->table = $table;

        return $this->exec($this->getInsertSql(), $this->insert);
    }

    /**
     * Añade fila para una modificación
     *
     * @param array $values Llave-valor para columna y valor
     *
     * @return $this
     */
    public function update(array $values): self
    {
        $this->type = Query::UPDATE;
        $this->update = Validator::insert($values);

        return $this;
    }

    /**
     * Configura el builder para eliminar columnas
     *
     * @return $this
     */
    public function delete(): self
    {
        $this->type = Query::DELETE;

        return $this;
    }

    /**
     * Añade un 'INNER JOIN' a la consulta 'SELECT'
     *
     * @param string $table Nombre de la tabla para hacer 'JOIN'
     * @param string $condition Condición del 'JOIN'
     *
     * @return $this
     */
    public function join(string $table, string $condition): self
    {
        return $this->baseJoin($table, $condition, Join::INNER);
    }

    /**
     * Añade un 'INNER JOIN' a la consulta 'SELECT'
     *
     * @param string $table Nombre de la tabla para hacer 'JOIN'
     * @param string $condition Condición del 'JOIN'
     *
     * @return $this
     */
    public function innerJoin(string $table, string $condition): self
    {
        return $this->baseJoin($table, $condition, Join::INNER);
    }

    /**
     * Añade un 'LEFT JOIN' a la consulta 'SELECT'
     *
     * @param string $table Nombre de la tabla para hacer 'JOIN'
     * @param string $condition Condición del 'JOIN'
     *
     * @return $this
     */
    public function leftJoin(string $table, string $condition): self
    {
        return $this->baseJoin($table, $condition, Join::LEFT);
    }

    /**
     * Añade un 'RIGHT JOIN' a la consulta 'SELECT'
     *
     * @param string $table Nombre de la tabla para hacer 'JOIN'
     * @param string $condition Condición del 'JOIN'
     *
     * @return $this
     */
    public function rightJoin(string $table, string $condition): self
    {
        return $this->baseJoin($table, $condition, Join::RIGHT);
    }

    /**
     * Añade un 'OUTER JOIN' a la consulta 'SELECT'
     *
     * @param string $table Nombre de la tabla para hacer 'JOIN'
     * @param string $condition Condición del 'JOIN'
     *
     * @return $this
     */
    public function outerJoin(string $table, string $condition): self
    {
        return $this->baseJoin($table, $condition, Join::OUTER);
    }

    /**
     * Define como añadir un 'JOIN' al Builder
     *
     * @param string $table Nombre de la tabla para hacer 'JOIN'
     * @param string $condition Condición del 'JOIN'
     * @param string $type Tipo de join (definidos en su respectiva clase)
     *
     * @return $this
     */
    private function baseJoin(string $table, string $condition, string $type): self
    {
        $this->type = Query::SELECT;

        array_push($this->joins, new Join($table, $condition, $type));

        return $this;
    }

    /**
     * Asigna cláusulas para realizar 'WHERE'
     *
     * @param mixed $clauses,... Cláusulas de 'WHERE'
     *
     * @return $this
     */
    public function where(string ...$clauses): self
    {
        $this->wheres = Validator::where($clauses);

        return $this;
    }

    /**
     * Añade cláusulas para realizar 'WHERE'
     *
     * @param mixed $clauses,... Cláusulas de 'WHERE'
     *
     * @return $this
     */
    public function addWhere(string ...$clauses): self
    {
        $this->wheres = array_merge($this->wheres, Validator::where($clauses));

        return $this;
    }

    /**
     * Añade cláusulas para realizar 'OR WHERE'
     *
     * @param mixed $clauses,... Cláusulas de 'OR WHERE'
     *
     * @throws Exception
     * @return $this
     */
    public function orWhere(string ...$clauses): self
    {
        $this->validateExistingWhere();
        $this->wheres = array_merge($this->wheres, Validator::where($clauses, Where::OR));

        return $this;
    }

    /**
     * Añade cláusulas para realizar 'OR WHERE'
     *
     * @param mixed $clauses,... Cláusulas de 'OR WHERE'
     *
     * @throws Exception
     * @return $this
     */
    public function addOrWhere(string ...$clauses): self
    {
        $this->validateExistingWhere();
        $this->wheres = array_merge($this->wheres, Validator::where($clauses, Where::OR));

        return $this;
    }

    /*
    public function groupBy(): self
    {
        return $this;
    }
    */

    /**
     * Asigna comandos 'ORDER BY' a la consulta
     *
     * @param string ...$commands Comandos 'ORDER BY' a añadir
     *
     * @return $this
     */
    public function orderBy(string ...$commands): self
    {
        $this->orderBy = Validator::orderBy($commands);

        return $this;
    }

    /**
     * Añade comandos 'ORDER BY' a la consulta
     *
     * @param string ...$commands Comandos 'ORDER BY' a añadir
     *
     * @throws Exception
     * @return $this
     */
    public function addOrderBy(string ...$commands): self
    {
        $this->validateExistingOrderBy();
        $this->orderBy = array_merge($this->orderBy, Validator::orderBy($commands));

        return $this;
    }

    /**
     * Obtiene el nombre de la tabla
     *
     * @return string|null
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Añade o modifica el nombre de la tabla base para el builder
     *
     * @param string $table Nombre de la tabla
     *
     * @return $this
     */
    public function setTable(string $table): self
    {
        $this->table = $table;

        return $this;
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
     * Obtiene la verificación para usar 'DISTINCT'
     *
     * @return bool
     */
    public function getDistinct(): bool
    {
        return $this->distinct;
    }

    /**
     * Obtiene listado de columnas para 'SELECT COUNT'
     *
     * @return array
     */
    public function getCounts(): array
    {
        return $this->counts;
    }

    /**
     * Obtiene listado de filas para 'INSERT'
     *
     * @return array
     */
    public function getInsert(): array
    {
        return $this->insert;
    }

    /**
     * Obtiene listado de filas para 'UPDATE'
     *
     * @return array
     */
    public function getUpdate(): array
    {
        return $this->update;
    }

    /**
     * Obtiene listado de clásusulas 'JOIN'
     *
     * @return array
     */
    public function getJoins(): array
    {
        return $this->joins;
    }

    /**
     * Obtiene listado de cláusulas 'WHERE'
     *
     * @return array
     */
    public function getWheres(): array
    {
        return $this->wheres;
    }

    /**
     * Obtiene listado de comandos 'ORDER BY
     *
     * @return array
     */
    public function getOrderBy(): array
    {
        return $this->orderBy;
    }

    /**
     * Crea una nueva instancia de Query Builder
     * TODO: no está totalmente estable, se usa solo en docs por ahora
     *
     * @param string $table Nombre de la tabla
     *
     * @return self
     */
    public static function table(string $table): self
    {
        $builder = new self();
        $builder->setTable($table);

        return $builder;
    }

    /**
     * Verifica si tiene nombre de tabla
     *
     * @throws UndefinedTableNameException
     * @return void
     */
    private function checkTableName(): void
    {
        if (!$this->table) {
            throw new UndefinedTableNameException();
        }
    }

    /**
     * Genera consulta SQL para un SELECT
     *
     * @param bool $count Verifica si debe preparar la consulta para un 'COUNT'
     * @param bool $bind Utilizará binding para la query?
     *
     * @return string
     */
    private function getSelectSql(bool $count = false, bool $bind = true): string
    {
        $query = '';

        $query .= $count ? $this->grammar->count(
            $this->table,
            $this->counts,
            $this->distinct
        ) : $this->grammar->select(
            $this->table,
            $this->selects,
            $this->distinct
        );

        // añade 'JOIN'
        if (count($this->joins) > 0) {
            $query .= ' ' . $this->grammar->join($this->joins, $bind);
        }

        // añade cláusulas de 'WHERE'
        if (count($this->wheres) > 0) {
            $query .= ' ' . $this->grammar->where($this->wheres, $bind);
        }

        // añade 'LIMIT'
        if ($this->limit) {
            $query .= $this->grammar->limit($this->limit);
        }

        // añade 'ORDER BY'
        if (count($this->orderBy) > 0) {
            $query .= ' ' . $this->grammar->orderBy($this->orderBy);
        }

        return $query;
    }

    /**
     * Genera consulta SQL para un INSERT
     *
     * @param bool $bind Utilizará binding para la query?
     *
     * @return string
     */
    private function getInsertSql(bool $bind = true): string
    {
        return $this->grammar->insert($this->table, $this->insert, $bind);
    }

    /**
     * Genera consulta SQL para un UPDATE
     *
     * @param bool $bind Utilizará binding para la query?
     *
     * @return string
     */
    private function getUpdateSql(bool $bind = true): string
    {
        $query = $this->grammar->update($this->table, $this->update, $bind);

        // añade cláusulas de 'WHERE'
        if (count($this->wheres) > 0) {
            $query .= ' ' . $this->grammar->where($this->wheres, $bind);
        }

        return $query;
    }

    /**
     * Genera consulta SQL para un DELETE
     *
     * @param bool $bind Utilizará binding para la query?
     *
     * @return string
     */
    private function getDeleteSql(bool $bind = true): string
    {
        $query = 'DELETE FROM ' . $this->table;

        // añade cláusulas de 'WHERE'
        if (count($this->wheres) > 0) {
            $query .= ' ' . $this->grammar->where($this->wheres, $bind);
        }

        return $query;
    }

    /**
     * Limpia los campos del query builder para volver a utilizar la instancia en otra consulta
     */
    private function cleanBuilder(): void
    {
        $this->type = Query::SELECT;
        $this->selects = [];
        $this->distinct = false;
        // $this->limit;
        $this->counts = [];
        $this->insert = [];
        $this->update = [];
        $this->wheres = [];
        $this->orderBy = [];
    }

    /**
     * Valida que existan cláusulas 'WHERE' añadidas
     *
     * @throws Exception
     * @return void
     */
    private function validateExistingWhere(): void
    {
        if (count(array_filter($this->wheres)) === 0) {
            throw new Exception();
        }
    }

    /**
     * Valida que existan comandos 'ORDER BY' añadidas
     *
     * @throws Exception
     * @return void
     */
    private function validateExistingOrderBy(): void
    {
        if (count(array_filter($this->orderBy)) === 0) {
            throw new Exception();
        }
    }
}
