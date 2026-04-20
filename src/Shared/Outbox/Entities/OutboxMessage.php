<?php

declare(strict_types=1);

namespace Src\Shared\Outbox\Entities;

final class OutboxMessage
{
    public function __construct(
        public readonly int $id,
        public readonly string $eventType,
        public readonly array $payload,
        public readonly \DateTimeImmutable $occurredAt,
        public int $attempts = 0,
        public ?string $error = null,
        public ?\DateTimeImmutable $processedAt = null,
        public ?\DateTimeImmutable $failedAt = null,
        public ?string $aggregateId = null,
        public ?string $correlationId = null
    ) {}

    public function canRetry(int $maxAttempts = 3): bool
    {
        return $this->attempts < $maxAttempts && $this->failedAt === null;
    }

    public function markFailed(string $error): self
    {
        return new self(
            $this->id,
            $this->eventType,
            $this->payload,
            $this->occurredAt,
            $this->attempts,
            $error,
            $this->processedAt,
            new \DateTimeImmutable,
            $this->aggregateId,
            $this->correlationId
        );
    }

    public function incrementAttempts(): self
    {
        return new self(
            $this->id,
            $this->eventType,
            $this->payload,
            $this->occurredAt,
            $this->attempts + 1,
            $this->error,
            $this->processedAt,
            $this->failedAt,
            $this->aggregateId,
            $this->correlationId
        );
    }

    // Новые методы для удобства
    public function hasAggregateId(): bool
    {
        return $this->aggregateId !== null;
    }

    public function hasCorrelationId(): bool
    {
        return $this->correlationId !== null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'event_type' => $this->eventType,
            'aggregate_id' => $this->aggregateId,
            'correlation_id' => $this->correlationId,
            'attempts' => $this->attempts,
            'error' => $this->error,
            'occurred_at' => $this->occurredAt->format('Y-m-d H:i:s'),
            'processed_at' => $this->processedAt?->format('Y-m-d H:i:s'),
            'failed_at' => $this->failedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
