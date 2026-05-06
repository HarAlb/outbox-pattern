<?php

declare(strict_types=1);

namespace Src\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Application\Shared\Contracts\TransactionServiceInterface;
use Src\Infrastructure\Persistence\TransactionService;

final class TransactionServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->app->singleton(TransactionServiceInterface::class, TransactionService::class);
    }
}
