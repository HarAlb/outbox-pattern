<?php

declare(strict_types=1);

namespace Src\Application\Auth\Responses;

final class TokenResponse implements \JsonSerializable
{
    public string $accessToken;

    public string $accessExpiresAt;

    public string $refreshToken;

    public string $refreshExpiresAt;

    public function __construct(
        string $accessToken,
        string $accessExpiresAt,
        string $refreshToken,
        string $refreshExpiresAt
    ) {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->refreshExpiresAt = $refreshExpiresAt;
        $this->accessExpiresAt = $accessExpiresAt;
    }

    #[\Override]
    public function jsonSerialize(): mixed
    {
        return [
            'access_token' => $this->accessToken,
            'access_expired_at' => $this->accessExpiresAt,
            'refresh_token' => $this->refreshToken,
            'refresh_expired_at' => $this->refreshExpiresAt,
        ];
    }
}
