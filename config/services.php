<?php

// config/services.php

/*
 * Sets up service bindings for dependency injection.
 * 
 * - Wires up UserRepositoryInterface to use Doctrine.
 * - Registers an EventDispatcher with event handlers.
 * - Uses the container to resolve dependencies on the fly.
 */

use App\Adapter\Out\Event\EventDispatcher;
use App\Adapter\Out\Event\UserRegisteredEventHandler;
use App\Adapter\Out\Persistence\DoctrineUserRepository;
use App\Container;
use App\Domain\Event\EventDispatcherInterface;
use App\Domain\Event\UserRegisteredEvent;
use App\Domain\Repository\UserRepositoryInterface;
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
    EventDispatcherInterface::class => function (Container $container) {
        return new EventDispatcher([
            UserRegisteredEvent::class => createEventHandler(UserRegisteredEventHandler::class, $container),
        ]);
    }
];
