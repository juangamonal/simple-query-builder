<?php

namespace QueryBuilder;

use Exception;
use PDO;
use QueryBuilder\Exceptions\UndefinedTableNameException;
use QueryBuilder\Grammars\GrammarHandler;
use QueryBuilder\Syntax\Validator;
use QueryBuilder\Types\Query;
use QueryBuilder\Types\Where;

/**
 * Class Builder
 *
 * @package QueryBuilder
 * @author Juan Gamonal H <juangamonal@gmail.com>
 */
final class Builder
{
    /**
     * Tipo de Query según las constantes de arriba
     *
     * @var int
     */
    private $type = Query::SELECT;

    /**
     * Instancia de conexión a la base de datos
     *
     * @var PDO
     */
    private $pdo;

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
    /*
    private $joins = [];
    */

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
    private $orderBy = [];
    */

    /**
     * Builder constructor.
     *
     * @param PDO|null $pdo Instancia de conexión a la BDD
     * @param Grammar|null $grammar Instancia de gramática de motor de BDD
     */
    public function __construct(PDO $pdo = null, Grammar $grammar = null)
    {
        $this->pdo = $pdo;
        $this->grammar = $grammar ?: GrammarHandler::create(
            getenv('QB_DEFAULT_DRIVER') ?: 'sqlite'
        );
    }

    /**
     * Ejecuta la consulta SQL según el tipo de dicha consulta
     *
     * @throws UndefinedTableNameException
     * @return array|object
     */
    public function execute()
    {
        $this->checkTableName();
        $this->checkConnection();

        switch ($this->type) {
            case Query::INSERT:
                // TODO: retornar resultado de operación?
                $this->pdo->prepare($this->getInsertSql())
                    ->execute($this->insert);

                break;
            case Query::UPDATE:
                // TODO: retornar resultado de operación?
                $this->pdo->prepare($this->getUpdateSql())
                    ->execute($this->update);

                break;
            case Query::DELETE:
                $sql = $this->getDeleteSql();
                break;
            case Query::SELECT:
            default:
                /* TODO: arreglar esto
                $data = [];
                $result = $this->pdo->query(
                    $this->getSelectSql(count($this->counts) > 0)
                );

                while ($r = $result->fetch(PDO::FETCH_OBJ)) {
                    array_push($data, $r);
                }

                return $data;
                */
                return [];
        }

        return [];
    }

    /**
     * Convierte el Query Builder en una consulta SQL
     *
     * @param bool $bind Utilizará binding para la query?
     *
     * @throws UndefinedTableNameException
     * @return string
     */
    public function toSql(bool $bind = false): string
    {
        $this->checkTableName();

        switch ($this->type) {
            case Query::INSERT:
                return $this->getInsertSql($bind);
            case Query::UPDATE:
                return $this->getUpdateSql($bind);
            case Query::DELETE:
                return $this->getDeleteSql();
            case Query::SELECT:
            default:
                return $this->getSelectSql(count($this->counts) > 0);
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
     * Añade un join a la consulta 'SELECT'
     *
     * @param string $table Nombre de la tabla para hacer 'join'
     * @param string $condition Condición del 'join'
     *
     * @return $this
     */
    /*
    public function join(string $table, string $condition): self
    {
        $this->type = Query::SELECT;

        array_push($this->joins, Join::create($table, $condition));

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
    */

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
        // vacía los orWhere existentes
        $this->wheres = array_filter($this->wheres);
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

    public function orderBy(): self
    {
        return $this;
    }
    */

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
     * Obtiene listado de cláusulas 'WHERE'
     *
     * @return array
     */
    public function getWheres(): array
    {
        return $this->wheres;
    }

    /**
     * Crea una nueva instancia de Query Builder
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
     * Verifica si tiene conexión PDO
     *
     * @return void
     */
    private function checkConnection(): void
    {
        if (!$this->pdo) {
            $this->pdo = new DefaultConnection();
        }
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
     *
     * @return string
     */
    private function getSelectSql(bool $count = false): string
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

        // añade cláusulas de 'WHERE'
        if (count($this->wheres) > 0) {
            $query .= ' ' . $this->grammar->where($this->wheres);
        }

        // añade 'LIMIT'
        if ($this->limit) {
            $query .= $this->grammar->limit($this->limit);
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
            $query .= ' ' . $this->grammar->where($this->wheres);
        }

        return $query;
    }

    /**
     * Genera consulta SQL para un DELETE
     *
     * @return string
     */
    private function getDeleteSql(): string
    {
        $query = 'DELETE FROM ' . $this->table;

        // añade cláusulas de 'WHERE'
        if (count($this->wheres) > 0) {
            $query .= ' ' . $this->grammar->where($this->wheres);
        }

        return $query;
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
}
