<?php

namespace Tests\Integration\Adapter\Out\Persistence;

use App\Adapter\Out\Persistence\DoctrineUserRepository;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\Password;
use PHPUnit\Framework\TestCase;
use Tests\Integration\WithDatabase;

/**
 * @covers \App\Adapter\Out\Persistence\DoctrineUserRepository
 */
class DoctrineUserRepositoryTest extends TestCase
{

    use WithDatabase;

    private DoctrineUserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->getRepository();
        $connection = $this->repository->getConnection();
        $connection->executeStatement('TRUNCATE TABLE users');
    }

    public function testUserIsProperlyPersisted(): void
    {
        $email =  new Email('john@example.com');
        $plainTextPassword = 'P@ssword1!';
        $user = new User(
            new Name('foo1'),
            $email,
            Password::create($plainTextPassword)
        );

        $this->repository->save($user);

        $retrievedUser = $this->repository->findByEmail($email);

        $this->assertNotNull($retrievedUser, 'user should have been stored in db!');
        $this->assertSame((string) $user->name, (string) $retrievedUser->name);
        $this->assertSame((string) $user->email, (string) $retrievedUser->email);
        $this->assertTrue($user->password->verify($plainTextPassword), 'password should match!');
        $this->assertNotNull($retrievedUser->createdAt, 'createdAt is populated in databae!');
    }

    private function getRepository(): UserRepositoryInterface {
        $connection = $this->getConnection();
        return new DoctrineUserRepository($connection);
    }
}
