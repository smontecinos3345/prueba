<?php

namespace App\Application\UseCase;

use DateTimeImmutable;

class UserResponseDTO implements \JsonSerializable
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $email,
        public readonly ?DateTimeImmutable $createdAt,
    ) {}

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'createdAt' => $this->createdAt?->format(DateTimeImmutable::ATOM),
        ];
    }
}
