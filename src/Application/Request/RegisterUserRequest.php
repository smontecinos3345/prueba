<?php

namespace App\Application\Request;

class RegisterUserRequest
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password
    ) {
    }
}
