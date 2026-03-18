<?php

declare(strict_types=1);

namespace App\Virtual\Responses\Auth;

use OpenApi\Attributes as OAT;

#[
    OAT\Schema(
        title: 'User Response',
    )
]
final class UserResponseDoc
{
    #[
        OAT\Property(
            example: 1
        )
    ]
    public int $id;

    #[OAT\Property(description: 'Name', example: 'Test')]
    public string $name;

    #[OAT\Property(description: 'Email', example: 'dani@gmail.com')]
    public string $email;

    #[OAT\Property(description: 'Surname', example: 'Some', nullable: true)]
    public ?string $surname;
}
