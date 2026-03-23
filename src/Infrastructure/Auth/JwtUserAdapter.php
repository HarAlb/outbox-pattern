<?php

declare(strict_types=1);

namespace Src\Infrastructure\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Src\Domain\User\Entities\User;
use Tymon\JWTAuth\Contracts\JWTSubject;

final class JwtUserAdapter implements Authenticatable, JWTSubject
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    #[\Override]
    public function getJWTIdentifier()
    {
        return $this->user->getId()->getValue();
    }

    #[\Override]
    public function getJWTCustomClaims(): array
    {
        return [
            'email' => $this->user->getEmail(),
        ];
    }

    #[\Override]
    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    #[\Override]
    public function getAuthPassword(): string
    {
        return '';
    }

    #[\Override]
    public function getRememberToken(): string
    {
        return '';
    }

    #[\Override]
    public function setRememberToken($value): void {}

    #[\Override]
    public function getRememberTokenName(): string
    {
        return '';
    }

    // Геттер для доступа к доменному пользователю
    public function getDomainUser(): User
    {
        return $this->user;
    }

    #[\Override]
    public function getAuthIdentifier()
    {
        return $this->user->getId()->getValue();
    }

    #[\Override]
    public function getAuthPasswordName()
    {
        return 'password';
    }
}
