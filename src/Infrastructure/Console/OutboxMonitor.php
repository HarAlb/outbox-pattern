<?php

declare(strict_types=1);

namespace Src\Infrastructure\Console;

use Illuminate\Console\Command;
use Src\Shared\Outbox\Repositories\OutboxRepository;

class OutboxMonitor extends Command
{
    protected $signature = 'outbox:monitor
        {--failed : Show failed messages}
        {--stats : Show statistics}
        {--retry= : Retry failed message by ID}
        {--cleanup= : Cleanup processed messages older than N days}';

    protected $description = 'Monitor and manage outbox messages';

    public function handle(OutboxRepository $outbox): int
    {
        if ($this->option('stats')) {
            return $this->showStats($outbox);
        }

        if ($this->option('failed')) {
            return $this->showFailed($outbox);
        }

        if ($retry = $this->option('retry')) {
            if (is_numeric($retry)) {
                $messageId = (int) $retry;

                if ($messageId > 0) {
                    return $this->retryMessage($outbox, $messageId);
                }

                $this->warn("ID сообщения должен быть положительным числом, получено: {$retry}");

                return 1;
            }
        }

        if ($days = $this->option('cleanup')) {
            if (is_numeric($days)) {
                $days = (int) $days;

                return $this->cleanup($outbox, $days);
            }

        }

        $this->error('Please specify an option. Use --help for details.');

        return 1;
    }

    private function showStats(OutboxRepository $outbox): int
    {
        $stats = $outbox->getStats();

        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Messages', $stats['total']],
                ['Processed', $stats['processed']],
                ['Failed', $stats['failed']],
                ['Pending', $stats['pending']],
                ['Attempts Exhausted', $stats['exhausted']],
                ['Max Attempts', $stats['max_attempts']],
            ]
        );

        return 0;
    }

    private function showFailed(OutboxRepository $outbox): int
    {
        $failed = $outbox->getFailedMessages();

        if (empty($failed)) {
            $this->info('No failed messages found.');

            return 0;
        }

        $rows = array_map(fn ($msg) => [
            $msg->id,
            $msg->eventType,
            $msg->attempts,
            $msg->error,
            $msg->failedAt?->format('Y-m-d H:i:s') ?? null,
        ], $failed);

        $this->table(
            ['ID', 'Type', 'Attempts', 'Error', 'Failed At'],
            $rows
        );

        return 0;
    }

    private function retryMessage(OutboxRepository $outbox, int $id): int
    {
        try {
            $outbox->retryFailed($id);
            $this->info("Message {$id} queued for retry.");

            return 0;
        } catch (\Throwable $e) {
            $this->error("Failed to retry message: {$e->getMessage()}");

            return 1;
        }
    }

    private function cleanup(OutboxRepository $outbox, int $days): int
    {
        $deleted = $outbox->cleanupOldProcessed($days);
        $this->info("Deleted {$deleted} processed messages older than {$days} days.");

        return 0;
    }
}
