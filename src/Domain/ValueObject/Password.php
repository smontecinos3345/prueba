<?php

namespace App\Domain\ValueObject;

class Password
{
    private string $hash;
    private string $salt;

    private function __construct(string $salt, string $hash)
    {
        $this->salt = $salt;
        $this->hash = $hash;
    }

    public static function create(string $plainPassword, ?string $salt = null): self
    {
        $salt = $salt ?? bin2hex(random_bytes(16));
        $hash = hash('sha256', $salt . $plainPassword);
        return new self($salt, $hash);
    }

    public function verify(string $plainPassword): bool
    {
        $hashed = hash('sha256', $this->salt . $plainPassword);
        return hash_equals($this->hash, $hashed);
    }

    public function __toString(): string
    {
        return $this->salt . $this->hash;
    }

    public function getSalt(): string
    {
        return $this->salt;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public static function fromHash(string $storedHash): self
    {
        $salt = substr($storedHash, 0, 32);
        $hash = substr($storedHash, 32);
        return new self($salt, $hash);
    }
}
