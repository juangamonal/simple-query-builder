<?php

namespace QueryBuilder;

use PDO;

/**
 * Esta clase funciona como 'puente' entre el Builder y la conexión PDO, principalmente para separar las lógicas de
 * ambas clases.
 *
 * @author Juan Gamonal H <jgamonal@ucsc.cl>
 * @package QueryBuilder
 */
class ConnectionHandler
{
    /**
     * Instancia de conexión a la base de datos
     * TODO: debe ser privado, solo por compatibilidad se deja como publico
     *
     * @var PDO
     */
    private $pdo;

    /**
     * ConnectionHandler constructor.
     *
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Sirve para ejecutar operaciones sobre la base de datos, principalmente usando 'binding'
     *
     * @param string $query La 'query' a ejecutar
     * @param mixed|null $data Los datos necesarios para ejecutar la consulta
     *
     * @return bool
     */
    protected function exec(string $query, $data = null): bool
    {
        return $this->pdo->prepare($query)->execute($data);
    }

    /**
     * Sirve para ejecutar consultas a la base de datos
     *
     * TODO fetch mode
     *
     * @param string $query La consulta a ejecutar
     * @param string|null $fetchMode Modo de obtención de datos según PDO
     * @param bool $unique Este flag si indica si debe obtener los datos como una lista o un objeto único
     *
     * @return array|object|null
     */
    protected function query(string $query, string $fetchMode = null, bool $unique = false)
    {
        var_dump($query);
        $result = $this->pdo->query($query);

        if ($unique === false) {
            if (!$result) {
                return [];
            }

            $data = [];

            while ($r = $result->fetch(PDO::FETCH_OBJ)) {
                array_push($data, $r);
            }

            return $data;
        } else {
            if (!$result) {
                return null;
            }

            return $result->fetch(PDO::FETCH_OBJ);
        }
    }

    /**
     * Obtiene instancia de PDO usaba en Query Builder
     *
     * @deprecated
     * @return PDO
     */
    public function getPDO(): PDO
    {
        return $this->pdo;
    }

    // TODO: transactions...
}
