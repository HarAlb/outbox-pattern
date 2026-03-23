<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\User;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Src\Domain\User\Entities\ValueObject\Email;

final class EmailTest extends TestCase
{
    public function test_it_creates_valid_email(): void
    {
        $email = new Email('test@example.com');

        $this->assertEquals('test@example.com', (string) $email);
    }

    public function test_it_throws_exception_for_invalid_email(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Email('invalid-email');
    }

    public function test_emails_are_equal(): void
    {
        $a = new Email('test@example.com');
        $b = new Email('TEST@example.com');

        $this->assertTrue($a->equals($b));
    }
}
