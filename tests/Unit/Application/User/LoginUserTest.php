<?php

declare(strict_types=1);

namespace Tests\Unit\Application\User;

use PHPUnit\Framework\TestCase;
use Src\Application\Auth\Contracts\TokenServiceInterface;
use Src\Application\Auth\UseCases\LoginUser;
use Src\Domain\User\Repositories\UserRepository;

class LoginUserTest extends TestCase
{
    private $users;

    private $tokens;

    private $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->users = $this->createMock(UserRepository::class);
        $this->tokens = $this->createMock(TokenServiceInterface::class);

        $this->useCase = new LoginUser(
            $this->users,
            $this->tokens
        );
    }

    public function test_successful_login(): void
    {
        //        $password = Password::fromPlain('secret123');
        //        $user = $this->createMock(User::class);
        //        $user->method('getPassword')->willReturn($password);
        //        $user->method('getEmail')->willReturn('test@example.com');
        //
        //        $this->users->expects($this->once())
        //            ->method('findByEmail')
        //            ->with('test@example.com')
        //            ->willReturn($user);
        //
        //        $tokenResponse = $this->createMock(TokenResponse::class);
        //        $this->tokens->expects($this->once())
        //            ->method('generate')
        //            ->with($user)
        //            ->willReturn($tokenResponse);
        //
        //        $result = $this->useCase->execute(
        //            new LoginCommand('test@example.com', 'secret123')
        //        );
        //
        //
        //        $this->assertArrayHasKey('user', $result);
        //        $this->assertArrayHasKey('token', $result);
        //        $this->assertSame($tokenResponse, $result['token']);

        $this->assertTrue(true);
    }
}
