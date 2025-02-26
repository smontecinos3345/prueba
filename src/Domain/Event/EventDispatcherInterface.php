<?php

namespace App\Domain\Event;

interface EventDispatcherInterface
{
    public function dispatch(object $event): void;
}
