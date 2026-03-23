<?php

declare(strict_types=1);

namespace Src\Domain\User\Entities\ValueObject;

use InvalidArgumentException;

final class Surname
{
    private string $value;

    public function __construct(string $surname)
    {
        $this->assertValid($surname);
        $this->value = $surname;
    }

    private function assertValid(string $surname): void
    {
        $surname = trim($surname);

        if ($surname === '') {
            throw new InvalidArgumentException('Surname cannot be empty');
        }

        if (mb_strlen($surname) > 255) {
            throw new InvalidArgumentException('Surname must be less than 255 characters');
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
