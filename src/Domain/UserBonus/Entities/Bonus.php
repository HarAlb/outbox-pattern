<?php

declare(strict_types=1);

namespace Src\Domain\UserBonus\Entities;

final class Bonus
{
    private ?int $id;

    public function __construct(
        private int $userId,
        private int $amount
    ) {}

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }
}
