<?php

declare(strict_types=1);

namespace Src\Application\Auth\Responses;

final class UserWithTokenResponse
{
    public function __construct(
        public UserResponse $user,
        public TokenResponse $token,
    ) {}
}
