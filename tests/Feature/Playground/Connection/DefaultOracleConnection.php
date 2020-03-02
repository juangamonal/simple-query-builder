<?php

namespace QueryBuilder\Tests;

use PDO;

/**
 * Class DefaultOracleConnection
 *
 * @package QueryBuilder\Tests
 * @author Juan Gamonal H <juangamonalh@gmail.com>
 */
class DefaultOracleConnection extends PDO
{
    /**
     * DefaultOracleConnection constructor.
     */
    public function __construct()
    {
        // TODO: parametrizar
        $dsn = 'oci:dbname=int-oraculo.ucsc.cl/integra';
        $user = 'FORMACION';
        $pass = 'najHetOy';

        parent::__construct($dsn, $user, $pass);

        // $default = getenv('QB_DEFAULT_ENGINE');
        // $this->engine = $default ?: Engine::MYSQL;
    }
}
