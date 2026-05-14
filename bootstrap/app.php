<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'jwt.auth' => \Src\Infrastructure\Http\Middleware\JwtAuthMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Src\Application\Auth\Exceptions\InvalidCredentialsException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => null,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        });

        $exceptions->render(function (\Src\Domain\User\Exceptions\EmailAlreadyExistsException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => null,
            ], Response::HTTP_CONFLICT);
        });
    })->withCommands([
        \Src\Infrastructure\Console\OutboxWorker::class,
        \Src\Infrastructure\Console\OutboxMonitor::class,
    ])->create();
