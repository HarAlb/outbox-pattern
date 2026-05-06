<?php

declare(strict_types=1);

namespace Src\Infrastructure\Persistence\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Domain\Outbox\Entities\OutboxMessage;
use Src\Domain\Outbox\Repositories\OutboxRepository;
use Src\Domain\Shared\Events\DomainEvent;

final class DatabaseOutboxRepository implements OutboxRepository
{
    private const MAX_RETRY_ATTEMPTS = 3;

    #[\Override]
    public function store(DomainEvent $event, ?string $aggregateId = null, ?string $correlationId = null): void
    {
        DB::table('outbox_messages')->insert([
            'type' => get_class($event),
            'payload' => json_encode($event->toArray()),
            'aggregate_id' => $aggregateId,
            'correlation_id' => $correlationId ?? $this->generateCorrelationId(),
            'occurred_at' => now(),
            'processed' => false,
            'failed' => false,
            'attempts' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    #[\Override]
    public function fetchNext(): ?OutboxMessage
    {
        return DB::transaction(function () {
            $row = DB::table('outbox_messages')
                ->where('processed', false)
                ->where('failed', false)
                ->where('attempts', '<', self::MAX_RETRY_ATTEMPTS)
                ->orderBy('id')
                ->lock('FOR UPDATE SKIP LOCKED')
                ->first();

            if (! $row) {
                return null;
            }

            return $this->mapToEntity($row);
        });
    }

    #[\Override]
    public function markAsProcessed(int $id): void
    {
        DB::table('outbox_messages')
            ->where('id', $id)
            ->update([
                'processed' => true,
                'processed_at' => now(),
                'updated_at' => now(),
            ]);
    }

    #[\Override]
    public function markAsFailed(int $id, ?string $error = null): void
    {
        DB::table('outbox_messages')
            ->where('id', $id)
            ->update([
                'failed' => true,
                'failed_at' => now(),
                'error' => $error,
                'updated_at' => now(),
            ]);
    }

    #[\Override]
    public function incrementAttempts(int $id, ?string $error = null): void
    {
        DB::table('outbox_messages')
            ->where('id', $id)
            ->update([
                'attempts' => DB::raw('attempts + 1'),
                'error' => $error,
                'updated_at' => now(),
            ]);
    }

    #[\Override]
    public function getFailedMessages(): array
    {
        $rows = DB::table('outbox_messages')
            ->where('failed', true)
            ->orderBy('failed_at')
            ->get();

        return $rows->map(fn ($row) => $this->mapToEntity($row))->toArray();
    }

    #[\Override]
    public function retryFailed(int $id): void
    {
        DB::table('outbox_messages')
            ->where('id', $id)
            ->where('failed', true)
            ->update([
                'failed' => false,
                'failed_at' => null,
                'error' => null,
                'attempts' => 0,
                'updated_at' => now(),
            ]);
    }

    #[\Override]
    public function getStats(): array
    {
        $stats = DB::table('outbox_messages')
            ->select(
                [
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN processed = true THEN 1 ELSE 0 END) as processed_count'),
                    DB::raw('SUM(CASE WHEN failed = true THEN 1 ELSE 0 END) as failed_count'),
                    DB::raw('SUM(CASE WHEN processed = false AND failed = false AND attempts < ' . self::MAX_RETRY_ATTEMPTS . ' THEN 1 ELSE 0 END) as pending_count'),
                    DB::raw('SUM(CASE WHEN attempts >= ' . self::MAX_RETRY_ATTEMPTS . ' AND failed = false THEN 1 ELSE 0 END) as exhausted_count'),
                    DB::raw('MAX(attempts) as max_attempts'),
                ]
            )
            ->first();

        if (! $stats) {
            return [
                'total' => 0,
                'processed' => 0,
                'failed' => 0,
                'pending' => 0,
                'exhausted' => 0,
                'max_attempts' => 0,
            ];
        }

        return [
            'total' => (int) $stats->total,
            'processed' => (int) $stats->processed_count,
            'failed' => (int) $stats->failed_count,
            'pending' => (int) $stats->pending_count,
            'exhausted' => (int) $stats->exhausted_count,
            'max_attempts' => (int) $stats->max_attempts,
        ];
    }

    #[\Override]
    public function cleanupOldProcessed(int $days = 7): int
    {
        return DB::table('outbox_messages')
            ->where('processed', true)
            ->where('created_at', '<', now()->subDays($days))
            ->delete();
    }

    private function mapToEntity($row): OutboxMessage
    {
        Log::info('asdad');

        return new OutboxMessage(
            id: $row->id,
            eventType: $row->type,
            payload: json_decode($row->payload, true),
            occurredAt: new \DateTimeImmutable($row->occurred_at),
            attempts: (int) $row->attempts,
            error: $row->error,
            processedAt: $row->processed_at ? new \DateTimeImmutable($row->processed_at) : null,
            failedAt: $row->failed_at ? new \DateTimeImmutable($row->failed_at) : null,
            aggregateId: $row->aggregate_id,
            correlationId: $row->correlation_id
        );
    }

    private function generateCorrelationId(): string
    {
        return sprintf(
            '%s-%s',
            date('YmdHis'),
            bin2hex(random_bytes(8))
        );
    }
}
