<?php

namespace Tests;

use PDO;

/**
 * Utilidades para la ejecución de las pruebas
 *
 * @author Juan Gamonal H <jgamonal@ucsc.cl>
 * @package Tests
 */
class Utils
{
    /**
     * Genera una conexión a la base de datos en memoria para ejecutar pruebas. También funciona como 'mock'.
     *
     * @return PDO
     */
    public static function getTestingConnection(): PDO
    {
        return new PDO('sqlite::memory');
    }
}
