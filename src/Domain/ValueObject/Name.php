<?php

namespace App\Domain\ValueObject;

use App\Domain\Exception\InputTooLongException;
use App\Domain\Exception\InvalidNameException;

class Name
{
    public readonly string $name;

    public function __construct(string $name)
    {
        $this->checkName($name);
        $this->name = $name;
    }

    private function checkName(string $name): void
    {
        if (strlen($name) < 4) {
            throw new InvalidNameException("name is too short");
        }

        if (strlen($name) >  100) {
            throw new InputTooLongException("name is too long (max 100)");
        }

        if (!preg_match('/^[a-zA-Z0-9._]+$/', $name)) {
            throw new InvalidNameException();
        }
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
