<?php

declare(strict_types=1);

namespace Src\Domain\Outbox\Repositories;

use Src\Domain\Outbox\Entities\OutboxMessage;
use Src\Domain\Shared\Events\DomainEvent;

interface OutboxRepository
{
    public function store(DomainEvent $event, ?string $aggregateId = null, ?string $correlationId = null): void;

    public function fetchNext(): ?OutboxMessage;

    public function markAsProcessed(int $id): void;

    public function markAsFailed(int $id, ?string $error = null): void;

    public function incrementAttempts(int $id, ?string $error = null): void;

    /** @return OutboxMessage[] */
    public function getFailedMessages(): array;

    public function retryFailed(int $id): void;

    public function getStats(): array;

    public function cleanupOldProcessed(int $days = 7): int;
}
