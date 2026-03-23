<?php

declare(strict_types=1);

namespace Src\Shared\Services;

use Carbon\Carbon;
use Src\Application\Auth\Responses\TokenResponse;
use Src\Domain\User\Entities\User;
use Src\Infrastructure\Auth\JwtUserAdapter;
use Src\Shared\Contracts\TokenServiceInterface;
use Tymon\JWTAuth\JWT;

final class TokenService implements TokenServiceInterface
{
    private int $accessTtl;

    private int $refreshTtl;

    public function __construct(private JWT $jwt)
    {
        $this->accessTtl = config('jwt.ttl') * 60;
        $this->refreshTtl = config('jwt.refresh_ttl') * 60;
    }

    #[\Override]
    public function generate(User $user): TokenResponse
    {
        $now = time();
        $jwtUser = new JwtUserAdapter($user);

        $accessToken = $this->jwt
            ->customClaims($this->accessClaims($user, $now))
            ->fromUser($jwtUser);

        $refreshToken = $this->jwt
            ->customClaims($this->refreshClaims($user, $now))
            ->fromUser($jwtUser);

        return new TokenResponse(
            $accessToken,
            $this->formatExpirationTimestamp($now + $this->accessTtl),
            $refreshToken,
            $this->formatExpirationTimestamp($now + $this->refreshTtl)
        );
    }

    private function accessClaims(User $user, int $now): array
    {
        return [
            'type' => 'access',
            'sub' => $user->getId()->getValue(),
            'iat' => $now,
            'exp' => $now + $this->accessTtl,
        ];
    }

    private function refreshClaims(User $user, int $now): array
    {
        return [
            'type' => 'refresh',
            'sub' => $user->getId()->getValue(),
            'iat' => $now,
            'exp' => $now + $this->refreshTtl,
        ];
    }

    private function formatExpirationTimestamp(int $timestamp): string
    {
        return Carbon::createFromTimestamp($timestamp)->toIso8601ZuluString();
    }
}
