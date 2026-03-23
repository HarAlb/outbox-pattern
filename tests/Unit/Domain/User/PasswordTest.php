<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\User;

use PHPUnit\Framework\TestCase;
use Src\Domain\User\Entities\ValueObject\Password;

final class PasswordTest extends TestCase
{
    public function test_it_hashes_password(): void
    {
        $password = Password::fromPlain('secret');

        $this->assertNotEquals('secret', $password->value());
    }

    public function test_it_verifies_password(): void
    {
        $password = Password::fromPlain('secret');

        $this->assertTrue($password->verify('secret'));
        $this->assertFalse($password->verify('wrong'));
    }
}
