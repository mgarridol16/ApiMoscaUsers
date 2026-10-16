<?php

// =================== DATABASE ===================
class Database
{

    public function connect()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            //echo 'Conectando a la base de datos MySQLi...<br>';

            return new mysqli(
                getenv('HOST_DB'),
                getenv('USER_DB'),
                getenv('PASS_DB'),
                getenv('URL_DB'),
                getenv('DB_PORT')
            );


        } catch (mysqli_sql_exception $e) {
            echo 'Error MySQL: ' . $e->getMessage() . '<br>';
            echo 'Código: ' . $e->getCode() . '<br>';
            throw $e;
        }
    }
}
