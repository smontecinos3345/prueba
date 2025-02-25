<?php

namespace App\Domain\Event;

use App\Domain\Entity\User;

class UserRegisteredEvent
{
    public function __construct(public readonly User $user) {}
}
