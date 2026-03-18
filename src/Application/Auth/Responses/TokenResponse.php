<?php

declare(strict_types=1);

namespace Src\Application\Auth\Responses;

final class TokenResponse
{
    public string $accessToken;

    public string $accessExpiresAt;

    public string $refreshToken;

    public string $expiresAt;

    public function __construct(
        string $accessToken,
        string $accessExpiresAt,
        string $refreshToken,
        string $expiresAt
    ) {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->expiresAt = $expiresAt;
        $this->accessExpiresAt = $accessExpiresAt;
    }
}
