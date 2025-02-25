<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\UserId;
use App\Domain\ValueObject\Password;
use DateTimeImmutable;

class User
{

    public function __construct(
        public readonly Name $name,
        public readonly Email $email,
        public readonly Password $password,
        public readonly ?UserId $userId = null,
        public readonly ?DateTimeImmutable $createdAt = null,
    ) {}

    public function withId(UserId $newId): self
    {
        return new self(
            $this->name,
            $this->email,
            $this->password,
            $newId,
            $this->createdAt
        );
    }

    public function withCreationDate(DateTimeImmutable $dateTimeImmutable)
    {
        return new self(
            $this->name,
            $this->email,
            $this->password,
            $this->userId,
            $dateTimeImmutable,
        );
    }
}
