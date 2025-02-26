<?php

namespace Tests\Unit\Application\UseCase;

use App\Application\Request\RegisterUserRequest;
use App\Application\UseCase\RegisterUserUseCase;
use App\Domain\Entity\User;
use App\Domain\Event\EventDispatcherInterface;
use App\Domain\Event\UserRegisteredEvent;
use App\Domain\Exception\UserAlreadyExistsException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\UserId;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Application\UseCase\RegisterUserUseCase
 */
class RegisterUserUseCaseTest extends TestCase
{
    private function createMockRepository(): UserRepositoryInterface
    {
        $storage = [];
        $mock = $this->createMock(UserRepositoryInterface::class);

        $createFinder = function ($test) use (&$storage) {
            return function ($arg) use ($test, &$storage) {
                foreach ($storage as $user) {
                    if ($test($user, $arg)) {
                        return $user;
                    }
                }
                return null;
            };
        };

        $mock->method('findByEmail')
            ->willReturnCallback($createFinder(fn($user, $email) => (string)$user->email === (string)$email));

        $mock->method('save')->willReturnCallback(function (User $user) use (&$storage) {
            $user = $user->withId(new UserId(uniqid()));
            $storage[] = $user;
        });

        return $mock;
    }

    public function testDuplicateEmailThrowsException(): void
    {
        $repository = $this->createMockRepository();
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $useCase = new RegisterUserUseCase($repository, $dispatcher);

        $request = new RegisterUserRequest('foo123', 'foo.smith@email.com', 'P@ssw0rd!');
        $useCase->handle($request);

        $this->expectException(UserAlreadyExistsException::class);
        $useCase->handle($request);
    }

    public function testEventIsDispatchedOnUserRegistration(): void
    {
        $repository = $this->createMockRepository();
        $dispatcher = $this->createMock(EventDispatcherInterface::class);

        $dispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(UserRegisteredEvent::class));

        $useCase = new RegisterUserUseCase($repository, $dispatcher);

        $request = new RegisterUserRequest('foo123', 'foo.smith@email.com', 'P@ssw0rd!');
        $useCase->handle($request);
    }
}
