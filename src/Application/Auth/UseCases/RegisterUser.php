<?php

namespace Src\Application\Auth\UseCases;

use Src\Application\Auth\Contracts\TokenServiceInterface;
use Src\Application\Auth\RegisterCommand;
use Src\Application\Auth\Responses\UserResponse;
use Src\Application\Auth\Responses\UserWithTokenResponse;
use Src\Application\Shared\Contracts\TransactionServiceInterface;
use Src\Domain\Outbox\Repositories\OutboxRepository;
use Src\Domain\User\Entities\User;
use Src\Domain\User\Entities\ValueObject\Email;
use Src\Domain\User\Entities\ValueObject\Name;
use Src\Domain\User\Entities\ValueObject\Password;
use Src\Domain\User\Entities\ValueObject\Surname;
use Src\Domain\User\Events\UserRegistered;
use Src\Domain\User\Exceptions\EmailAlreadyExistsException;
use Src\Domain\User\Repositories\UserRepository;

final class RegisterUser
{
    public function __construct(
        private readonly UserRepository              $users,
        private readonly OutboxRepository            $outbox,
        private readonly TransactionServiceInterface $transaction,
        private readonly TokenServiceInterface       $tokens,
    )
    {
    }

    public function execute(RegisterCommand $command): UserWithTokenResponse
    {
        return $this->transaction->run(function () use ($command) {
            $user = new User(
                new Name($command->name),
                new Email($command->email),
                Password::fromPlain($command->password),
                $command->surname ? new Surname($command->surname) : null
            );

            if ($this->users->findByEmail($user->getEmail())) {
                throw new EmailAlreadyExistsException('Email already exists', 409);
            }

            $savedUser = $this->users->save($user);

            $event = new UserRegistered($savedUser->getId(), $savedUser->getEmail());

            $this->outbox->store($event, (string)$savedUser->getid()->getValue(), null);

            $token = $this->tokens->generate($savedUser);

            return new UserWithTokenResponse(
                new UserResponse($user),
                $token
            );
        });
    }
}
