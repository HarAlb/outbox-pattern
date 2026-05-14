<?php

namespace Src\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Application\Auth\Contracts\TokenServiceInterface;
use Src\Infrastructure\Auth\TokenService;

final class TokenServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->app->singleton(TokenServiceInterface::class, TokenService::class);
    }
}
