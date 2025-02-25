<?php

namespace App;

class EventDispatcher
{
    public function __construct(
        private array $handlers,
    ) {}

    public function dispatch($event)
    {
        $type = get_class($event);

        if (!isset($this->handlers[$type])) {
            return;
        }

        return $this->handlers[$type]($event);
    }

    public function addHandler($class, $handler)
    {
        $this->handlers[$class] = $handler;
        return $this;
    }
}
