<?php

namespace Src\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Shared\Contracts\TokenServiceInterface;
use Src\Shared\Services\TokenService;

final class TokenServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->app->singleton(TokenServiceInterface::class, TokenService::class);
    }
}
