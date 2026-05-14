<?php

declare(strict_types=1);

namespace Src\Application\Auth;
final class LoginCommand
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}
}
