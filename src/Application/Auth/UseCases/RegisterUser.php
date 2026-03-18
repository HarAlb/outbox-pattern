<?php

namespace Src\Application\Auth\UseCases;

use Src\Application\Auth\RegisterCommand;
use Src\Application\Auth\Responses\UserResponse;
use Src\Domain\User\Entities\User;
use Src\Domain\User\Events\UserRegistered;
use Src\Domain\User\Repositories\UserRepository;
use Src\Shared\Outbox\Repositories\OutboxRepository;
use Src\Shared\Services\TokenService;
use Src\Shared\Services\TransactionService;

final class RegisterUser
{
    public function __construct(
        private UserRepository $users,
        private OutboxRepository $outbox,
        private TransactionService $transaction,
        private TokenService $tokens,
    ) {}

    public function execute(RegisterCommand $command): array
    {
        return $this->transaction->run(function () use ($command) {
            $user = new User(
                $command->name,
                $command->email,
                bcrypt($command->password),
                $command->surname
            );

            if ($this->users->findByEmail($user->getEmail())) {
                throw new \Exception('Email already exists', 400);
            }

            $savedUser = $this->users->save($user);

            $event = new UserRegistered($savedUser->getid(), $savedUser->getEmail());

            $this->outbox->store($event, (string) $savedUser->getid(), null);

            $token = $this->tokens->generate($savedUser);

            return [
                'user' => new UserResponse($savedUser),
                'token' => $token,
            ];
        });
    }
}
