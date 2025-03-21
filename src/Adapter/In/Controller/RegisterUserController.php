<?php

namespace App\Adapter\In\Controller;

use App\Adapter\In\Http\Request;
use App\Application\DTO\UserResponseDTO;
use App\Application\Request\RegisterUserRequest;
use App\Application\UseCase\RegisterUserUseCase;

class RegisterUserController
{

    public function __construct(private RegisterUserUseCase $registerUserUseCase) {}

    public function __invoke(Request $request)
    {
        $name = (string)$request->get('name', '');
        $email = (string)$request->get('email', '');
        $password = (string)$request->get('password', '');

        $registerUserRequest = new RegisterUserRequest(
            $name,
            $email,
            $password,
        );

        $saved = $this->registerUserUseCase->handle($registerUserRequest);

        return (new UserResponseDTO(
            (string)$saved->userId,
            (string)$saved->name,
            (string)$saved->email,
            $saved->createdAt,
        ));
    }
}
