<?php

declare(strict_types=1);

namespace App\Virtual\Requests\Auth;

use OpenApi\Attributes as OAT;

#[OAT\Schema(
    title: 'Login Request',
    required: ['email', 'password']
)]
final class LoginRequestDoc
{
    #[OAT\Property(description: 'Email', example: 'dani@gmail.com')]
    public string $email;

    #[OAT\Property(description: 'Пароль', example: 'password')]
    public string $password;
}
