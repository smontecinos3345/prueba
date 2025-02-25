<?php

namespace Tests\Unit\Domain\ValueObject;

use App\Domain\ValueObject\Email;
use App\Domain\Exception\InvalidEmailException;
use PHPUnit\Framework\TestCase;


/**
 * @covers \App\Domain\ValueObject\Email
 */
class EmailTest extends TestCase
{
    public function testValidEmail()
    {
        $email = new Email('test@example.com');
        $this->assertSame('test@example.com', (string) $email);
    }

    public function testInvalidEmailThrowsException()
    {
        $this->expectException(InvalidEmailException::class);
        new Email('invalid-email');
    }

    public function testToStringReturnsEmail()
    {
        $email = new Email('user@domain.com');
        $this->assertSame('user@domain.com', (string) $email);
    }
}
