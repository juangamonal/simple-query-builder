<?php

namespace QueryBuilder\Tests\Unit;

use PHPUnit\Framework\TestCase;
use QueryBuilder\Grammar;
use QueryBuilder\Grammars\MySqlGrammar;
use QueryBuilder\Syntax\Join;
use QueryBuilder\Types\OrderBy;
use QueryBuilder\Types\Where;

/**
 * Class GrammarTest
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Tests
 */
final class GrammarTest extends TestCase
{
    /**
     * Instancia de grammar para pruebas
     *
     * @var Grammar
     */
    private $grammar;

    /**
     * GrammarTest constructor.
     *
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->grammar = new MySqlGrammar();
    }

    /**
     * Prueba el método ->select()
     *
     * @return void
     */
    public function testSelect()
    {
        // prepara un 'SELECT' sencillo
        $statements = ['email', 'name as full_name'];
        $select = 'SELECT email, name AS full_name FROM users';
        $this->assertEquals($select, $this->grammar->select('users', $statements));

        // prepara un 'SELECT' con 'DISTINCT'
        $statements = ['email as user_email', 'name', 'status'];
        $select = 'SELECT DISTINCT email AS user_email, name, status FROM users';
        $this->assertEquals($select, $this->grammar->select('users', $statements, true));
    }

    /**
     * Prueba el método ->count()
     *
     * @return void
     */
    public function testCount()
    {
        // prepara un 'COUNT' sencillo
        $statements = ['email', 'name as full_name'];
        $select = 'SELECT COUNT(email), COUNT(name) AS full_name FROM users';
        $this->assertEquals($select, $this->grammar->count('users', $statements));

        // prepara un 'COUNT' con 'DISTINCT'
        $statements = ['email as user_email', 'name', 'status'];
        $select = 'SELECT DISTINCT COUNT(email) AS user_email, COUNT(name), COUNT(status) FROM users';
        $this->assertEquals($select, $this->grammar->count('users', $statements, true));
    }

    /**
     * Prueba el método ->insert()
     *
     * @return void
     */
    public function testInsert()
    {
        // insert básico
        $sql = "INSERT INTO users (first_name, status) VALUES ('foo bar', 1)";
        $data = [
            'first_name' => 'foo bar',
            'status' => 1
        ];

        $this->assertEquals($sql, $this->grammar->insert('users', $data, false));

        // TODO: probar con demás tipos de datos
    }

    /**
     * Prueba el método ->insert() con binding
     *
     * @return void
     */
    public function testInsertBind()
    {
        // insert básico
        $sql = "INSERT INTO users (first_name, status) VALUES (:first_name, :status)";
        $data = [
            'first_name' => 'foo bar',
            'status' => 1
        ];

        $this->assertEquals($sql, $this->grammar->insert('users', $data));

        // TODO: probar con demás tipos de datos
    }

    /**
     * Prueba el método ->update()
     *
     * @return void
     */
    public function testUpdate()
    {
        // insert básico
        $sql = "UPDATE users SET status = 0, email = 'foo@bar'";
        $data = [
            'status' => 0,
            'email' => 'foo@bar'
        ];

        $this->assertEquals($sql, $this->grammar->update('users', $data, false));

        // TODO: probar con demás tipos de datos
    }

    /**
     * Prueba el método ->update() con binding
     *
     * @return void
     */
    public function testUpdateBinding()
    {
        // insert básico
        $sql = "UPDATE users SET age = :age, email = :email";
        $data = [
            'age' => 25,
            'email' => 'foo@bar'
        ];

        $this->assertEquals($sql, $this->grammar->update('users', $data));

        // TODO: probar con demás tipos de datos
    }

    /**
     * Prueba el método ->join()
     *
     * @return void
     */
    public function testJoin()
    {
        // inner join
        $data = [new Join('users', 'users.id = posts.owner_id', Join::INNER)];
        $join = 'INNER JOIN users ON users.id = posts.owner_id';
        $this->assertEquals($join, $this->grammar->join($data, false));

        // TODO: test demás tipos de join
    }

    // TODO
    public function testJoinBinding()
    {

    }

    /**
     * Prueba el método ->where()
     *
     * @return void
     */
    public function testWhere()
    {
        // where básico
        $data = ['status = 1' => Where::AND];
        $where = 'WHERE status = 1';
        $this->assertEquals($where, $this->grammar->where($data, false));

        // where con múltiples 'AND'
        $data = ['status = 1' => Where::AND, 'age < 18' => Where::AND, 'age > 0' => Where::AND];
        $where = 'WHERE status = 1 AND age < 18 AND age > 0';
        $this->assertEquals($where, $this->grammar->where($data, false));

        // where y 'OR' where
        $data = ['status = 1' => Where::AND, 'deleted = 0' => Where::OR];
        $where = 'WHERE status = 1 OR deleted = 0';
        $this->assertEquals($where, $this->grammar->where($data, false));
    }

    /**
     * Prueba el método ->where() con binding
     *
     * @return void
     */
    public function testWhereBinding()
    {
        // where básico
        $data = ['status = 1' => Where::AND];
        $where = 'WHERE status = :status';
        $this->assertEquals($where, $this->grammar->where($data));

        // where con múltiples 'AND'
        $data = ['status = 1' => Where::AND, 'age < 18' => Where::AND, 'name like juan' => Where::AND];
        $where = 'WHERE status = :status AND age < :age AND name like :name';
        $this->assertEquals($where, $this->grammar->where($data));

        // TODO: probar con múltiple binding
        // $data = ['status = 1' => Where::AND, 'age < 18' => Where::AND, 'age > 0' => Where::AND];
        // $where = 'WHERE status = :status AND age < :age AND age > :age2';
        // $this->assertEquals($where, $this->grammar->where($data));

        // where y 'OR' where
        $data = ['status = 1' => Where::AND, 'deleted = 0' => Where::OR];
        $where = 'WHERE status = :status OR deleted = :deleted';
        $this->assertEquals($where, $this->grammar->where($data));
    }

    /**
     * Prueba el método ->orderBy()
     *
     * @return void
     */
    public function testOrderBy()
    {
        // 'ORDER BY' básico
        $data = ['age' => OrderBy::ASC];
        $orderBy = 'ORDER BY age ASC';
        $this->assertEquals($orderBy, $this->grammar->orderBy($data));

        // mútiples 'ORDER BY'
        $data = ['age' => OrderBy::ASC, 'status' => OrderBy::DESC];
        $orderBy = 'ORDER BY age ASC, status DESC';
        $this->assertEquals($orderBy, $this->grammar->orderBy($data));
    }
}
