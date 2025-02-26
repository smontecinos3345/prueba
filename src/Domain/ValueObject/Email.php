<?php

namespace App\Domain\ValueObject;

use App\Domain\Exception\InputTooLongException;
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

        if (strlen($email) > 100) {
            throw new InputTooLongException("password is too long!");
        }
    }

    public function __toString()
    {
        return $this->email;
    }
}
