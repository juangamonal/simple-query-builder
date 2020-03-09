<?php

namespace QueryBuilder\Tests\Feature\Playground;

use Faker\Factory;
use QueryBuilder\Builder;
use QueryBuilder\ConnectionBuilder;
use QueryBuilder\Tests\Base;

/**
 * Class BuilderSqlGeneratorTest
 *
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 * @package QueryBuilder\Tests\Feature\Playground
 */
class MySqlTest extends Base
{
    /**
     * MySqlTest constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $host = getenv('QB_MYSQL_HOST');
        $db = getenv('QB_MYSQL_DATABASE');
        $user = getenv('QB_MYSQL_USER');
        $pass = getenv('QB_MYSQL_PASSWORD');


        if (!$host || !$db || !$user || !$pass) {
            // TODO: omitir prueba
        } else {
            $this->conn = ConnectionBuilder::create(
                'mysql',
                $host,
                $db,
                $user,
                $pass
            );
            $this->builder = new Builder($this->conn);
        }
    }

    /**
     * Prueba un select básico
     *
     * @return void
     */
    public function testSelect()
    {
        $users = $this->builder
            ->setTable('users')
            ->where('status = 1')
            ->execute();

        $this->assertCount($this->qty, $users);
    }

    /**
     * Prueba un insert básico
     *
     * @return void
     */
    public function testInsert()
    {
        $faker = Factory::create();
        $this->builder
            ->setTable('users')
            ->insert([
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->email()
            ])
            ->execute();

        $users = $this->builder
            ->select()
            ->execute();

        $this->assertEquals($this->qty + 1, count($users));
    }

    /**
     * Prueba un update básico
     *
     * @return void
     */
    public function testUpdate()
    {
        $this->builder
            ->setTable('users')
            ->update([
                'first_name' => 'modified',
                'last_name' => 'user'
            ])
            ->where('users.id = 1')
            ->execute();

        $user = $this->builder
            ->where("first_name = 'modified'", "last_name = 'user'")
            ->select()
            ->execute();

        $this->assertCount(1, $user);
    }
}
