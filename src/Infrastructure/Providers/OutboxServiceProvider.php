<?php

namespace Src\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Domain\Outbox\Repositories\OutboxRepository;
use Src\Infrastructure\Persistence\Repositories\DatabaseOutboxRepository;

final class OutboxServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->app->singleton(OutboxRepository::class, DatabaseOutboxRepository::class);
    }
}
