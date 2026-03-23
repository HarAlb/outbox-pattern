<?php

declare(strict_types=1);

namespace Src\Domain\User\Events;

use Src\Domain\Common\ValueObject\Id;
use Src\Domain\User\Entities\ValueObject\Email;
use Src\Shared\Events\DomainEvent;

final class UserRegistered extends DomainEvent
{
    public function __construct(
        private readonly Id $userId,
        private readonly Email $email,
        private ?string $ipAddress = null,
        private ?string $userAgent = null
    ) {}

    #[\Override]
    public function toArray(): array
    {
        return [
            'user_id' => $this->userId->getValue(),
            'email' => $this->email->value(),
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
        ];
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function getUserId(): Id
    {
        return $this->userId;
    }

    public function setIpAddress(?string $ipAddress): void
    {
        $this->ipAddress = $ipAddress;
    }

    public function setUserAgent(?string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }
}
