<?php

namespace App\Domain\ValueObject;

use App\Domain\Exception\WeakPasswordException;

class Password
{

    const MIN_LENGTH = 8;

    private string $hash;
    private string $salt;

    private function __construct(string $salt, string $hash)
    {
        $this->salt = $salt;
        $this->hash = $hash;
    }

    private static function checkPasswordStrength(string $password)
    {
        if (strlen($password) < self::MIN_LENGTH) {
            throw new WeakPasswordException('password is too short!');
        }

        $tieneMayuscula = false;
        $tieneNumero = false;
        $tieneEspecial = false;

        for ($i = 0; $i < strlen($password); $i++) {
            $char = $password[$i];

            if (ctype_upper($char)) {
                $tieneMayuscula = true;
            } elseif (ctype_digit($char)) {
                $tieneNumero = true;
            } elseif (!ctype_alnum($char)) {
                $tieneEspecial = true;
            }

            if ($tieneMayuscula && $tieneNumero && $tieneEspecial) {
                return true;
            }
        }
        throw new WeakPasswordException('Password must contain at least one special character, one capital letter and a number');
    }

    public static function create(string $plainPassword, ?string $salt = null): self
    {

        self::checkPasswordStrength($plainPassword);

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
