<?php

use App\Adapter\Out\Persistence\DoctrineUserRepository;
use App\Container;
use App\Domain\Event\UserRegisteredEvent;
use App\Domain\Event\UserRegisteredEventHandler;
use App\Domain\Repository\UserRepositoryInterface;
use App\EventDispatcher;
use Doctrine\DBAL\DriverManager;

$secrets = require_once __DIR__ . '/secrets.php';

function createEventHandler(string $class, Container $container)
{
    return function ($event) use ($container, $class) {
        $handler = $container->get($class);
        return $handler->handle($event);
    };
};

$handlers = [];

return [
    UserRepositoryInterface::class => function (Container $container) use ($secrets) {


        $connection = DriverManager::getConnection([
            'dbname'   => $secrets['db']['dbname'],
            'user'     => $secrets['db']['user'],
            'password' => $secrets['db']['password'],
            'host'     => $secrets['db']['host'],
            'driver'   => 'pdo_mysql',
        ]);
        return new DoctrineUserRepository($connection);
    },
    EventDispatcher::class => function (Container $container) {
        return new EventDispatcher([
            UserRegisteredEvent::class => createEventHandler(UserRegisteredEventHandler::class, $container),
        ]);
    }
];
