<?php

declare(strict_types=1);

namespace App\Virtual\Responses\Auth;

use OpenApi\Attributes as OAT;

#[
    OAT\Schema(
        title: 'Token Response',
        description: 'Response containing authentication tokens',
    )
]
final class TokenResponseDoc
{
    #[
        OAT\Property(
            description: 'JWT access token',
            example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAiLCJpYXQiOjE3MTA4OTYwMDAsImV4cCI6MTcxMDg5OTYwMCwidHlwZSI6ImFjY2VzcyIsImVtYWlsIjoidGVzdEBleGFtcGxlLmNvbSJ9.abc123...'
        )
    ]
    public string $access_token;

    #[
        OAT\Property(
            description: 'Access token expiration timestamp (ISO 8601 format)',
            example: '2024-03-20T10:30:00Z'
        )
    ]
    public string $access_expires_at;

    #[
        OAT\Property(
            description: 'JWT refresh token (used to obtain new access token)',
            example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAiLCJpYXQiOjE3MTA4OTYwMDAsImV4cCI6MTcxMDk4MjQwMCwidHlwZSI6InJlZnJlc2gifQ.xyz789...'
        )
    ]
    public string $refresh_token;

    #[
        OAT\Property(
            description: 'Refresh token expiration timestamp (ISO 8601 format)',
            example: '2024-03-21T10:30:00Z'
        )
    ]
    public string $refresh_expires_at;
}
