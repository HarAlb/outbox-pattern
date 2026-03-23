<?php

declare(strict_types=1);

namespace Src\Domain\User\Entities\ValueObject;

use InvalidArgumentException;

final class Email
{
    private string $value;

    public function __construct(string $email)
    {
        $this->assertValid($email);
        $this->value = mb_strtolower($email);
    }

    private function assertValid(string $email): void
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email: {$email}");
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Email $email): bool
    {
        return $this->value === $email->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function fromString(string $email): self
    {
        return new self($email);
    }
}
