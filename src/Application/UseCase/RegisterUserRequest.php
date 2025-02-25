<?php

namespace App\Application\UseCase;

class RegisterUserRequest
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password
    ) {
    }
}
