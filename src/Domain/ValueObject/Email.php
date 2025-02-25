<?php

namespace App\Domain\ValueObject;

class Email
{
    public readonly string $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function __toString()
    {
        return $this->email;
    }
}
