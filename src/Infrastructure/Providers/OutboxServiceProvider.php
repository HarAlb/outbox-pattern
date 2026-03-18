<?php

namespace Src\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Infrastructure\Persistence\DatabaseOutboxRepository;
use Src\Shared\Outbox\Repositories\OutboxRepository;

final class OutboxServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->app->singleton(OutboxRepository::class, DatabaseOutboxRepository::class);
    }
}
