<?php

declare(strict_types=1);

namespace App\Virtual\Responses\Auth;

use OpenApi\Attributes as OAT;

#[
    OAT\Schema(
        title: 'Auth Response',
    )
]
final class AuthResponseDoc
{
    #[OAT\Property()]
    public UserResponseDoc $user;

    #[OAT\Property()]
    public TokenResponseDoc $token;
}
