<?php

namespace Tests\Unit\Domain\ValueObject;

use App\Domain\Exception\InputTooLongException;
use App\Domain\ValueObject\Name;
use App\Domain\Exception\InvalidNameException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Domain\ValueObject\Name
 */
class NameTest extends TestCase
{
    public function testValidName()
    {
        $name = new Name('John_Doe');
        $this->assertSame('John_Doe', (string) $name);
    }

    public function testValidNameWithDot()
    {
        $name = new Name('john.doe');
        $this->assertSame('john.doe', (string) $name);
    }

    public function testValidNameWithNumbers()
    {
        $name = new Name('user123');
        $this->assertSame('user123', (string) $name);
    }

    public function testInvalidNameTooShort()
    {
        $this->expectException(InvalidNameException::class);
        new Name('abc');
    }

    public function testInvalidNameWithSpaces()
    {
        $this->expectException(InvalidNameException::class);
        new Name('John Doe');
    }

    public function testInvalidNameWithSpecialCharacters()
    {
        $this->expectException(InvalidNameException::class);
        new Name('john@doe');
    }

    public function testInvalidNameWithSymbols()
    {
        $this->expectException(InvalidNameException::class);
        new Name('john#doe');
    }

    public function testRejectsLongUserNames()
    {
        $this->expectException(InputTooLongException::class);
        $name = 'f' . str_repeat('o', 100) . '_user';
        new Name($name);
    }
}
