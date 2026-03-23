<?php

declare(strict_types=1);

namespace Src\Infrastructure\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Src\Domain\Common\ValueObject\Id;
use Src\Domain\User\Repositories\UserRepository;

class DomainUserProvider implements UserProvider
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    #[\Override]
    public function retrieveById($identifier): ?Authenticatable
    {
        $user = $this->userRepository->findById(new Id((int) $identifier));

        if (! $user) {
            return null;
        }

        return new JwtUserAdapter($user);
    }

    #[\Override]
    public function retrieveByToken($identifier, $token): ?Authenticatable
    {
        return null;
    }

    #[\Override]
    public function updateRememberToken(Authenticatable $user, $token): void
    {
        // Не используется
    }

    #[\Override]
    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        return null;
    }

    #[\Override]
    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        return false;
    }

    #[\Override]
    public function rehashPasswordIfRequired(
        Authenticatable $user,
        #[\SensitiveParameter] array $credentials,
        bool $force = false
    ) {}
}
