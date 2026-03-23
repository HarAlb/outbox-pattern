<?php

declare(strict_types=1);

namespace Src\Application\Auth\Responses;

use Src\Domain\User\Entities\User;

final class UserResponse implements \JsonSerializable
{
    public int $id;

    public string $email;

    public string $name;

    public ?string $surname;

    public function __construct(User $user)
    {
        $this->id = $user->getId()->getValue();
        $this->email = $user->getEmail()->value();
        $this->name = $user->getName()->value();
        $this->surname = $user->getSurname()?->value();
    }

    #[\Override]
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'surname' => $this->surname,
        ];
    }
}
