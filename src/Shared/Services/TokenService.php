<?php

declare(strict_types=1);

namespace Src\Shared\Services;

use Carbon\Carbon;
use Src\Application\Auth\Responses\TokenResponse;
use Src\Domain\User\Entities\User;
use Src\Infrastructure\Auth\JwtUserAdapter;
use Tymon\JWTAuth\JWT;

final class TokenService
{
    private int $accessTtl;

    private int $refreshTtl;

    public function __construct(private JWT $JWTAuth)
    {
        $this->accessTtl = config('jwt.ttl') * 60;
        $this->refreshTtl = config('jwt.refresh_ttl') * 60;
    }

    public function generate(User $user): TokenResponse
    {
        $now = time();

        $jwtUser = new JwtUserAdapter($user);

        $accessExpiresAt = Carbon::createFromTimestamp($now + $this->accessTtl)->toISOString();

        if ($accessExpiresAt === null) {
            throw new \RuntimeException('Failed to generate access token');
        }

        $refreshExpiresAt = Carbon::createFromTimestamp($now + $this->refreshTtl)->toISOString();

        if ($refreshExpiresAt === null) {
            throw new \RuntimeException('Failed to generate refresh expired token');
        }

        $accessToken = $this->JWTAuth
            ->customClaims([
                'type' => 'access',
                'exp' => $now + $this->accessTtl,
                'email' => $user->getEmail(),
            ])->fromUser($jwtUser);

        $refreshToken = $this->JWTAuth
            ->customClaims([
                'type' => 'refresh',
                'exp' => $now + $this->refreshTtl,
            ])->fromUser($jwtUser);

        if ($refreshToken === null) {
            throw new \RuntimeException('Failed to generate refresh token');
        }

        return new TokenResponse(
            $accessToken,
            $accessExpiresAt,
            $refreshToken,
            $refreshExpiresAt
        );
    }
}
