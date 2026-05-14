<?php

namespace Src\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Domain\User\Repositories\UserRepository;
use Src\Infrastructure\Persistence\Repositories\DatabaseUserRepository;

final class UserServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->app->singleton(UserRepository::class, DatabaseUserRepository::class);
    }
}
