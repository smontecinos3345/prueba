<?php

namespace Tests\Unit\Domain\ValueObject;

use App\Domain\ValueObject\Password;
use App\Domain\Exception\WeakPasswordException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Domain\ValueObject\Password
 */
class PasswordTest extends TestCase
{
    public function testValidPassword()
    {
        $password = Password::create('StrongP@ss1');
        $this->assertInstanceOf(Password::class, $password);
    }

    public function testWeakPasswordThrowsException()
    {
        $this->expectException(WeakPasswordException::class);
        Password::create('weak');
    }

    public function testVerifyCorrectPassword()
    {
        $password = Password::create('StrongP@ss1');
        $this->assertTrue($password->verify('StrongP@ss1'));
    }

    public function testVerifyIncorrectPassword()
    {
        $password = Password::create('StrongP@ss1');
        $this->assertFalse($password->verify('WrongPassword'));
    }

    public function testToString()
    {
        $password = Password::create('StrongP@ss1');
        $this->assertStringContainsString($password->getSalt(), (string) $password);
        $this->assertStringContainsString($password->getHash(), (string) $password);
    }

    public function testFromHash()
    {
        $password = Password::create('StrongP@ss1');
        $reconstructed = Password::fromHash((string) $password);
        
        $this->assertSame($password->getSalt(), $reconstructed->getSalt());
        $this->assertSame($password->getHash(), $reconstructed->getHash());
        $this->assertTrue($reconstructed->verify('StrongP@ss1'));
    }
}
