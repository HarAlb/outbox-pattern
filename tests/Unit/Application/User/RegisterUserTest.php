<?php

declare(strict_types=1);

namespace Tests\Unit\Application\User;

use PHPUnit\Framework\TestCase;
use Src\Application\Auth\Contracts\TokenServiceInterface;
use Src\Application\Auth\RegisterCommand;
use Src\Application\Auth\Responses\TokenResponse;
use Src\Application\Auth\Responses\UserResponse;
use Src\Application\Auth\Responses\UserWithTokenResponse;
use Src\Application\Auth\UseCases\RegisterUser;
use Src\Application\Shared\Contracts\TransactionServiceInterface;
use Src\Domain\Common\ValueObject\Id;
use Src\Domain\Outbox\Repositories\OutboxRepository;
use Src\Domain\User\Entities\User;
use Src\Domain\User\Events\UserRegistered;
use Src\Domain\User\Repositories\UserRepository;

class RegisterUserTest extends TestCase
{
    public function test_user_register_success(): void
    {
        $users = $this->createMock(UserRepository::class);
        $tokens = $this->createMock(TokenServiceInterface::class);
        $outbox = $this->createMock(OutboxRepository::class);
        $transaction = $this->createMock(TransactionServiceInterface::class);

        $transaction->expects($this->once())
            ->method('run')
            ->willReturnCallback(fn ($callback) => $callback());

        $users->expects($this->once())
            ->method('findByEmail')
            ->with('test@example.com')
            ->willReturn(null);

        $users->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (User $user) {
                $user->setId(new Id(123));

                return $user;
            });

        $tokenResponse = new TokenResponse(
            'access-token-123',
            '2024-01-01T00:00:00Z',
            'refresh-token-123',
            '2024-01-02T00:00:00Z'
        );

        $tokens->expects($this->once())
            ->method('generate')
            ->with($this->isInstanceOf(User::class))
            ->willReturn($tokenResponse);

        $outbox->expects($this->once())
            ->method('store')
            ->with(
                $this->isInstanceOf(UserRegistered::class),
                '123',
                null
            );

        $useCase = new RegisterUser(
            $users,
            $outbox,
            $transaction,
            $tokens,
        );

        $result = $useCase->execute(
            new RegisterCommand(
                'Albert',
                'test@example.com',
                'secret',
                null
            )
        );

        $this->assertInstanceOf(UserWithTokenResponse::class, $result);
        $this->assertInstanceOf(UserResponse::class, $result->user);
        $this->assertEquals($tokenResponse, $result->token);
    }
}
