<?php

return [
    App\Providers\AppServiceProvider::class,
    \Src\Infrastructure\Providers\OutboxServiceProvider::class,
    \Src\Infrastructure\Providers\UserServiceProvider::class,
    \Src\Infrastructure\Providers\BonusesServiceProvider::class,
    \Src\Infrastructure\Providers\OutboxEventDispatcherProvider::class,
];
