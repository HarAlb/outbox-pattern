<?php

declare(strict_types=1);

namespace Src\Application\Auth\Responses;

use Src\Domain\User\Entities\User;

final class UserResponse
{
    public int $id;

    public string $email;

    public string $name;

    public ?string $surname;

    public function __construct(User $user)
    {
        $this->id = $user->getId();
        $this->email = $user->getEmail();
        $this->name = $user->getName();
        $this->surname = $user->getSurname();
    }
}
