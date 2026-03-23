<?php

declare(strict_types=1);

namespace Src\Domain\User\Entities\ValueObject;

use InvalidArgumentException;

final class Name
{
    private string $value;

    public function __construct(string $name)
    {
        $this->assertValid($name);
        $this->value = $name;
    }

    private function assertValid(string $name): void
    {
        $name = trim($name);

        if ($name === '') {
            throw new InvalidArgumentException('Name cannot be empty');
        }

        if (mb_strlen($name) > 64) {
            throw new InvalidArgumentException('Name must be less than 64 characters');
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
