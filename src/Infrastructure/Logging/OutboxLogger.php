<?php

declare(strict_types=1);

namespace Src\Infrastructure\Logging;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

final class OutboxLogger
{
    private Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger('outbox');

        $this->logger->pushHandler(new RotatingFileHandler(
            storage_path('logs/outbox.log'),
            7,
            Logger::INFO
        ));
    }

    public function info(string $message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->logger->warning($message, $context);
    }
}
