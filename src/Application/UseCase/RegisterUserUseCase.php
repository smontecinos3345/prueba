<?php

namespace App\Application\UseCase;

use App\Application\Request\RegisterUserRequest;
use App\Domain\Entity\User;
use App\Domain\Event\EventDispatcherInterface;
use App\Domain\Event\UserRegisteredEvent;
use App\Domain\Exception\UserAlreadyExistsException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Name;
use App\Domain\ValueObject\Password;

class RegisterUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepositoryInterface,
        private EventDispatcherInterface $dispatcher,
    ) {}

    public function handle(RegisterUserRequest $registerUserRequest)
    {
        $name = new Name($registerUserRequest->name);
        $email  = new Email($registerUserRequest->email);

        $password = Password::create($registerUserRequest->password);
        $user = new User($name, $email, $password);

        $other = $this->userRepositoryInterface->findByEmail($email);

        if ($other !== null) {
            throw new UserAlreadyExistsException("email is taken");
        }

        $this->userRepositoryInterface->save($user);
        $saved = $this->userRepositoryInterface->findByEmail($email);

        $event = new UserRegisteredEvent($saved);
        $this->dispatcher->dispatch($event);

       return $saved;
    }
}
