<?php

namespace Src\Application\Auth\UseCases;

use Src\Application\Auth\Contracts\TokenServiceInterface;
use Src\Application\Auth\Exceptions\InvalidCredentialsException;
use Src\Application\Auth\LoginCommand;
use Src\Application\Auth\Responses\UserResponse;
use Src\Application\Auth\Responses\UserWithTokenResponse;
use Src\Domain\User\Entities\ValueObject\Email;
use Src\Domain\User\Repositories\UserRepository;

final class LoginUser
{
    public function __construct(
        private UserRepository $users,
        private TokenServiceInterface $tokens,
    ) {}

    /**
     * @throws \Exception
     */
    public function execute(LoginCommand $command): UserWithTokenResponse
    {
        $user = $this->users->findByEmail(new Email($command->email));

        if (! $user) {
            throw new InvalidCredentialsException('Invalid credentials', 422);
        }

        if (! $user->getPassword()->verify($command->password)) {
            throw new InvalidCredentialsException('Invalid credentials', 422);
        }

        $token = $this->tokens->generate($user);

        return new UserWithTokenResponse(
            new UserResponse($user),
            $token
        );
    }
}
