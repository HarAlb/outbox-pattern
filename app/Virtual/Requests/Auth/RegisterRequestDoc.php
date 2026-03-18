<?php

declare(strict_types=1);

namespace App\Virtual\Requests\Auth;

use OpenApi\Attributes as OAT;

#[OAT\Schema(
    title: 'Register Request',
    required: ['email', 'name', 'password', 'password_confirmation']
)]
final class RegisterRequestDoc
{
    #[OAT\Property(description: 'Name', example: 'Test')]
    public string $name;

    #[OAT\Property(description: 'Surname', example: 'Test', nullable: true)]
    public ?string $surname;

    #[OAT\Property(description: 'Email', example: 'dani@gmail.com')]
    public string $email;

    #[OAT\Property(description: 'Пароль', example: 'password')]
    public string $password;

    #[OAT\Property(description: 'Пароль', example: 'password')]
    public string $password_confirmation;
}
