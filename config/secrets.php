<?php

return [
    'db' => [
        'driver'   => 'pdo_mysql',
        'host'     => getenv('MYSQL_HOST') ?: 'localhost',
        'port'     => getenv('MYSQL_PORT') ?: 3306,
        'dbname'   => getenv('MYSQL_DATABASE') ?: 'my_database',
        'user'     => getenv('MYSQL_USER') ?: 'my_user',
        'password' => getenv('MYSQL_PASSWORD') ?: 'my_password',
        'charset'  => 'utf8mb4',
    ],
];
