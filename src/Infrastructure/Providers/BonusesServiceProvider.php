<?php

declare(strict_types=1);

namespace Src\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Domain\User\Events\UserRegistered;
use Src\Domain\UserBonus\Listeners\GiveRegistrationBonusListener;
use Src\Domain\UserBonus\Repositories\UserBonusesRepository;
use Src\Infrastructure\Persistence\DatabaseUserBonusesRepository;

final class BonusesServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register()
    {
        $this->app->singleton(UserBonusesRepository::class, DatabaseUserBonusesRepository::class);
    }

    public function boot(): void
    {
        \Illuminate\Support\Facades\Event::listen(
            UserRegistered::class,
            GiveRegistrationBonusListener::class
        );
    }
}
