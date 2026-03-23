<?php

namespace Src\Shared\Events;

abstract class DomainEvent
{
    private \DateTimeImmutable $occurredOn;

    public function __construct()
    {
        $this->occurredOn = new \DateTimeImmutable;
    }

    abstract public function toArray(): array;

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
