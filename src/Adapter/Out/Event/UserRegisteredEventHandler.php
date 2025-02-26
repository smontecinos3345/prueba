<?php

namespace App\Adapter\Out\Event;

use App\Domain\Event\UserRegisteredEvent;

class UserRegisteredEventHandler
{
    public function __construct() {}

    public function handle(UserRegisteredEvent $userRegisteredEvent)
    {
    }
}
