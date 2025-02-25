<?php

namespace App\Domain\ValueObject;

use App\Domain\Exception\InvalidEmailException;

class Email
{
    const EMAIL_REGEX = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

    public readonly string $email;

    public function __construct($email)
    {
        $this->checkEmail($email);
        $this->email = $email;
    }

    private function checkEmail(string $email)
    {
        if (!preg_match(self::EMAIL_REGEX, $email)) {
            throw new InvalidEmailException();
        }
    }

    public function __toString()
    {
        return $this->email;
    }
}
