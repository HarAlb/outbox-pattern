<?php

declare(strict_types=1);

namespace Src\Infrastructure\Auth;

use Src\Domain\User\Entities\User;
use Tymon\JWTAuth\Contracts\JWTSubject;

final class JwtUserAdapter implements JWTSubject
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    #[\Override]
    public function getJWTIdentifier()
    {
        return $this->user->getId();
    }

    #[\Override]
    public function getJWTCustomClaims(): array
    {
        return [
            'email' => $this->user->getEmail(),
        ];
    }
}
