<?php

declare(strict_types=1);

namespace Src\Domain\User\Events;

final class UserRegistered
{
    public function __construct(
        public readonly int $userId,
        public readonly string $email
    ) {}

    public function toArray(): array
    {
        return [
            get_object_vars($this),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(...$data);
    }
}
