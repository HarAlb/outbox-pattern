<?php

declare(strict_types=1);

namespace Src\Application\Auth;

final class RegisterCommand
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public ?string $surname = null,
    ) {}
}
