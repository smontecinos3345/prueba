<?php

namespace Tests\Integration;

use Doctrine\DBAL\DriverManager;

trait WithDatabase
{
    public function getConnection()
    {
        $parameters = [
            'driver'   => 'pdo_mysql',
            'host'     => getenv('MYSQL_HOST') ?: 'localhost',
            'port'     => getenv('MYSQL_PORT') ?: 3306,
            'dbname'   => getenv('MYSQL_DATABASE') ?: 'my_database',
            'user'     => getenv('MYSQL_USER') ?: 'my_user',
            'password' => getenv('MYSQL_PASSWORD') ?: 'my_password',
            'charset'  => 'utf8mb4',
        ];

        $connection = DriverManager::getConnection($parameters);
        return $connection;
    }
}
