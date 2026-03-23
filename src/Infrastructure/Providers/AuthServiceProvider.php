<?php

declare(strict_types=1);

namespace Src\Infrastructure\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Src\Infrastructure\Auth\DomainUserProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $auth = $this->app->get('auth');

        $auth->provider('domain_users', function ($app) {
            return new DomainUserProvider(
                $app->make(\Src\Domain\User\Repositories\UserRepository::class)
            );
        });
    }
}
