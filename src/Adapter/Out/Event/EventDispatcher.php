<?php

namespace App\Adapter\Out\Event;

use App\Domain\Event\EventDispatcherInterface;

class EventDispatcher implements EventDispatcherInterface
{
    public function __construct(
        private array $handlers,
    ) {}

    public function dispatch(object $event): void
    {
        $type = get_class($event);

        if (!isset($this->handlers[$type])) {
            return;
        }

        $this->handlers[$type]($event);
    }

    public function addHandler($class, $handler)
    {
        $this->handlers[$class] = $handler;
        return $this;
    }
}
