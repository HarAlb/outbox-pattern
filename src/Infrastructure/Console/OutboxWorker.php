<?php

declare(strict_types=1);

namespace Src\Infrastructure\Console;

use Illuminate\Console\Command;
use Src\Domain\Outbox\Repositories\OutboxRepository;
use Src\Infrastructure\Events\OutboxEventDispatcher;
use Src\Infrastructure\Logging\OutboxLogger;

class OutboxWorker extends Command
{
    protected $signature = 'outbox:worker
        {--sleep=1 : Seconds to sleep between iterations}
        {--limit=0 : Max messages to process}
        {--maxAttempts= 3: Max Attempts}';

    protected $description = 'Process Outbox messages';

    public function __construct(
        private OutboxRepository $outbox,
        private OutboxEventDispatcher $dispatcher,
        private OutboxLogger $logger
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->logger->info('Outbox Worker started');

        $processed = 0;
        $sleep = $this->getSafeIntOption('sleep', 1);
        $limit = $this->getSafeIntOption('limit', 0);
        $maxAttempts = $this->getSafeIntOption('maxAttempts', 0);

        while (true) {
            try {
                $message = $this->outbox->fetchNext();
                if (! $message) {
                    if ($limit > 0 && $processed >= $limit) {
                        break;
                    }
                    sleep($sleep);

                    continue;
                }

                $this->logger->info("Processing message {$message->id}", [
                    'type' => $message->eventType,
                    'attempt' => $message->attempts + 1,
                ]);

                $this->dispatcher->dispatch($message);

                $this->outbox->markAsProcessed($message->id);

                $this->logger->info("Message {$message->id} processed");

                $processed++;

            } catch (\Throwable $e) {
                $this->logger->error("Worker error: {$e->getMessage()}");

                $this->outbox->incrementAttempts($message->id, $e->getMessage());

                if ($message->attempts + 1 >= $maxAttempts) {
                    $this->outbox->markAsFailed($message->id, $e->getMessage());
                }

                sleep(2);
            }
        }

        $this->logger->info("Worker finished. Processed: {$processed}");

        return 0;
    }

    private function getSafeIntOption(string $name, int $default): int
    {
        $value = $this->option($name);

        // Если null или пустая строка - возвращаем default
        if ($value === null || $value === '') {
            return $default;
        }

        // Если массив - берем первый элемент
        if (is_array($value)) {
            $value = $value[0] ?? null;
        }

        // Проверяем, что это число
        if (! is_numeric($value)) {
            $this->logger->warning("Option '{$name}' is not numeric, using default {$default}", [
                'value' => $value,
            ]);

            return $default;
        }

        return (int) $value;
    }
}
