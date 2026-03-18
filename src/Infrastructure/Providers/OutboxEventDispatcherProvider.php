<?php

declare(strict_types=1);

namespace Src\Infrastructure\Providers;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use Src\Domain\User\Events\UserRegistered;
use Src\Domain\UserBonus\Listeners\GiveRegistrationBonusListener;
use Src\Infrastructure\Events\OutboxEventDispatcher;
use Src\Infrastructure\Logging\OutboxLogger;

final class OutboxEventDispatcherProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->app->singleton(OutboxEventDispatcher::class, function (Container $app) {
            $dispatcher = new OutboxEventDispatcher(
                $app,
                $app->make(OutboxLogger::class)
            );

            $this->registerListeners($dispatcher);

            return $dispatcher;
        });
    }

    private function registerListeners(OutboxEventDispatcher $dispatcher): void
    {
        $dispatcher->register(
            UserRegistered::class,
            GiveRegistrationBonusListener::class
        );
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->app->make(OutboxLogger::class)->info('Outbox Event Dispatcher registered');
        }
    }
}
