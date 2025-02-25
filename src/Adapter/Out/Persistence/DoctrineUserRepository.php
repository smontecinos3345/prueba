<?php

namespace App\Adapter\Out\Persistence;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\UserId;
use App\Domain\ValueObject\Password;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private Connection $connection
    ) {}

    public function save(User $user): void
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->insert('users')
            ->values([
                'name' => ':name',
                'email' => ':email',
                'password_hash' => ':hash',
            ])
            ->setParameter('id', (string) $user->userId)
            ->setParameter('email', (string) $user->email)
            ->setParameter('name', (string)$user->name)
            ->setParameter('hash', $user->password->getHash());

        try {
            $qb->executeStatement();
        } catch (Exception $e) {
            throw new \RuntimeException("Failed to save user", 0, $e);
        }
    }

    public function findById(UserId $id): ?User
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from('users')
            ->where('id = :id')
            ->setParameter('id', (string) $id);

        $row = $qb->executeQuery()->fetchAssociative();

        return $row ? $this->hydrateUser($row) : null;
    }

    public function findByEmail(Email $email): ?User
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from('users')
            ->where('email = :email')
            ->setParameter('email', (string) $email);

        $row = $qb->executeQuery()->fetchAssociative();

        return $row ? $this->hydrateUser($row) : null;
    }

    public function delete(UserId $id): void
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->delete('users')
            ->where('id = :id')
            ->setParameter('id', (string) $id);

        $qb->executeStatement();
    }

    private function hydrateUser(array $row): User
    {
        return new User(
            new Name($row['name']),
            new Email($row['email']),
            Password::fromHash($row['password_hash']),
            userId: new UserId($row['id']),
            createdAt: !empty($row['created_at']) ? new \DateTimeImmutable($row['created_at']) : null,
        );
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }
}
